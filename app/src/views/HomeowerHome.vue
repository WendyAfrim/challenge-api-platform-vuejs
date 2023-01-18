<script setup lang="ts">
import Navbar from '@/components/Navbar.vue';
import Hero from '@/components/Hero.vue';
import Layout from '@/components/Layout.vue';
</script>

<template>
    <Layout>
        <template v-slot>
            <Navbar for="homeowner" />
            <Hero for="homeowner" />
        </template>
    </Layout>
</template>

<style scoped lang="scss">

</style>