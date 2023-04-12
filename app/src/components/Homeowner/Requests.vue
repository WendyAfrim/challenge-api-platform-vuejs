<template>
    <v-container>
        <v-card v-if="requests?.length">
            <v-card-text>
                <v-container>
                    <v-row>
                        <v-col cols="4" v-for="request in requests" :key="request.id">
                            <v-card :title="request.lodger?.firstname" :subtitle="request.property?.title">
                                <ul>
                                    <li>Situation : {{ request.lodger?.situation }}</li>
                                    <li>Revenu : {{ request.lodger?.salary }}€</li>
                                </ul>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>
        </v-card>
        <v-alert v-if="message.text" class="mb-5 text-white" :type="message.type">
            {{ message.text }}
        </v-alert>
    </v-container>

</template>

<script setup lang="ts">

import { ref } from 'vue';
import { getRequestsByOwner } from '@/services/homeowner/requests';
import { useAuthStore } from '@/stores/auth.store';
import type { PropertyRequest } from '@/interfaces/PropertyRequest';

const requests = ref<PropertyRequest[]>();

const authStore = useAuthStore();

const user = await authStore.getUser;

const message = ref({ text: '', type: undefined as "error" | "success" | "warning" | "info" | undefined });

try {
    const response = await getRequestsByOwner(user.id);
    if(response.status === 404) {
        message.value = { text: response.message, type: 'info' }
    }
    requests.value = response;
} catch (error: any) {
    message.value = { text: error.message, type: 'error' }
}

</script>

<style>
    ul {
        padding: 1em;
    }

    li {
        list-style: none;
    }

</style>
