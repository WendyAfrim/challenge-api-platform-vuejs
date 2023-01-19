<script setup lang="ts">
import Layout from '@/components/Layout.vue';
import RegisterForm from '@/components/RegisterForm.vue';
</script>
    
<template>
  <Layout>
    <template v-slot>
      <RegisterForm role="homeowner" />
    </template>
  </Layout>
</template>


<style scoped>

</style>