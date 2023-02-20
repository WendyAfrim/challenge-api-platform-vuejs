<script setup lang="ts">
import {computed, ref} from "vue"

const props = defineProps({
  images: Array,
});

const currentIndex = ref(0);

const has_next = computed(() => {
  if(undefined !== props.images) {
    return currentIndex.value < props.images.length -1;
  }
  return false;
});
const next = function() {
  if(undefined !== props.images && has_next) {
    currentIndex.value += 1;
  }
}

const has_previous = computed(()=>{
  if(undefined !== props.images) {
    return currentIndex.value > 0;
  }
  return false;
});
const prev = function() {
  if(undefined !== props.images && has_previous) {
    currentIndex.value -= 1
  }
}
</script>

<template>
  <div style="text-align: center">
      <div v-for="(image, i) in props.images" :key="i" >
        <img alt="" :src="image" :height="400" style="position: center" v-if="currentIndex === i && undefined !== image "/>
      </div>
      <v-btn class="ma-1" color="primary" variant="outlined" :disabled="!has_previous" @click.prevent="prev">
        <v-icon icon='mdi-chevron-left'></v-icon>
      </v-btn>
      <v-btn class="ma-1" color="primary" variant="outlined" :disabled="!has_next" @click.prevent="next">
        <v-icon icon='mdi-chevron-right'></v-icon>
      </v-btn>
  </div>
</template>

<style scoped>

</style>