<template>
    <input :type="type" :name="name" :placeholder="placeholder" @input="$emit('update:modelValue', $event.target.value)"/>
</template>

<script setup lang="ts">
    defineProps<{
        type: string,
        name: string,
        placeholder: string,
    }>();

    defineEmits(['update:modelValue']);
</script>