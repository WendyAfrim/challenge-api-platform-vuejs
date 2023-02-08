<script setup lang="ts">
import type { Roles } from '@/enums/roles';
import { useAuthStore } from '@/stores/auth.store';
import { useRoute } from 'vue-router';
import PropertiesGrid from "@/components/PropertiesGrid.vue";

const route = useRoute();
const forType = route.meta.forType as Roles;
const authStore = useAuthStore();
const user = authStore.user;
</script>

<template>
    dashboard {{ forType }}
  <PropertiesGrid v-if="forType==='tenant'"></PropertiesGrid>
</template>

<style scoped lang="scss">

</style>