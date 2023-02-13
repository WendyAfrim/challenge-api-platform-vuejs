<template>
    <v-container>
        <v-alert v-if="message.text" class="mb-10 text-white" :color="message.type">
          {{ message.text }}
        </v-alert>

        <h1>Veuillez proposer 3 créneaux au profil sélectionné</h1>

        <Datepicker v-model="dates" multi-dates multi-dates-limit="3" ></Datepicker>
        <v-btn @click="loadVisits" color="primary" >Enregistrer</v-btn>
    </v-container>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter, useRoute } from "vue-router";
import { postAvailaibility } from '@/services/availaibility';
import { sendAvailaibilityToLodger } from '@/services/availaibility';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

const router = useRouter();
let route = useRoute();
let lodgerId = route.params.id;
let propertyId= route.params.propertyId;

let availaibilities: any[] = [];

const errorType = ref('');
const message = ref({
    text: '',
    type: ''
})

const dates = ref();

function getAllAvailaibilities(): string[] {
    for (const date of dates.value) {
        let slot: object = {
            slot: date,
            property: propertyId,
            lodger: lodgerId
        };

        availaibilities.push(slot)
    }

   return availaibilities;
    
}

async function loadVisits() {

        
    const data : string[] = getAllAvailaibilities();

    await postAvailaibility(data)
    .then(async (response) => {

        message.value.text = response.message;

        console.log(response);
        
        if(406 === response.status) {
            message.value.type = 'warning';
        } else {
            await sendAvailaibilityToLodger(propertyId, lodgerId);
            message.value.type = 'info';
            await router.push({ name: 'homeowner_property_requests', params: {'id' : propertyId} })
        }

    })
    .catch((error) => {
        errorType.value = error.response.data.error_type || '';
        message.value.text = error.response.data.message || 'Une erreur est survenue. Veuillez réessayer.';
        message.value.type = 'error';

    });
}
</script>

<style></style>
