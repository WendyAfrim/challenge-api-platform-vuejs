<script setup lang="ts">
import {axios} from "@/services/auth";
import {ref, defineProps, computed, onMounted} from "vue";
import {useRoute, useRouter} from "vue-router";
import {Roles} from "@/enums/roles";

const props = defineProps(
    {
      property : Object
    });

const router = useRouter();
const route = useRoute();
const forType = route.meta.forType as Roles;
const errorType = ref('');
const message = ref({
  text:'',
  type:''
});
const imagePath = ref("");

async function onClick() {
    try{
      const response = await axios.get(`${import.meta.env.VITE_BASE_API_URL}/properties/${props.property['@id'].split('/').pop()}`);
      // router.push()
    } catch (error: any) {
      console.log("err: ", error)
      message.value.text = '';
      message.value.type = '';
      message.value.text = error.response.data.message || 'Une erreur est survenue. Veuillez réessayer.';
      message.value.type = 'error';
  }
}

onMounted(async () => {
  if(props.property['photos']['0'] !== undefined){
    try {
      const response = await axios.get(`${import.meta.env.VITE_BASE_API_URL}/media_objects/${props.property['photos']['0'].split('/').pop()}`);
      imagePath.value = response.data.filePath;
      // router.push()
    } catch (error: any) {
      console.log("err: ", error)
      message.value.text = '';
      message.value.type = '';
      message.value.text = error.response.data.message || 'Une erreur est survenue. Veuillez réessayer.';
      message.value.type = 'error';
    }
  }
})


const getImage = computed( () => {
  return imagePath.value;
});

</script>

<template>

  <v-card id="card" @click="onClick"
    >
  <v-alert v-if="message.text" class="text-white" :color="message.type">
    {{ message.text }}
  </v-alert>
      <v-img

          class="align-end text-white"
          v-bind:src="getImage"
          cover
      >
      </v-img>
      <v-card-title>
          <span class="float-start">{{props.property.price}} €</span>
          <span class="float-end">{{props.property.type}}</span>
      </v-card-title>
      <v-card-text class="pb-0">
        <div style="height: 96px">
          {{props.property.title}}
          <div>{{props.property.rooms}} pièces | {{props.property.surface}} m2
            <span v-if="props.property.has_elevator"> | Ascenseur</span>
            <span v-if="props.property.has_balcony"> | Balcon</span>
            <span v-if="props.property.has_parking"> | Parking</span>
            <span v-if="props.property.has_terrace"> | Terrasse</span>
          </div>
          <div>{{props.property.address}} ({{props.property.zipcode}})</div>

        </div>
      </v-card-text>

      <v-card-actions class="pb-0 pt-0">
        <v-btn color="orange" height="9">
          reserve
        </v-btn>

        <v-btn color="orange" height="9">
          Explore
        </v-btn>
      </v-card-actions>
    </v-card>

</template>

<style scoped>

</style>