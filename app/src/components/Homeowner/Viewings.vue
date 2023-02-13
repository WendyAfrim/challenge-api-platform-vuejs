<template>
    <v-container>
        <h1>Mes visites</h1>
        <v-alert v-if="message.text" class="mb-10 text-white" :color="message.type">
          {{ message.text }}
        </v-alert>

        <v-table v-if="viewings">
            <template v-slot:default>
                <thead>
                    <tr>
                        <th>Nom du bien</th>
                        <th>Nom du locataire</th>
                        <th>Agent immobilier</th>
                        <th>Date de la visite</th>
                        <th>Etat</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody v-for="viewing in viewings">
                    <tr>
                        <td>{{ viewing.availaibility.property.title }}</td>
                        <td>{{ viewing.lodger.firstname }} {{ viewing.lodger.lastname }}</td>
                        <td>{{ viewing.agent.firstname }} {{ viewing.agent.lastname }}</td>
                        <td>{{ viewing.availaibility.slot ?? 'Non défini' }}</td>
                    </tr>
                </tbody>
            </template>
        </v-table>

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
        viewings = response;
        console.log(viewings);
        
    })
    .catch((error) => {
        errorType.value = error.response.data.error_type || '';
        message.value.text = error.response.status === 404 ? 'Aucune visite n\'est programmée pour le moment' : 'Une erreur est survenue. Veuillez réessayer.';
        message.value.type = error.response.status === 404 ? 'info' : 'error';
    })
</script>

<style></style>
