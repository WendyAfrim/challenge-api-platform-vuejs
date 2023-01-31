<script setup lang="ts">
import { useRoute } from 'vue-router';
import Hero from '@/components/Hero.vue';
import type { Roles } from '@/enums/roles';

const route = useRoute();
const forType = route.meta.forType as Roles;
</script>

<template>
    <Hero :for="forType" />
</template>

<style scoped lang="scss">

</style>