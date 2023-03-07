<template>
    <v-container v-if="Roles.Homeowner === forType">
      <v-row align="center" justify="space-between" no-gutters>
        <v-col cols="12" md="6">
          <h1 class="text-h4 font-weight-bold heading-sentence">Tous <span>mes biens</span></h1>
        </v-col>
        <v-col cols="12" md="6" class="text-right">
          <router-link :to="{ name: `${Roles.Homeowner}_property_add` }">
            <v-btn rounded="pill" color="primary">Ajouter un bien</v-btn>
          </router-link>
        </v-col>
      </v-row>
      <v-table v-if="properties?.length !== 0">
        <template v-slot:default>
          <thead>
            <tr>
              <th class="text-left"> Nom du bien </th>
              <th class="text-left"> Adresse</th>
              <th class="text-left"> Loyer </th>
              <th class="text-left"> État </th>
              <th class="text-left"> Actions </th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="property in properties"
              :key="property.title"
            >
              <td>{{ property.title }}</td>
              <td>{{ property.address }} - {{ property.zipcode }} </td>
              <td>{{ property.price }}.00€ </td>
              <td>
                <v-chip v-if="property.state === 'availaible'" color="success">Libre</v-chip>
                <v-chip v-else-if="property.state === 'locked'" color="info">Créneau proposé</v-chip>
                <v-chip v-else-if="property.state === 'assignment'" color="primary">En attente d'attribution</v-chip>
                <v-chip v-else-if="property.state === 'viewing'" color="warning">Visite</v-chip>
                <v-chip v-else color="red">Loué</v-chip>
              </td>

              <td>
                <router-link :to="{ name: `${Roles.Homeowner}_property_requests`, params: { id: property.id } }">
                  <v-btn v-if="property.state === PropertyEnum.Availaible" color="primary" variant="tonal">Voir les demandes</v-btn>
                  <v-btn v-else >Details</v-btn>
                </router-link>
              </td>
            </tr>
          </tbody>
        </template>
      </v-table> 
      <v-alert v-else dense type="info" >
        Aucun n'est enregistré pour le moment. Ne tardez plus !:)
      </v-alert>
    </v-container>
  </template>
  
  <script setup lang="ts">
  
  import { ref } from "vue";
  import { useRoute, useRouter } from "vue-router";
  import { axios } from '@/services/auth';
  import { Roles } from "@/enums/roles";
  import type { Property } from '@/interfaces/Property';
  import  { PropertyEnum } from '@/enums/PropertyEnum';


  const router = useRouter();
  const route = useRoute();
  const forType = route.meta.forType as Roles;

  let properties = ref<Property[]>();


  try {
    const response = await axios.get(`${import.meta.env.VITE_BASE_API_URL}/user/details`);
    console.log(response);

    properties.value = response.data.properties;
  }
  catch(e){
    console.log(e);
    await router.push('/login');
  }
  
</script>
  
<style scoped lang="scss">
  th {
      width: 20em;
      padding: 1em 0;
  }

</style>
