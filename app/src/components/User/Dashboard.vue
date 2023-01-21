<template>
    <div v-if="hasRoleOwner()">
        <Navbar for="homeowner" location="dashboard"></Navbar>

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
        <v-btn color="primary" @click="logout">Deconnexion</v-btn>
    </div>

</template>
  
  <script setup lang="ts">
  
  import {onMounted, reactive} from "vue";
  import axios from "@/interceptors/axios";
  import {useRouter} from "vue-router";
  import Navbar from "../Layouts/Navbar.vue";
  
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

  const hasRoleOwner = () => {
    return user.roles.includes('ROLE_OWNER');
  }

  const hasRoleLodger = () => {
    return user.roles.includes('ROLE_LODGER');
  }

  </script>
  
  <style scoped lang="scss">
  
  </style>