<script  setup lang="ts">
import {ref} from "vue"
import { axios } from '@/services/auth';

const selectedFile = ref();


function onFileSelected(event: any){
  selectedFile.value = event.target.files[0];
}

const emit = defineEmits(['file-uploaded']);
async function onUpload() {
  const formData = new FormData();
  formData.append('file', selectedFile.value, selectedFile.value.name);
  try {
    const response = await axios.post('https://localhost/media_objects', formData)
    if(response.data){
      emit('file-uploaded', response.data)
    }
  } catch (error) {
    console.log(error);
  }
}


</script>

<template>
  <v-card>
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