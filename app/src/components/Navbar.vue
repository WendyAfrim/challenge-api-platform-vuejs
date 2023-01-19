<script setup lang="ts">

const props = defineProps<{
    for: 'homeowner' | 'tenant';
}>()


</script>

<template>
    <v-app-bar color="white" elevation="0" class="sticky">
        <v-app-bar-title class="font-weight-bold ml-16" text="Easyhome" />
        <v-container class="d-flex justify-end">
            <template v-if="props.for === 'homeowner'">
                <v-btn color="primary" variant="outlined" href="/">Vous êtes locataire ?</v-btn>
            </template>
            <template v-else>
                <v-btn color="primary" variant="outlined" href="/proprietaire">Vous êtes propriétaire ?</v-btn>
            </template>
            <v-btn color="primary" href="/login" class="ml-3">Je me connecte</v-btn>
        </v-container>
    </v-app-bar>
</template>

<style scoped lang="scss">
.v-app-bar {
    backdrop-filter: saturate(200%) blur(10px);
}
</style>