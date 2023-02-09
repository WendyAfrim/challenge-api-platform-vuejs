<template>
  <div v-if="shouldShow">
    <slot />
  </div>
</template>
  
<script setup lang="ts">
  import { computed, inject } from 'vue';

  const currentId = inject('STEP_COUNTER').value++;
  const formStepId = inject('CURRENT_STEP_INDEX');

  const shouldShow = computed(() => {
    return currentId === formStepId.value;
  });
</script>