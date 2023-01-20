<script setup lang="ts">

const props = defineProps<{
    for?: 'homeowner' | 'tenant';
}>()


</script>

<template>
    <v-app-bar color="transparent" elevation="0" class="sticky">
        <router-link :to="{ name: `${props.for}_home` }">
            <v-app-bar-title class="font-weight-bold ml-16" text="Easyhome" />
        </router-link>
        <v-container class="d-flex justify-end">
            <template v-if="props.for === 'homeowner'">
                <v-btn color="primary" variant="outlined" href="/">Vous êtes locataire ?</v-btn>
            </template>
            <template v-else-if="props.for === 'tenant'">
                <v-btn color="primary" variant="outlined" href="/homeowner">Vous êtes propriétaire ?</v-btn>
            </template>
            <template v-else></template>
            <v-btn color="primary" href="/login" class="ml-3">Je me connecte</v-btn>
        </v-container>
    </v-app-bar>
</template>

<style scoped lang="scss">
.v-app-bar {
    backdrop-filter: blur(5px);
}
</style>