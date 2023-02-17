<script setup lang="ts">
    import { axios } from '@/services/auth';
    import { ref, computed, onMounted } from 'vue';
    import { Roles } from '@/enums/roles';

    const viewings = ref();
    const agents = ref();
    const timeFilter = ref();
    const assignedFilter = ref();

    const fetchViewings = async () => {
        try {
            axios.defaults.headers.common['Accept'] = 'application/json';
            const viewingsResponse = await axios.get(`${import.meta.env.VITE_BASE_API_URL}/viewings`);
            viewings.value = viewingsResponse.data;
            const agentsResponse = await axios.get(`${import.meta.env.VITE_BASE_API_URL}/users`, { params: {roles: Roles.Agency}});
            agents.value = agentsResponse.data;
        } catch (error) {
            console.log(error);
        }
    };

    onMounted(async () => {
        await fetchViewings();
    });

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
    <v-container>
        <v-row align="center">
            <v-col cols="12">
                <h1 class="mb-5 text-h4 font-weight-bold heading-sentence">Demandes de <span>visite</span></h1>
                <h3 class="heading-sentence"><span class="font-weight-medium">Filtrer</span> les visites</h3>
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
                        <tr>
                            <th class="text-left">Locataire</th>
                            <th class="text-left">Agent responsable</th>
                            <th class="text-left">Propriétaire</th>
                            <th class="text-left">Bien</th>
                            <th class="text-left">Créneau</th>
                            <th class="text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="viewing in showedViewings" :key="viewing.id">
                            <td>{{ viewing.lodger.firstname }} {{ viewing.lodger.lastname }}</td>
                            <td>{{ viewing.agent?.firstname ?? 'Non défini' }} {{ viewing.agent?.lastname }}</td>
                            <td>{{ viewing.availaibility.request.property.owner.firstname }} {{ viewing.availaibility.request.property.owner.lastname }}</td>
                            <td>
                                <router-link :to="{ name: `${Roles.Agency}_property_details`, params: {id: viewing.availaibility.request.property.id} }" class="text-decoration-underline text-indigo">
                                    {{ viewing.availaibility.request.property.title }}
                                </router-link>
                            </td>
                            <td>{{ viewing.availaibility.slot }}</td>
                            <td>
                                <router-link :to="{ name: `${Roles.Agency}_show_visit`, params: {id: viewing.id} }">
                                    <v-btn color="primary" variant="tonal">Voir</v-btn>
                                </router-link>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
            </v-col>
        </v-row>
    </v-container>
</template>

<style scoped>
    tbody tr:hover {
        background-color: rgb(248, 248, 248);
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