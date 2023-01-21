<script setup lang="ts">
import Navbar from '@/components/Layouts/Navbar.vue';
import Hero from '@/components/Hero.vue';
import Layout from '@/components/Layout.vue';
</script>

<template>
    <Layout>
        <template v-slot:default>
            <Navbar for="tenant" />
            <Hero for="tenant" />
        </template>
    </Layout>
</template>

<style scoped lang="scss">

</style>