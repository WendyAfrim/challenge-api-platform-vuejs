<template>
    <v-container>
        <h1 class="mb-10 text-h4 font-weight-bold">Mes demandes</h1>
        <v-card>
            <v-card-text>
                <v-container>
                    <v-row>
                        <v-col cols="4" v-for="request in requests" >
                            <v-card :title="request.lodger.firstname" :subtitle="request.property.title">
                                <ul>
                                    <li>Situation : {{ request.lodger.situation }}</li>
                                    <li>Revenu : {{ request.lodger.salary }}€</li>
                                </ul>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>
        </v-card>
    </v-container>

</template>

<script setup lang="ts">

import { ref } from 'vue';
import { getRequestsByOwner } from '@/services/homeowner/requests';
import { useAuthStore } from '@/stores/auth.store';
import type { PropertyRequest } from '@/interfaces/PropertyRequest';
import type { Property } from '@/interfaces/Property';


const requests = ref<PropertyRequest[]>();
const property = ref<Property>();
console.log(requests);

const authStore = useAuthStore();

const user = authStore.user;

const message = ref({
    text: '',
    type: ''
});

    
   await getRequestsByOwner(user.id)
    .then((response) => {
        if(response.status === 404) {
            message.value.text = response.message;
            message.value.type = 'info';
        }

        requests.value = response;
    })
    .catch((error) => {
        message.value.text = 'Une erreur est survenue. Veuillez réessayer.';
        message.value.type = 'error';
    })

</script>

<style>
    ul {
        padding: 1em;
    }

    li {
        list-style: none;
    }

</style>
