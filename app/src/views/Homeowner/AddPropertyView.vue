<script setup lang="ts">
import Layout from '@/components/Layout.vue';
import PropertyForm from '@/components/Homeowner/AddPropertyForm.vue';
</script>
    
<template>
  <Layout>
    <template v-slot>
      <PropertyForm role="property" />
    </template>
  </Layout>
</template>


<style scoped>

</style>