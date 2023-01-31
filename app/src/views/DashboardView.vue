<script setup lang="ts">
import type { Roles } from '@/enums/roles';
import { useRoute } from 'vue-router';

const route = useRoute();
const forType = route.meta.forType as Roles;
</script>

<template>
    dashboard {{ forType }}
</template>

<style scoped lang="scss">

</style>