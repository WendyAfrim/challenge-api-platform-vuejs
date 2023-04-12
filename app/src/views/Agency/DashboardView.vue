<script setup lang="ts">
    import { Roles } from '@/enums/roles';
    import { axios } from '@/services/auth';
    import { ref, computed, onMounted } from 'vue';
    import router from '@/router';
    import { UserValidationStatus } from '@/enums/UserValidationStatus';

    const users = ref([]);
    const selection = ref([]);

    const getUsers = async () => {
        try {
            axios.defaults.headers.common['Accept'] = 'application/json';
            const response = await axios.get(`${import.meta.env.VITE_BASE_API_URL}/users`, { params: {roles: Roles.Tenant}});
            users.value = response.data;
        } catch (error) {
            throw 'Error while fetching users';
        }
    }

    onMounted(async () => {
        await getUsers();
    });

    const showedUsers = computed(() => {
        if (!selection.value.length) {
            return users.value;
        }
        let showed: any[] = [];
        selection.value.forEach((status: string) => {
            showed = showed.concat(users.value.filter((user: any) => user.validationStatus === status));
        });
        return showed;
    });
</script>

<template>
    <v-row>
        <v-col cols="12">
            <h3 class="heading-sentence"><span class="font-weight-medium">Filtrer</span> les dossiers</h3>
            <v-chip-group multiple selected-class="text-primary" v-model="selection">
                <v-chip filter class="ma-2" size="x-large" :value="UserValidationStatus.ToComplete">À compléter</v-chip>
                <v-chip filter class="ma-2" size="x-large" :value="UserValidationStatus.ToReview">À valider</v-chip>
                <v-chip filter class="ma-2" size="x-large" :value="UserValidationStatus.Validated">Validés</v-chip>
            </v-chip-group>
            <v-table>
                <thead>
                    <tr class="">
                        <th class="text-left font-weight-bold">Prénom</th>
                        <th class="text-left">Nom</th>
                        <th class="text-left">Situation</th>
                        <th class="text-left">Revenus</th>
                        <th class="text-left">Statut du dossier</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in showedUsers" :key="user.email" @click="router.push({ name: `${Roles.Agency}_show_user`, params: {id: user.id}})">
                        <td>{{ user?.firstname ?? '-' }}</td>
                        <td>{{ user?.lastname ?? '-' }}</td>
                        <td>{{ user.situation !== undefined ? $t(`work_situation.${user.situation}`) : '-' }}</td>
                        <td>{{ user.salary ?? '0' }} €</td>
                        <td>{{ $t(`validation_status.${user.validationStatus}`) }}</td>
                    </tr>
                </tbody>
            </v-table>
        </v-col>
    </v-row>
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