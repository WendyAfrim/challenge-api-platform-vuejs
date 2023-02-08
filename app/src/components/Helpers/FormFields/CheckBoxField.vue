<template>
    <label :for="name">{{ label }}</label>
    <input :type="type" :name="name"  @input="$emit('update:modelValue', $event.target.value)"/>
</template>

<script setup lang="ts">
    defineProps<{
        type: string,
        name: string,
        label: string
    }>();

    defineEmits(['update:modelValue']);
</script>