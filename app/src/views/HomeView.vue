<script setup lang="ts">
import { useRoute } from 'vue-router';
import Hero from '@/components/Hero.vue';

const route = useRoute();
const forType = route.meta.forType as 'homeowner' | 'tenant';
</script>

<template>
    <Hero :for="forType" />
</template>

<style scoped lang="scss">

</style>