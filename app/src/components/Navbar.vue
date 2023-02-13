<script setup lang="ts">
import type { Roles } from '@/enums/roles';
import { useAuthStore } from '@/stores/auth.store';
import { defineProps, computed } from 'vue';
import { useRoute } from 'vue-router';

const props = defineProps<{
    for?: Roles;
}>()
const authStore = useAuthStore();
const role = authStore.getRole;
const user = authStore.user;
const route = useRoute();
const currentRouteName = computed(() => route.name);

</script>
<template>
    <v-app-bar color="transparent" elevation="0" class="sticky">
        <template v-if="role && role === 'homeowner'">
            <router-link :to="{ name: `${role}_dashboard` }">
                <v-app-bar-title class="font-weight-bold ml-16" text="Easyhome" />
            </router-link>
            <v-container class="d-flex justify-end">
                    <router-link :to="{ name: `${role}_dashboard` }">
                        <v-btn color="primary" variant="outlined">Dashboard</v-btn>
                    </router-link>
                    <router-link :to="{ name: `${role}_viewings`, params: {'ownerId' : user.id} }">
                        <v-btn class="ml-4" color="primary" variant="outlined">Mes visites</v-btn>
                    </router-link>
                    <router-link :to="{ name: 'logout' }">
                        <v-btn color="primary" class="ml-3">Déconnexion</v-btn>
                    </router-link>
            </v-container>
        </template>

        <template v-else-if="role && role === 'tenant'">
            <router-link :to="{ name: `tenant_dashboard` }">
                <v-app-bar-title class="font-weight-bold ml-16" text="Easyhome" />
            </router-link>
            <v-container class="d-flex justify-end">
                    <router-link :to="{ name: `tenant_dashboard` }">
                        <v-btn color="primary" :variant="currentRouteName === `tenant_dashboard` ? 'flat' : 'tonal'">Dashboard</v-btn>
                    </router-link>
                    <router-link :to="{ name: `tenant_properties` }">
                        <v-btn class="ml-4" color="primary" :variant="currentRouteName === `tenant_properties` ? 'flat' : 'tonal'">Nos biens</v-btn>
                    </router-link>
                    <router-link :to="{ name: `tenant_requests` }">
                        <v-btn class="ml-4" color="primary" :variant="currentRouteName === `tenant_requests` ? 'flat' : 'tonal'">Mes demandes</v-btn>
                    </router-link>
                    <router-link :to="{ name: 'logout' }">
                        <v-btn color="primary" class="ml-3">Déconnexion</v-btn>
                    </router-link>
            </v-container>
        </template>

        <template v-else-if="role && role === 'agency'">
            <router-link :to="{ name: `${role}_dashboard` }">
                <v-app-bar-title class="font-weight-bold ml-16" text="Easyhome" />
            </router-link>
            <v-container class="d-flex justify-end">
                <router-link :to="{ name: `${role}_dashboard` }">
                    <v-btn color="primary" :variant="currentRouteName === `${role}_dashboard` ? 'flat' : 'tonal'" class="ml-3">Dossiers locataires</v-btn>
                </router-link>
                <router-link :to="{ name: `${role}_viewings` }">
                    <v-btn color="primary" :variant="currentRouteName === `${role}_viewings` ? 'flat' : 'tonal'" class="ml-3">Visites</v-btn>
                </router-link>
                <router-link :to="{ name: `${role}_properties` }">
                    <v-btn color="primary" :variant="currentRouteName === `${role}_properties` ? 'flat' : 'tonal'" class="ml-3">Biens</v-btn>
                </router-link>
                <router-link :to="{ name: 'logout' }">
                    <v-btn color="primary" class="ml-3">Déconnexion</v-btn>
                </router-link>
            </v-container>
        </template>

        <template v-else>
            <template v-if="props.for === 'agency'">
                <router-link :to="{ name: `${props.for}_login` }">
                    <v-app-bar-title class="font-weight-bold ml-16" text="Easyhome" />
                </router-link>
            </template>
            <template v-else>
                <router-link :to="{ name: `${props.for}_home` }">
                    <v-app-bar-title class="font-weight-bold ml-16" text="Easyhome" />
                </router-link>
            </template>
            <v-container class="d-flex justify-end">
                <template v-if="props.for === 'homeowner'">
                    <router-link :to="{ name: 'tenant_home' }">
                        <v-btn color="primary" variant="outlined">Vous êtes locataire ?</v-btn>
                    </router-link>
                    <router-link :to="{ name: 'homeowner_login' }">
                        <v-btn color="primary" class="ml-3">Je me connecte</v-btn>
                    </router-link>
                </template>
                <template v-else-if="props.for === 'tenant'">
                    <router-link :to="{ name: 'homeowner_home' }">
                        <v-btn color="primary" variant="outlined">Vous êtes propriétaire ?</v-btn>
                    </router-link>
                    <router-link :to="{ name: 'tenant_login' }">
                        <v-btn color="primary" class="ml-3">Je me connecte</v-btn>
                    </router-link>
                </template>
                <template v-else>
                    <v-btn color="primary" class="ml-3">Espace admin</v-btn>
                </template>
            </v-container>
        </template>
    </v-app-bar>
</template>

<style scoped lang="scss">
.v-app-bar {
    backdrop-filter: blur(5px);
}
</style>