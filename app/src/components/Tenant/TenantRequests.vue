<template>
    <v-container class="d-flex justify-center">
        <v-row align="center" no-gutters>
            <v-col cols="12" md="12">
                <h1 class="text-h4 font-weight-bold mb-6 heading-sentence">Mes <span>demandes</span></h1>
                <v-chip-group multiple selected-class="text-primary" v-model="selection">
                        <v-chip filter class="ma-2" size="x-large" value="pending" color="blue">En attente</v-chip>
                        <v-chip filter class="ma-2" size="x-large" value="accepted" color="blue">Proposition de visite</v-chip>
                        <v-chip filter class="ma-2" size="x-large" value="refused" color="red">Refusées</v-chip>
                </v-chip-group>
                <v-table>
                    <thead>
                        <tr class="">
                            <th class="text-left font-weight-bold">Nom du bien</th>
                            <th class="text-left">Statut</th>
                            <th class="text-left">Date de la demande</th>
                            <th class="text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="request in showRequests" :key="request.id">
                            <td>{{ request.property.title }}</td>
                            <td>{{ getRequestState(request.state)  }}</td>
                            <td>{{ request.created_at }}</td>
                            <td>
                                <router-link v-if="request.state === RequestEnum.Accepted" :to="{name: 'tenant_request_slots', params:{'id' : request.id }}">
                                    <v-btn>Voir les créneaux</v-btn>
                                </router-link>
                                <span v-else>Aucune action disponible</span>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup lang="ts">
    import { ref, computed } from 'vue';
    import { getRequestsByLodger } from '@/services/tenant/requests';
    import { useAuthStore } from '@/stores/auth.store';
    import { RequestEnum } from '@/enums/RequestEnum';

    const selection = ref([]);
    const requests = ref();

    const authStore = useAuthStore();

    const user = authStore.user;

    await getRequestsByLodger(user.id)
        .then((response) => {
            requests.value = response;

        }).catch((error) => {
            console.log(error);
        });

    const showRequests = computed(() => {
        if (requests.value[0] == 404) {
        return false;
        }
        if (!selection.value.length) {
            return requests.value;
        }

        console.log(requests);
        
        let showed: any[] = [];
        selection.value.forEach((status: string) => {
            console.log(status);
            
            showed = showed.concat(requests.value.filter((request: any) => request.state === status));
        });

        return showed;
    })

    function getRequestState(state: string): string {

        switch(state){
            case RequestEnum.Accepted:
                state = 'Accepté'
                break;

            case RequestEnum.Refused:
                state = 'Refusé'
                break;

            case RequestEnum.Pending:
                state = 'En attente'
                break;

            case RequestEnum.Assignment:
                state = 'En attente d\'assignation'
                break;
        } 

        return state;
    }

</script>

<style scoped>
</style>