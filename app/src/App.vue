<script setup lang="ts">
import { RouterView } from 'vue-router'
</script>

<template>
  <v-theme-provider theme="light">
    <v-app>
      <RouterView v-slot="{ Component, route }">
        <Suspense>
          <component :is="Component" :key="route.fullPath" />
          <template #fallback>
            <div>Loading...</div>
          </template>
        </Suspense>
      </RouterView>
    </v-app>
  </v-theme-provider>
</template>

<style scoped>
.v-app {
  background: white;
}
</style>
