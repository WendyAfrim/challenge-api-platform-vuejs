<script setup lang="ts">
import { useRoute } from 'vue-router';
import Layout from '@/components/Layout.vue';
import Navbar from '@/components/Navbar.vue';

const route = useRoute();
const forType = route.meta.forType as 'homeowner' | 'tenant';
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