<template>
     <v-container>
        <h1>{{ property.title }}</h1>

        <v-table v-if="requests.length !== 0 && !ownerHasChooseALodger">
            <template v-slot:default>
                <thead>
                    <tr>
                        <th>Nom du locataire</th>
                        <th>Situation</th>
                        <th>Revenu</th>
                        <th>Email</th>
                        <th>Etat</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody v-for="request in requests">
                    <tr>
                        <td>{{ request.lodger.firstname }} {{ request.lodger.lastname }}</td>
                        <td>{{ request.lodger.situation }}</td>
                        <td>{{ request.lodger.salary }} €</td>
                        <td>{{ request.lodger.email }}</td>
                        <td>{{ request.is_accepted === true ? 'Accepté' : false ? 'Refusé' : 'Non renseigné' }}</td>
                        <td>
                            <router-link :to="{ name: `homeowner_visits_proposals`, params: { id : request.lodger.id, propertyId: property.id} }">
                                <v-btn color="primary">Accepter</v-btn>
                            </router-link>
                        </td>
                    </tr>
                </tbody>
            </template>
        </v-table>
        <v-alert v-else-if="ownerHasChooseALodger">
            Vous avez proposé des créneaux à sur ce bien. Nous attendons le retour du profil sélectionné.
        </v-alert>
        <v-alert v-else dense type="info" >
            Aucune demande n'est enregistré pour le moment sur ce bien !
        </v-alert>
    </v-container>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { useRoute } from "vue-router";
import { axios } from '@/services/auth';
import type { Property } from '@/interfaces/Property';
import type { Request } from '@/interfaces/Request';

let router = useRoute();
let propertyId = router.params.id;


let property: Property;
let requests: Request[];

let ownerHasChooseALodger = computed(() => {

    let requestsState: any[] = [];

    requests.forEach((request) => {
        requestsState.push(request.is_accepted)
    })

    if(requestsState.includes(true)) {
        return true;    
    } else {
        return false;
    }
}) 

try{
    const response = await axios.get(`${import.meta.env.VITE_BASE_API_URL}/properties/${propertyId}`);

    property = response.data;
    requests = property.requests as any;
    
} catch(e){
    console.log(e)
}
</script>

<style scoped lang="scss">

th {
      width: 20em;
      padding: 1em 0;
  }
</style>
