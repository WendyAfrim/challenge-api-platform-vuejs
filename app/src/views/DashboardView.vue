<script setup lang="ts">
import type { Roles } from '@/enums/roles';
import { useAuthStore } from '@/stores/auth.store';
import { useRoute } from 'vue-router';
import PropertiesGrid from "@/components/PropertiesGrid.vue";
import PropertyForm from "@/components/Property/PropertyForm.vue";

const route = useRoute();
const forType = route.meta.forType as Roles;
const authStore = useAuthStore();
const user = authStore.user;
</script>

<template>
  <PropertiesGrid v-if="forType==='tenant'"></PropertiesGrid>
  <div v-else>
    dashboard {{ forType }}
  </div>
</template>

<style scoped lang="scss">

</style>