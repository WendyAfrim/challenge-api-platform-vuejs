<template>

    <label :for="name">{{ label }}</label>
    <select :name="name" :id="id" @input="$emit('update:modelValue', $event.target.value)">
        <option v-for="(text, value) in options" :value="value">{{ text }}</option>
    </select>
</template>

<script setup lang="ts">
    defineProps<{
        label: string,
        name: string,
        id: string,
        options: string[] | number[]
    }>()

    defineEmits(['update:modelValue']);
</script>

<style></style>