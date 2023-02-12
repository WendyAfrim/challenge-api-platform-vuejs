<script setup lang="ts">
    import { Roles } from '@/enums/roles';
    import { useAuthStore } from '@/stores/auth.store';
    import { axios } from '@/services/auth';
    import { ref, computed } from 'vue';
    import router from '@/router';

    const authStore = useAuthStore();
    const user = ref();
    const users = ref();
    const selection = ref([]);

    try {
        if (authStore.getRole === Roles.Tenant) {
            const response = await axios.get('https://localhost/users/' + authStore.user.id);
            user.value = response.data;
            console.log(response);
        } else if (authStore.getRole === Roles.Agency) {
            const response = await axios.get('https://localhost/users/', {
                params: {
                    roles: Roles.Tenant,
                },
            });
            users.value = response.data;
            console.log(response);
        }
    } catch (error) {
        console.log(error);
    }

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
    const chipColor = computed(() => {
        switch (user.value.validationStatus) {
            case 'validated':
                return 'success';
            case 'to_complete':
                return 'warning';
            case 'to_review':
            default:
                return 'primary';
        }
    });
</script>

<template>
    <div v-if="authStore.getRole === Roles.Tenant">
        <div class="mb-10">
            <h2 class="mb-4 text-h4 font-weight-bold">Mon dossier</h2>
            <v-chip :color="chipColor" text-color="white" class="mb-6">
                {{ $t(`validation_status.${user.validationStatus}`) }}
            </v-chip>
        </div>
        <v-row>
            <v-col cols="12" md="6">
                <v-card>
                    <v-card-title>
                        <h2 class="headline mb-0">Mes informations</h2>
                    </v-card-title>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field v-model="user.firstname" label="Prénom" outlined dense readonly />
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field v-model="user.lastname" label="Nom" outlined dense readonly />
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field v-model="user.email" label="Email" outlined dense readonly />
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field v-model="user.situation" label="Situation" outlined dense readonly />
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="6">
                <v-card>
                    <v-card-title>
                        <h2 class="headline mb-0">Mes documents</h2>
                    </v-card-title>
                    <v-card-text>
                        <v-list lines="three">
                        <v-list-item v-for="item in user.documents" :key="item.name">
                            <v-list-item-title class="text-h7 font-weight-bold">{{ $t(`document_type.${item.type}`) }}</v-list-item-title>
                            <div class="d-flex align-center">
                                <a :href="item.contentUrl" target="_blank" class="mr-3 font-weight-medium">{{ item.name }}</a>
                                <v-icon v-if="item.status === 'validated'" icon="mdi-check-bold"></v-icon>
                                <v-icon v-if="item.status === 'rejected'" icon="mdi-close-thick"></v-icon>
                                <v-icon v-if="item.status === 'to_review'" icon="mdi-account-clock" color="primary"></v-icon>
                                <v-icon tag="a" :href="item.contentUrl" target="_blank" icon="mdi-eye-arrow-right" color="primary" class="ml-3"></v-icon>
                            </div>
                        </v-list-item>
                        </v-list>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </div>
    <div v-if="authStore.getRole === Roles.Agency" class="mx-16 h-75">
        <h1 class="mb-10 text-h4 font-weight-bold">Dossiers locataires</h1>
        <v-chip-group multiple selected-class="text-primary" v-model="selection">
            <v-chip filter class="ma-2" size="x-large" value="to_complete">A compléter</v-chip>
            <v-chip filter class="ma-2" size="x-large" value="to_review">A valider</v-chip>
            <v-chip filter class="ma-2" size="x-large" value="validated">Validés</v-chip>
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
                <tr v-for="user in showedUsers" :key="user.email" @click="router.push({ name: 'agency_show_user', params: {id: user.id}})">
                    <td>{{ user.firstname }}</td>
                    <td>{{ user.lastname }}</td>
                    <td>{{ $t(`work_situation.${user.situation}`) }}</td>
                    <td>{{ user.salary ?? '0' }} €</td>
                    <td>{{ $t(`validation_status.${user.validationStatus}`) }}</td>
                </tr>
            </tbody>
        </v-table>
    </div>
</template>

<style scoped>
    tbody tr:hover {
        background-color: rgb(234, 237, 238);
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