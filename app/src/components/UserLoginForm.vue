<template>

  <input type="text" name="email" placeholder="Adresse email professionnelle" v-model="user.email">
  <input type="password" name="password" placeholder="Mot de pass" v-model="user.password">
  <button @click="login">Identifiez-vous</button>

</template>

<script setup lang="ts">
  import axios from 'axios';
  import {useRouter} from "vue-router";
  const router = useRouter();
  interface User {
    email: string,
    password:
        string,
  }

  let user: User = {
    email: '',
    password:''
  };

  function login(event: MouseEvent) {
    axios.post(`${import.meta.env.VITE_BASE_API_URL}/api/auth`,user)
        .then((response) => {
          if (response.data.token) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
            router.push('/userDashboard')
          } else {
            console.log(response.data)
          }
        })
        .catch((error) => {
          console.log('Err: ' + error)
        })
  }
</script>

<style scoped lang="scss">
</style>
