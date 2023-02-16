<template>
    <v-container>
        <v-row>
            <v-col cols="12" md="6">
                <h1 class="text-h4 font-weight-bold heading-sentence">Mes <span>visites</span></h1>
            </v-col>
        </v-row>
        <v-row>
            <v-alert v-if="message.text" class="mb-5 text-white" :color="message.type">
              {{ message.text }}
            </v-alert>
        </v-row>

        <v-row>
            <v-table v-if="viewings.length > 0">
                <template v-slot:default>
                    <thead>
                        <tr>
                            <th>Nom du bien</th>
                            <th>Nom du locataire</th>
                            <th>Agent immobilier</th>
                            <th>Date de la visite</th>
                        </tr>
                    </thead>
                    <tbody >
                        <tr v-for="viewing in viewings" :key="viewing.id">
                            <td>{{ viewing?.availaibility.property.title }}</td>
                            <td>{{ viewing?.lodger.firstname }} {{ viewing?.lodger.lastname }}</td>
                            <td>{{ viewing?.agent ? viewing?.agent.firstname : 'Non défini' }}</td>
                            <td>{{ viewing?.availaibility.slot ?? 'Non défini' }}</td>
                        </tr>
                    </tbody>
                </template>
            </v-table>
        </v-row>
    </v-container>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter, useRoute } from "vue-router";
import { getOwnerVisits } from '@/services/homeowner/visits';
import { useAuthStore  } from '@/stores/auth.store';
import type { Viewing } from '@/interfaces/Viewing';

const router = useRouter();
let route = useRoute();
let authStore = useAuthStore();

let user = authStore.user;

const errorType = ref('');
const message = ref({
    text: '',
    type: ''
})

let viewings: Viewing[];


await getOwnerVisits(user.id)
    .then((response) => {
        if(response.status === 404) {
            message.value.text = response.message;
            message.value.type = 'info';
        }
        viewings = response;
        
    })
    .catch((error) => {
        errorType.value = error.response.data.error_type || '';
        message.value.text = 'Une erreur est survenue. Veuillez réessayer.';
        message.value.type = 'error';
    })
</script>

<style></style>
