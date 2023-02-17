<template>
     <v-container>
        <h1 class="mb-10 text-h4 font-weight-bold">{{ property.title }}</h1>
        <div v-if="!propertyBusyState.includes(property.state) && requests?.length !== 0">
            <v-table>
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
                            <td>{{ request.lodger.firstname }} </td>
                            <td>{{ request.lodger.situation }}</td>
                            <td>{{ request.lodger.salary }} €</td>
                            <td>{{ request.lodger.email }}</td>
                            <td>
                                <v-chip v-if="request.state === RequestEnum.Accepted" color="success">
                                    Accepté
                                </v-chip>
                                <v-chip v-else-if="request.state === RequestEnum.Refused" color="red">
                                    Refusé
                                </v-chip>
                                <v-chip v-else-if="request.state === RequestEnum.Visit" color="warning">
                                    En visite
                                </v-chip>
                                <v-chip v-else color="info">
                                    Non renseigné
                                </v-chip>
                            </td>
                            <td>
                                <router-link v-if="property.state === 'availaible'" :to="{ name: `homeowner_request_slots_proposals`, params: { id : request.id} }">
                                    <v-btn color="primary">Accepter</v-btn>
                                </router-link>
                                <v-chip  v-else variant="elevated" color="warning">
                                    Aucune action possible
                                </v-chip>
                            </td>
                        </tr>
                    </tbody>
                </template>
            </v-table>
        </div>
        <v-alert v-else-if="requests?.length === 0" color="info">
            Aucune demande n'a été effectué sur ce bien pour le moment
        </v-alert>
        <v-card v-else-if="property.state === PropertyEnum.Locked " :title="property.title" subtitle="Créneaux proposés">
            <div v-for="request in requests">
                <ul v-if="request.state === 'accepted' && request.availaibilities">
                    Futur locataire : {{ request.lodger.firstname }} {{ request.lodger.lastname }} <br/>
                    Email : {{ request.lodger.email }}<br/><br/>
                    <li v-for="availaibility in request.availaibilities">{{ availaibility.slot }}</li>
                    <br/>
                    Dès retour de sa part, vous serez notifié par email.
                </ul>
            </div>
            <router-link :to="{ name:'homeowner_dashboard' }">
                <v-card-actions>
                    <v-spacer></v-spacer>
                <v-btn color="primary" variant="flat"> Retour à l'accueil</v-btn>
                </v-card-actions>
            </router-link>
        </v-card>
        <v-card v-else-if="property.state === PropertyEnum.Assignment" subtitle="En cours d'assignation">
            <div v-for="request in requests">
                <p v-if="request.state === RequestEnum.Assignment">
                    Votre futur locataire : {{ request.lodger.firstname }} {{ request.lodger.lastname }} a accepté un des créneaux proposés. <br/>
                    Votre visite est prévue le : {{ getViewing(request.availaibilities).slot }} <br/><br/>

                    Dès assignation de l'agent par l'agence, vous en serez notifié.
                </p>
            </div>
        </v-card>
        <v-card v-else title="Votre visite">
            <div v-for="request in requests">
                <p v-if="request.state === RequestEnum.Visit">
                    L'agence a affecté l'agent : <strong> {{ getViewing(request.availaibilities).agent }}</strong> pour votre prochaine visite prévue le  <strong>{{ getViewing(request.availaibilities).slot }}</strong>  avec le locataire : 
                    {{ request.lodger.firstname }} {{ request.lodger.lastname }}. <br/>
                </p>
            </div>
            <router-link :to="{ name:'homeowner_dashboard' }">
                <v-card-actions>
                    <v-spacer></v-spacer>
                <v-btn color="primary" variant="flat"> Retour à l'accueil</v-btn>
                </v-card-actions>
            </router-link>
        </v-card>
    </v-container>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRoute } from "vue-router";
import { axios } from '@/services/auth';
import type { Property } from '@/interfaces/Property';
import type { Availaibility } from '@/interfaces/Availaibility';
import type { PropertyRequest } from '@/interfaces/PropertyRequest';
import { RequestEnum } from '@/enums/RequestEnum';
import { PropertyEnum } from '@/enums/PropertyEnum';

let router = useRoute();
let propertyId = router.params.id;


let property: Property;

const viewing = ref({
    slot: '', 
    agent: ''
});
const requests = ref<PropertyRequest[]>();
const propertyBusyState = ['locked', 'viewing', 'assignment'];


try{
    const response = await axios.get(`${import.meta.env.VITE_BASE_API_URL}/properties/${propertyId}`);
    
    property = response.data;
    requests.value =  response.data.requests
    
} catch(e){
    console.log(e)
}

function getViewing(availaibilities: Availaibility[])
{   
    availaibilities.forEach( availaibility => {
        
        if(availaibility.state === 'accepted') {
           viewing.value.agent = availaibility.viewing?.agent.firstname ?? '',
           viewing.value.slot = availaibility.slot 
        }
    });

    return viewing.value;
}

</script>

<style scoped lang="scss">

th {
      width: 20em;
      padding: 1em 0;
}

ul{
    margin-bottom: 2em;
    margin-left: 1em;
}

li {
    margin-left: 2em;
}  

p {
    padding: 2em;
}

.chip {
    padding-left: 1em;
}
</style>
