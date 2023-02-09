<script setup lang="ts">
    import FileInput from "@/components/FileInput.vue";
    import {useAuthStore} from "@/stores/auth.store";

    const authStore = useAuthStore();
    const property_id = authStore.getPropertyId();
    import { useRoute, useRouter } from "vue-router";
    import type { Roles } from "@/enums/roles";

    const router = useRouter();
    const route = useRoute();
    const forType = route.meta.forType as Roles;

    function onClick(){
      authStore.setPropertyId("");
      // router.push({ name: 'homeowner_property_add_photos'});
      router.push({ name: `${forType}_dashboard`});
    }

</script>

<template>
  <h1 class="text-h4 font-weight-bold text-center mb-3">Formulaire - Ajout des photos de votre bien </h1>
  <div class="upload-area">
    <FileInput :propertyId="property_id"></FileInput>
    <FileInput :propertyId="property_id"></FileInput>
    <FileInput :propertyId="property_id"></FileInput>
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