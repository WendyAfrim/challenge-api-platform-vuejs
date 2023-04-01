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

const alert = ref(false);

function onFileSelected(event: any){
  selectedFile.value = event.target.files[0];
}

async function onUpload() {
  alert.value = true;
  if (undefined === selectedFile.value) {
    message.value.text = 'Veuillez sélectionner un fichier';
    message.value.type = 'error';
    return;
  }
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
      message.value.text = 'Ce fichier a été ajouté avec succès';
      message.value.type = 'success';
    }
  } catch (error: any) {
    console.log("err: ", error)
    message.value.text = '';
    message.value.type = '';
    message.value.text = error.response.data.detail || 'Une erreur est survenue. Veuillez réessayer.';
    message.value.type = 'error';
  }

}


</script>

<template>
  <v-alert v-if="message.text" v-model="alert" class="text-white mb-3" :color="message.type" density="compact" closable>
    {{ message.text }}
  </v-alert>
  <v-row align="center">
    <v-col cols="12">
      <v-file-input
      color="primary"
      variant="outlined"
      show-size
      label="File"
      @change="onFileSelected"
      append-icon="mdi-cloud-upload"
      @click:append="onUpload"
      @click:clear="selectedFile = undefined"
      ></v-file-input>
    </v-col>
  </v-row>
</template>

<style scoped>

</style>