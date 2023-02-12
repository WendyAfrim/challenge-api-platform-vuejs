<script setup lang="ts">
    import FileInput from "@/components/FileInput.vue";
    import {useAuthStore} from "@/stores/auth.store";
    import { useRoute, useRouter } from "vue-router";
    import type { Roles } from "@/enums/roles";

    const authStore = useAuthStore();
    const router = useRouter();
    const route = useRoute();

    const propertyId = route.params.id as string;
  
    function onClick(){
      router.push({ name: `homeowner_dashboard`});
    }

</script>

<template>
  <h1 class="text-h4 font-weight-bold text-center mb-3">Formulaire - Ajout des photos de votre bien </h1>
  <div class="upload-area">
    <FileInput :propertyId="propertyId"></FileInput>
    <FileInput :propertyId="propertyId"></FileInput>
    <FileInput :propertyId="propertyId"></FileInput>
    <v-btn color="primary" variant="tonal" @click="onClick">
      Terminé
    </v-btn>
  </div>

</template>

<style scoped>
.upload-area{
  display: grid;
  grid-gap: 70px;
}

</style>