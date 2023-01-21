<script setup lang="ts">
import type { Roles } from '@/enums/roles';
import { useAuthStore } from '@/stores/auth.store';
import { useRoute } from 'vue-router';
import Dashboard from '@/components/User/Dashboard.vue';
import PropertiesGrid from "@/components/PropertiesGrid.vue";
import PropertyForm from "@/components/Property/PropertyForm.vue";

const route = useRoute();
const forType = route.meta.forType as Roles;
const authStore = useAuthStore();
const user = authStore.user;
</script>

<template>
    <Dashboard/>
</template>

<style scoped lang="scss">

</style>
