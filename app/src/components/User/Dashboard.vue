<template>
    <v-list>
      <v-list-item :title="user.firstname"></v-list-item>
      <v-list-item :title="user.lastname"></v-list-item>
      <v-list-item :title="user.email"></v-list-item>
      <v-list-item
          v-for="(role, i) in user.roles"
          :key="i"
          :title="role"
      ></v-list-item>
    </v-list>
    <v-btn @click="logout" color="primary">Logout</v-btn>
  </template>
  
  <script setup lang="ts">
  
  import {onMounted, reactive} from "vue";
  import axios from "@/interceptors/axios";
  import {useRouter} from "vue-router";
  
  const router = useRouter();
  interface User {
    email: string,
    firstname: string,
    lastname: string,
    roles : []
  }
  const user : User = reactive({
    email: '',
    firstname: '',
    lastname: '',
    roles : []
  });
  onMounted( async () => {
    const response = await axios.get('/user/details');
    try{
      console.log("user:" ,response);
      user.email = response.data.email;
      user.firstname = response.data.firstname;
      user.lastname = response.data.lastname;
      user.roles = response.data.roles;
    }
    catch(e){
      await router.push('/login');
    }
  });
  const logout = async () => {
    await axios.post('/logout', {});
    axios.defaults.headers.common['Authorization'] = '';
    await router.push('/login');
  }
  </script>
  
  <style scoped lang="scss">
  
  </style>