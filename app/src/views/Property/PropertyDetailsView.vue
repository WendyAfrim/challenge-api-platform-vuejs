<script setup lang="ts">
import PropertyDetails from "@/components/PropertyDetails.vue";
import {useRoute} from "vue-router";

const route = useRoute();
const propertyId = route.params.id as string;

</script>
    
<template>
  <PropertyDetails :id="propertyId"></PropertyDetails>
</template>


<style scoped>

</style>