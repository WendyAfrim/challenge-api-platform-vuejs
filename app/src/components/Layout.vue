<script setup lang="ts">
</script>

<template>
    <v-main>
        <v-container fluid>
            <slot name="header" />
            <slot />
        </v-container>
    </v-main>
</template>

<style scoped lang="scss">
.v-main {
    display: flex;
    min-height: 100vh;
    padding: 0;

    .v-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 100%;
        padding: 0;
    }
}
</style>