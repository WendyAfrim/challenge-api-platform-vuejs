<template>
    <v-container v-if="'homeowner' === forType">
      <v-row align="center" justify="space-between" no-gutters>
        <v-col cols="12" md="6">
          <h1 class="text-h4 font-weight-bold heading-sentence">Tous <span>mes biens</span></h1>
        </v-col>
        <v-col cols="12" md="6" class="text-right">
          <router-link :to="{ name: `homeowner_property_add` }">
            <v-btn rounded="pill" color="primary">Ajouter un bien</v-btn>
          </router-link>
        </v-col>
      </v-row>
      <v-table v-if="properties">
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
              <td>{{ property.state }} </td>

              <td>
                <router-link :to="{ name: `homeowner_property_requests`, params: { id: property.id } }">
                  <v-btn>Voir les demandes</v-btn>
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
  
  import { useRoute, useRouter } from "vue-router";
  import { axios } from '@/services/auth';
  import type { Roles } from "@/enums/roles";
  import type { Property } from '@/interfaces/Property';
   

  const router = useRouter();
  const route = useRoute();
  const forType = route.meta.forType as Roles;

  let properties: Property[];


  try {
    const response = await axios.get(`${import.meta.env.VITE_BASE_API_URL}/user/details`);
    properties = response.data.properties;
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
