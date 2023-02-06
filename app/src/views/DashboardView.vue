<script setup lang="ts">
import type { Roles } from '@/enums/roles';
import { useAuthStore } from '@/stores/auth.store';
import { useRoute } from 'vue-router';

const route = useRoute();
const forType = route.meta.forType as Roles;
const authStore = useAuthStore();
const user = authStore.user;
</script>

<template>
    dashboard {{ forType }}
</template>

<style scoped lang="scss">

</style>