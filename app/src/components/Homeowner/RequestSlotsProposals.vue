<template>
    <v-container>
        <v-alert v-if="message.text" class="mb-10 text-white" :color="message.type">
          {{ message.text }}
        </v-alert>

        <div v-if="!request">
            <h1>Veuillez proposer 3 créneaux au profil sélectionné</h1>

            <Datepicker v-model="dates" lang="fr" format="dd/MM/yyyy" multi-dates multi-dates-limit="3" ></Datepicker>
            <v-progress-circular v-if="loading" indeterminate color="primary" class="mt-5"></v-progress-circular>
            <v-btn v-else @click="loadVisits" color="primary" >Enregistrer</v-btn>
        </div>

        <div v-else>
            <h1 class="mb-10">Votre bien : {{ request.property?.title }}</h1>
            <v-card :title="request.property?.title" subtitle="Proposition de créneau">
                <p>
                    Vos disponibilités ont bien été enregistrées ! <br/>
                    Un email à été envoyé au locataire : {{ request.lodger?.firstname}} {{ request.lodger?.lastname }} et toutes les autres demandes sur
                    le bien sont actuellement refusées
                </p>
                <v-card-actions>
                    <router-link :to="{name: `${Roles.Homeowner}_dashboard`}">
                        <v-btn color="primary" variant="tonal" class="ml-2 mb-4 mt-4">Retour à l'accueil</v-btn>
                    </router-link>
                </v-card-actions>
            </v-card>
        </div>
    </v-container>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue';
import { useRoute } from "vue-router";
import { postRequestSlots } from '@/services/homeowner/requests';
import Datepicker from '@vuepic/vue-datepicker';
import type { PropertyRequest } from '@/interfaces/PropertyRequest';
import '@vuepic/vue-datepicker/dist/main.css'
import { Roles } from '@/enums/roles';
import { format } from 'date-fns';

let route = useRoute();
const requestId: any = route.params.id;
const request = ref<PropertyRequest>();
const loading = ref(false);

const dates = ref();
let availaibilities: any = reactive([]);

const errorType = ref('');
const message = ref({
    text: '',
    type: ''
})


function getAllAvailaibilities(): string[] {

    for (const date of dates.value) {
        let slot: object = {
            // slot: date,
            slot: format(new Date(date), 'dd-MM-yyyy h:mm:ss'),
        };

        availaibilities.push(slot)
    }

   return availaibilities;
    
}

async function loadVisits() {

    const slots : string[] = getAllAvailaibilities();
    console.log(slots);
    
    loading.value = true;
    
    await postRequestSlots(requestId, slots)
    .then(async (response) => {

        request.value = response;
        if(406 === response.status) {
            message.value.type = 'warning';
        } 

    })
    .catch((error) => {
        errorType.value = error.response.data.error_type || '';
        message.value.text = error.response.data.message || 'Une erreur est survenue. Veuillez réessayer.';
        message.value.type = 'error';

    });
    loading.value = false;
}
</script>

<style scoped>

    p {
        padding-left: 1em;
    }

</style>
