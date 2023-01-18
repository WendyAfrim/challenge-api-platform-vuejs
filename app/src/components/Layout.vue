<script setup lang="ts">
</script>

<template>
    <v-main>
        <v-container fluid>
            <slot />
        </v-container>
    </v-main>
</template>

<style scoped lang="scss">
.v-main {
    display: flex;
    max-height: 100vh;

    .v-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 100%;
    }
}
</style>