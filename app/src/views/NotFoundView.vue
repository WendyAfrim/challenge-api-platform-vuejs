<script setup lang="ts">
  import Layout from '@/components/Layout.vue';
  import { Vue3Lottie } from 'vue3-lottie'
  import 'vue3-lottie/dist/style.css'
  import NotFoundLottie from '@/assets/404.json';
</script>
    
<template>
  <Layout>
    <template v-slot>
      <v-layout class="d-flex flex-column justify-center align-center">
        <v-row align="center" justify="center">
          <v-col cols="12" md="6" class="text-center">
            <h1 class="text-h3 font-weight-black heading-sentence">
              Désolé, la page que vous recherchez <span>n'existe pas</span>.
            </h1>
            <Vue3Lottie
              :animationData="NotFoundLottie"
            />
          </v-col>
        </v-row>
    </v-layout>
    </template>
  </Layout>
</template>


<style scoped>
</style>