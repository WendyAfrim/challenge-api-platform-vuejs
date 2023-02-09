<script setup lang="ts">
import { Roles } from '@/enums/roles';
import { useAuthStore } from '@/stores/auth.store';
import axios from 'axios';
import { useRoute } from 'vue-router';
import Dashboard from '@/components/User/Dashboard.vue';
import PropertiesGrid from "@/components/PropertiesGrid.vue";
import PropertyForm from "@/components/Property/PropertyForm.vue";

const route = useRoute();
const forType = route.meta.forType as Roles;
const authStore = useAuthStore();
const response = await axios.get('https://localhost/users/' + authStore.user.id);
const user = response.data;
</script>

<template>
    <v-card v-if="authStore.getRole === Roles.Tenant">
        <v-card-title>
            <h2 class="headline mb-0">Mon dossier</h2>
        </v-card-title>
        <v-card-text>
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
                                    <v-text-field v-model="user.validationStatus" label="Statut du dossier" outlined dense readonly />
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
        </v-card-text>
    </v-card>
</template>
