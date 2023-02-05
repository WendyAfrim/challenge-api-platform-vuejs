<script setup lang="ts">
import Layout from '@/components/Layout.vue';
import Dashboard from "@/views/DashboardView.vue";
</script>
    
<template>
  <Layout>
    <template v-slot>
      <Dashboard />
    </template>
  </Layout>
</template>


<style scoped>

</style>