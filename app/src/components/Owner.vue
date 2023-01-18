<template>

  <input type="text" name="firstname" placeholder="Prénom" v-model="owner.firstname">
  <input type="text" name="lastname" placeholder="Nom" v-model="owner.lastname">
  <input type="text" name="email" placeholder="Adresse email professionnelle" v-model="owner.email">

  <button @click="register">M'inscrire</button>
</template>

<script setup lang="ts">

import axios from 'axios';

interface Owner {
  firstname: string,
  lastname: string,
  email: string
  roles: string
};

let owner: Owner = {
  firstname: '',
  lastname: '',
  email: '',
  roles: ''
};

function register(event: MouseEvent) {

  axios.post('https://localhost/users', {
    firstname: owner.firstname,
    lastname: owner.lastname,
    email: owner.email,
    roles: 'ROLE_OWNER'
  })
    .then((response) => {
      if (response.data.success) {
        console.log(response.data)
      } else {
        console.log(response.data)
      }
    })
    .catch((error) => {
      console.log('Err: ' + error)
    })
}

</script>

<style scoped>

</style>
