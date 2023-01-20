<template>
  <v-simple-table>
    <template v-slot:default>
      <thead>
        <tr>
          <th class="text-left">
            Date de partage
          </th>
          <th class="text-left">
            Référence
          </th>
          <th class="text-left">
            Candidat
          </th>
          <th class="text-left">
            Situation
          </th>
          <th class="text-left">
            Statut
          </th>
          <th class="text-left">
            Actions
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="folder in folders"
          :key="folder.name"
        >
          <td>{{ folder.created_at }}</td>
          <td>{{ folder.calories }}</td>
          <td>{{ folder.lodger.firstname }} {{ folder.lodger.lastname }}</td>
          <td>{{ folder.lodger.situation }}</td>
        </tr>
      </tbody>
    </template>
  </v-simple-table>
</template>

<script setup lang="ts">

import axios from 'axios';
  
  let folders = [];

  axios.get(`${import.meta.env.VITE_BASE_API_URL}/requests`)
      .then((response) => {
        folders = response.data['hydra:member'];
          console.log(folders);
      })
      .catch((error) => {
          console.log(error);
      });
</script>
<style></style>