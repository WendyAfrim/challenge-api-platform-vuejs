<script  setup lang="ts">
import {ref, defineProps} from "vue"
import { axios } from '@/services/auth';

const selectedFile = ref();

const props = defineProps({
  propertyId: String
})

const message = ref({
  text:'',
  type:''
});

function onFileSelected(event: any){
  selectedFile.value = event.target.files[0];
}

async function onUpload() {
  console.log("pro Id: ",  props.propertyId)
  const formData = new FormData();
  if(undefined !== selectedFile.value && undefined !== selectedFile.value.name){
    formData.append('file', selectedFile.value, selectedFile.value.name);
    formData.append('property_id', props.propertyId as string);
  }
  try {
    const response = await axios.post(`${import.meta.env.VITE_BASE_API_URL}/media_objects`, formData, {
      headers:{
        'Content-type' : 'multipart/form-data'
      }
        }
    )
    if(response.data){
      message.value.text = 'fichier recu';
      message.value.type = 'success';
    }
  } catch (error: any) {
    console.log("err: ", error)
    message.value.text = '';
    message.value.type = '';
    message.value.text = error.response.data.message || 'Une erreur est survenue. Veuillez réessayer.';
    message.value.type = 'error';
  }

}


</script>

<template>
  <v-card>
    <v-alert v-if="message.text" class="text-white" :color="message.type">
      {{ message.text }}
    </v-alert>
    <v-file-input
        color="primary"
        variant="outlined"
        show-size
        label="File"
        @change="onFileSelected"
    ></v-file-input>
    <v-btn color="primary" variant="tonal" @click="onUpload">
      Upload
      <v-icon primary>mdi-cloud-upload</v-icon>
    </v-btn>
  </v-card>


</template>

<style scoped>

</style>