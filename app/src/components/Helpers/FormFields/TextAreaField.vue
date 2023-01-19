<template>
    <label :for="name">{{ label }}</label>
    <textarea :name="name" :id="id" :cols="cols" :rows="rows" @input="$emit('update:modelValue', $event.target.value)"></textarea>
</template>

<script setup lang="ts">
    defineProps<{
        for: string,
        label: string,
        name: string,
        id: string,
        cols: string, 
        rows: string
    }>()

    defineEmits(['update:modelValue']);
</script>

<style></style>