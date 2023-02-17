<script setup lang="ts">
import { RouterView } from 'vue-router'
</script>

<template>
  <v-theme-provider theme="light">
    <v-app>
      <RouterView v-slot="{ Component, route }">
        <template v-if="Component">
          <Transition mode="out-in" name="fade">
            <Suspense timeout="0">
              <component :is="Component" :key="route.path" />
              <template #fallback>
                <v-progress-circular indeterminate color="primary" class="ma-auto" :size="64" :width="6"></v-progress-circular>
              </template>
            </Suspense>
          </Transition>
        </template>
      </RouterView>
    </v-app>
  </v-theme-provider>
</template>

<style scoped>
.v-app {
  background: white;
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
