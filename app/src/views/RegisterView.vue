<script setup lang="ts">
import RegisterForm from '@/components/RegisterForm.vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const forType = route.meta.forType as 'homeowner' | 'tenant';
</script>
    
<template>
  <RegisterForm :for="forType" />
</template>


<style scoped>

</style>