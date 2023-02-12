<script setup lang="ts">

import {ref} from "vue";
import {axios} from "@/services/auth";


const props = defineProps({
      id:String,
    });

const message = ref({
  text:'',
  type:''
});


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

async function getMyProperty(id:any) {
  try {

    const response = await axios.get(`${import.meta.env.VITE_BASE_API_URL}/properties/${props.id}`);
    const photos_links = [];
    for (const photo_id of response.data.photos) {
      const link  = await getPhotoLink(photo_id.split('/').pop())
      photos_links.push(link)
    }
      response.data.photos = photos_links;
    return response.data;

  } catch (error: any) {
    console.log("err: ", error)
    message.value.text = '';
    message.value.type = '';
    message.value.text = error.response.data.message || 'Une erreur est survenue. Veuillez réessayer.';
    message.value.type = 'error';
  }
}
const property = await getMyProperty(props.id);

</script>

<template>
  <div id="container">
    <div>
      <v-carousel id="images_slider">
        <v-carousel-item
            v-for="(link,i) in property.photos"
            :key="i"
            :src="link"
            cover
        ></v-carousel-item>
      </v-carousel>
    </div>
    <v-card class="h-100">
      <div id="details-area" class=" w-100 row">
        <div>
          <v-list>
              <v-list-item class="text-h4">
                  <v-icon icon="mdi mdi-currency-eur"></v-icon>
                  {{property.price}}
              </v-list-item>
            <v-list-item class="text-h5">
              <span class="justify-end" v-if="property.type === 'House'"><v-icon icon="mdi mdi-home-outline"></v-icon></span>
              <span class="justify-end" v-if="property.type === 'Apartment'"><v-icon icon="mdi mdi-office-building-outline"></v-icon></span>
              <span class="justify-end">{{property.type}}</span>
            </v-list-item>
            <v-list-item-title class="text-h5 ml-8">
              {{property.title}}
            </v-list-item-title>
            <v-list-item class="text-h7">{{property.rooms}} pièces<v-icon icon="mdi mdi-floor-plan"></v-icon></v-list-item>
            <v-list-item class="text-h7">{{property.surface}} &#13217; <v-icon icon="mdi mdi-tape-measure"></v-icon></v-list-item>
            <v-list-item class="text-h7" v-if="property.state"> {{property.state}}<v-icon icon="mdi mdi-door-closed-lock"></v-icon></v-list-item>
            <v-list-item class="text-h7" v-if="property.has_elevator">Ascenseur <v-icon icon="mdi mdi-elevator"></v-icon></v-list-item>
              <v-list-item class="text-h7" v-if="property.has_balcony">Balcon <v-icon icon="mdi mdi-balcony"></v-icon></v-list-item>
              <v-list-item class="text-h7" v-if="property.has_parking">Parking <v-icon icon="mdi mdi-garage"></v-icon></v-list-item>
              <v-list-item class="text-h7" v-if="property.has_terrace">Terrasse <v-icon icon="mdi mdi-fence"></v-icon></v-list-item>
            <v-list-item class="text-h7" v-if="property.is_furnished">Meublé <v-icon icon="mdi mdi-sofa-single-outline"></v-icon></v-list-item>
            <v-list-item class="text-h7" v-if="property.has_cave">Cave <v-icon icon="mdi mdi-door-closed-lock"></v-icon></v-list-item>
            <v-list-item class="text-h7">{{property.address}} ({{property.zipcode}}), {{property.city}} {{property.country}}</v-list-item>
          </v-list>
        </div>
        <div>
          <v-btn class="mr-5 mt-5 ml-auto" color="primary">Intéresse</v-btn>
        </div>
      </div>
    </v-card>
  </div>
  <v-card-text class="mt-10">
    {{property.description}}
  </v-card-text>
</template>

<style scoped>
#details-area{
  display: grid;
  grid-template-columns: 3fr 1fr;
  gap: 30px;
}
#container{
  display: grid;
  grid-template-columns: 3fr 2fr;
  gap: 30px;
}
</style>