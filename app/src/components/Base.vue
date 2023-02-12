<script setup lang="ts">
import { useRoute } from 'vue-router';
import Layout from '@/components/Layout.vue';
import Navbar from '@/components/Navbar.vue';
import type { Roles } from '@/enums/roles';

const route = useRoute();
const forType = route.meta.forType as Roles;
const location = route.meta.location as 'dashboard' | 'front';
</script>

<template>
    <Layout>
        <template v-slot:header>
            <Navbar :for="forType" />
        </template>
        <template v-slot:default>
            <router-view></router-view>
        </template>
    </Layout>
</template>

<style scoped lang="scss">

</style>