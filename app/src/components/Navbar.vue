<script setup lang="ts">
import type { Roles } from '@/enums/roles';
import { useAuthStore } from '@/stores/auth.store';
import { defineProps } from 'vue';

const props = defineProps<{
    for?: Roles;
}>()
const authStore = useAuthStore();
const role = authStore.getRole;
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
                    <!-- <router-link :to="{ name: `${role}_requests` }">
                        <v-btn color="primary" variant="outlined">Mes demandes</v-btn>
                    </router-link> -->
                    <router-link :to="{ name: 'logout' }">
                        <v-btn color="primary" class="ml-3">Déconnexion</v-btn>
                    </router-link>
            </v-container>
        </template>

        <template v-else-if="role && role === 'tenant'">
            <router-link :to="{ name: `${role}_dashboard` }">
                <v-app-bar-title class="font-weight-bold ml-16" text="Easyhome" />
            </router-link>
            <v-container class="d-flex justify-end">
                    <router-link :to="{ name: `${role}_dashboard` }">
                        <v-btn color="primary" variant="outlined">Dashboard</v-btn>
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
                    <router-link :to="{ name: 'homeowner_home' }">
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