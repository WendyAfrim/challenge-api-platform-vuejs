<template>
  <div class="d-flex h-50">
    <div class="d-flex flex-column justify-space-around">
      <template v-for="index in stepCounter" :key="index">
        <div class="step d-flex align-center mr-16">
          <div class="bullet d-flex justify-center align-center" :class="[index <= currentStepId+1 ? 'bg-primary' : 'bg-white to-complete', {'validated': index < currentStepId+1}]">
            <v-icon v-if="index < currentStepId+1" icon="mdi-check-bold"></v-icon>
            <v-icon v-else-if="index === currentStepId+1" icon="mdi-dots-horizontal"></v-icon>
            <div v-else>{{ index }}</div>
          </div>
          <div class="font-weight-medium ml-4 text-h7">
            <slot :name="`stepper_name_${index}`">
              Etape {{ index }}
            </slot>
          </div>
        </div>
      </template>
    </div>

    <form class="flex-grow-1" @submit="onSubmit">
      <slot />
      <div class="d-flex justify-space-between mt-10">
        <v-btn color="primary" v-if="hasPrevious" type="button" @click="goToPrev">Précedent</v-btn>
        <v-btn color="primary" type="submit">{{ isLastStep ? 'Envoyer' : 'Suivant' }}</v-btn>
      </div>
    </form>
  </div>
</template>
  
  <script setup lang="ts">
  import { useForm } from 'vee-validate';
  import { ref, computed, provide } from 'vue';
  
  const props = defineProps({
    validationSchema: {
      type: Array,
      required: true,
    },
  });
  const emit = defineEmits(['submit']);
  const currentStepId = ref(0);
  
  // Injects the starting step, child <form-steps> will use this to generate their ids
  const stepCounter = ref(0);
  provide('STEP_COUNTER', stepCounter);
  
  // Inject the live ref of the current index to child components
  // will be used to toggle each form-step visibility
  provide('CURRENT_STEP_INDEX', currentStepId);
  
  // if this is the last step
  const isLastStep = computed(() => {
    return currentStepId.value === stepCounter.value - 1;
  });
  
  const hasPrevious = computed(() => {
    return currentStepId.value > 0;
  });
  
  const currentSchema = computed(() => {
    return props.validationSchema[currentStepId.value];
  });
  
  const { values, handleSubmit } = useForm({
    validationSchema: currentSchema,
    keepValuesOnUnmount: true,
  });
  const onSubmit = handleSubmit((values) => {
    if (!isLastStep.value) {
      currentStepId.value++;
  
      return;
    }
    emit('submit', values);
  });
  
  function goToPrev() {
    if (currentStepId.value === 0) {
      return;
    }
  
    currentStepId.value--;
  }
</script>
  
<style scoped>
  .validated {
    opacity: 0.5;
  }
  .to-complete {
    border: 2px solid #bfbfbf;
    opacity: 0.7;
  }
</style>