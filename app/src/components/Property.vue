<script setup lang="ts">
import { getFullUrl } from "@/helpers/assets";
import {axios} from "@/services/auth";
import { useAuthStore } from "@/stores/auth.store";
import {ref, defineProps, computed, onMounted} from "vue";
import {useRoute, useRouter} from "vue-router";

const props = defineProps(
    {
      property : Object
    });

const router = useRouter();
const message = ref({
  text:'',
  type:''
});
const imagePath = ref("");
const authStore = useAuthStore();
const user = await authStore.getUser;

async function onClick() {
    try{
      if (undefined !== props.property){
        if(undefined !== props.property['@id']){
          const property_id = props.property['@id'].split('/').pop()
          await router.push({name: `${user.role}_property_details`, params: {id: property_id}})
          }
        }
    } catch (error: any) {
      console.log("err: ", error)
      message.value.text = error || 'Une erreur est survenue. Veuillez réessayer.';
      message.value.type = 'error';
  }
}

onMounted(async () => {
  // default value will never be used (agency will verify the announcement before published)
  imagePath.value = getFullUrl('../assets/no-image.jpg');
  if(undefined !== props.property) {
    const property = props.property
    if (undefined !== property['photos']) {
      const photos_hydra_id_list =  property['photos']
      const default_photo_hydra_id = photos_hydra_id_list['0'];
      if(undefined !== default_photo_hydra_id){
        if(undefined !== default_photo_hydra_id.split('/').pop()){
          imagePath.value = await getPhotoLink(default_photo_hydra_id.split('/').pop());
        }
      }
    }
  }
})

async function getPhotoLink(id:String) {
  try {
    const response = await axios.get(`${import.meta.env.VITE_BASE_API_URL}/media_objects/${id}`);
    const photo_link = await response.data.filePath
    return await photo_link;
  } catch (error: any) {
    console.log("err: ", error)
    message.value.text = '';
    message.value.type = '';
    message.value.text = error.response.data.message || 'Une erreur est survenue. Veuillez réessayer.';
    message.value.type = 'error';
  }
}


const getImage = computed( () => {
  return imagePath.value;
});

</script>

<template>

  <v-card id="card" @click="onClick">
  <v-alert v-if="message.text" class="text-white" :color="message.type">
    {{ message.text }}
  </v-alert>
      <v-img
          class="align-end text-white"
          v-bind:src="getImage"
          height="200px"
          cover>
      </v-img>
      <v-card-title>
          <span class="float-start">{{props.property!.price}} €</span>
          <span class="float-end">{{props.property!.type}}</span>
      </v-card-title>
      <v-card-text class="pb-0">
        <div style="height: 96px">
          {{props.property!.title}}
          <div>{{props.property!.rooms}} pièces | {{props.property?.surface}} m2
            <span v-if="props.property?.has_elevator"> | Ascenseur</span>
            <span v-if="props.property?.has_balcony"> | Balcon</span>
            <span v-if="props.property?.has_parking"> | Parking</span>
            <span v-if="props.property?.has_terrace"> | Terrasse</span>
          </div>
          <div>{{props.property!.address}} ({{props.property?.zipcode}})</div>
        </div>
      </v-card-text>
    </v-card>

</template>

<style scoped>

</style>