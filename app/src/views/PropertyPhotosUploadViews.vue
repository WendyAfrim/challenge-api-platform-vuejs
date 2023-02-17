<script setup lang="ts">
    import FileInput from "@/components/FileInput.vue";
    import {useAuthStore} from "@/stores/auth.store";
    import { useRoute, useRouter } from "vue-router";
    import { Roles } from "@/enums/roles";

    const authStore = useAuthStore();
    const router = useRouter();
    const route = useRoute();

    const propertyId = route.params.id as string;
  
    function onClick(){
      router.push({ name: `${Roles.Homeowner}_dashboard`});
    }

</script>

<template>
  <v-container>
    <v-row class="d-flex justify-center">
      <v-col cols="12" md="6">
        <h1 class="text-h4 font-weight-bold text-center mb-3 heading-sentence">Compléter votre bien en y <span>ajoutant des photos</span></h1>
      </v-col>
    </v-row>
    <v-row class="d-flex justify-center">
      <v-col cols="12" md="6">
        <FileInput :propertyId="propertyId"></FileInput>
        <FileInput :propertyId="propertyId"></FileInput>
        <FileInput :propertyId="propertyId"></FileInput>
      </v-col>
    </v-row>
    <v-row class="d-flex justify-center">
      <v-col cols="12" md="6" class="d-flex justify-center">
        <v-btn color="primary" @click="onClick">J'ai fini !</v-btn>
      </v-col>
    </v-row>
  </v-container>

</template>

<style scoped>
  
</style>