<template>
  <div v-if="shouldShow">
    <slot />
  </div>
</template>
  
<script setup lang="ts">
  import { computed, inject } from 'vue';
  import { StepCounterKey, CurrentStepIndexKey } from '@/symbols'

  const currentId = inject(StepCounterKey)!.value++;
  const formStepId = inject(CurrentStepIndexKey);
  
  const shouldShow = computed(() => {
    return currentId === formStepId!.value;
  });
</script>