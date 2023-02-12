<script setup lang="ts">
import Layout from '@/components/Layout.vue';
import PropertyDetails from "@/components/PropertyDetails.vue";
import {useRoute} from "vue-router";

const route = useRoute();
const propertyId = route.params.id as string;

</script>
    
<template>
  <Layout>
    <template v-slot>
      <PropertyDetails :id="propertyId"></PropertyDetails>
    </template>
  </Layout>
</template>


<style scoped>

</style>