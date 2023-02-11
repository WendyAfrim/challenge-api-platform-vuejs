<script setup lang="ts">
import {computed , ref} from 'vue';
import Property from "@/components/Property.vue";
import { axios } from '@/services/auth';
import {onMounted} from "vue";

const message = ref({
  text:'',
  type:''
});
const current_page = ref(1);
const last_page = ref(1);
const properties = ref();

const has_next = computed(() => {
  return current_page.value < last_page.value;
});
async function next_page() {
  if(has_next.value){
    await get_page((current_page.value) + 1);
  }
}

const has_precious = computed(()=>{
  return current_page.value > 1;
});
async function previous_page() {
  if(has_precious.value){
    await get_page(current_page.value - 1);
  }
}

async function get_page(pageNumber = 1) {
  axios.defaults.headers.common['Accept'] = 'application/ld+json';
  try {
    const response = await axios.get(`${import.meta.env.VITE_BASE_API_URL}/properties?page=${pageNumber}`);
    if(response.data['hydra:view'] !== undefined){
      current_page.value = parseInt(response.data['hydra:view']['@id'].split('=')[1]);
      last_page.value = parseInt(response.data['hydra:view']['hydra:last'].split('=')[1]);
    }
    properties.value = response.data['hydra:member'];
  } catch (error: any) {
    console.log("err: ", error)
    message.value.text = '';
    message.value.type = '';
    message.value.text = error.response.data.message || 'Une erreur est survenue. Veuillez réessayer.';
    message.value.type = 'error';
  }
  axios.defaults.headers.common['Accept'] = 'application/json';
}

onMounted(async () => {
    await get_page(1);
})

</script>
    
<template>
  <div id="box" >
    <v-alert v-if="message.text" class="text-white" :color="message.type">{{ message.text }}</v-alert>
    <div class="ma-0" v-for="element in properties" :key="element['@id'].split('/').pop()">
    <Property :property="element"></Property>
    </div>
  </div>
  <div class="text-center" v-if="last_page > 1">
    <span v-if="has_precious">
      <v-btn class="ma-1" color="primary" variant="outlined" @click="previous_page">
        <v-icon icon='mdi-chevron-left'></v-icon>
      </v-btn>
    </span>
    <span v-if="has_next">
      <v-btn class="ma-1" color="primary" variant="outlined" @click="next_page">
        <v-icon icon='mdi-chevron-right'></v-icon>
      </v-btn>
    </span>
  </div>
</template>

<style scoped>
#box{
  align-self: start;
  display: grid;
  grid-template-columns: repeat(5, minmax(250px, 1fr));
  grid-gap: 3vmin;
}

</style>