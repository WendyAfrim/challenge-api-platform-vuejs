<script setup lang="ts">
    import { useRoute } from 'vue-router';
    import { axios } from '@/services/auth';
    import { ref, computed } from 'vue';
import router from '@/router';

    const viewings = ref();
    const timeFilter = ref();
    const assignedFilter = ref();

    try {
        const response = await axios.get('https://localhost/viewings/');
        viewings.value = response.data;
        console.log(response);
    } catch (error) {
        console.log(error);
    }

    const showedViewings = computed(() => {
        let showed: any[] = [];
        if (timeFilter.value) {
            if (timeFilter.value === 'passed') {
                showed = viewings.value.filter((viewing: any) => new Date(viewing.availaibility.slot).getTime() < new Date().getTime());
            } else if (timeFilter.value === 'next_days') {
                showed = viewings.value.filter((viewing: any) => new Date(viewing.availaibility.slot).getTime() > new Date().getTime() && new Date(viewing.availaibility.slot).getTime() < new Date().getTime() + 3 * 24 * 60 * 60 * 1000);
            } else if (timeFilter.value === 'not_passed') {
                showed = viewings.value.filter((viewing: any) => new Date(viewing.availaibility.slot).getTime() > new Date().getTime());
            }
        } else {
            showed = viewings.value;
        }
        if (assignedFilter.value) {
            if (assignedFilter.value === 'assigned')
                showed = showed.filter((viewing: any) => viewing.agent !== undefined);
            else if (assignedFilter.value === 'non_assigned')
                showed = showed.filter((viewing: any) => viewing.agent === undefined);
        }
        return showed;
    });
</script>
<template>
    <div class="mx-16 h-75">
        <h1 class="mb-10 text-h4 font-weight-bold">Demandes de visite</h1>
        <v-chip-group v-model="assignedFilter">
            <v-chip filter selected-class="text-success" class="ma-2" size="x-large" value="assigned">Assignées</v-chip>
            <v-chip filter selected-class="text-error" class="ma-2" size="x-large" value="non_assigned">Non assignées</v-chip>
        </v-chip-group>
        <v-chip-group selected-class="text-primary" v-model="timeFilter">
            <v-chip filter class="ma-2" size="x-large" value="passed">Passées</v-chip>
            <v-chip filter class="ma-2" size="x-large" value="next_days">3 prochains jours</v-chip>
            <v-chip filter class="ma-2" size="x-large" value="not_passed">Non passées</v-chip>
        </v-chip-group>
        <v-table>
            <thead>
                <tr class="">
                    <th class="text-left">Locataire</th>
                    <th class="text-left">Agent responsable</th>
                    <th class="text-left">Propriétaire</th>
                    <th class="text-left">Bien</th>
                    <th class="text-left">Créneau</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="viewing in showedViewings" :key="viewing.id" @click="router.push({ name: 'agency_show_user', params: {id: viewing.id}})">
                    <td>{{ viewing.lodger.firstname }} {{ viewing.lodger.lastname }}</td>
                    <td>{{ viewing.agent?.firstname }} {{ viewing.agent?.lastname }}</td>
                    <td>{{ viewing.availaibility.property.owner.firstname }} {{ viewing.availaibility.property.owner.lastname }}</td>
                    <td>{{ viewing.availaibility.property.title }}</td>
                    <td>{{ (new Date(viewing.availaibility.slot)).toISOString().slice(0, 19).replace(/-/g, "/").replace("T", " ") }}</td>
                </tr>
            </tbody>
        </v-table>
    </div>
</template>

<style scoped>
    tbody tr:hover {
        background-color: rgb(234, 237, 238);
        cursor: pointer;
    }
    th {
        padding: 0.5rem 1rem;
        font-size: large;
    }
    td {
        padding: 0.5rem 1rem;
        font-size: large;
    }
</style>