<script setup lang="ts">
import { useRoute } from 'vue-router';
import Navbar from '@/components/Navbar.vue';
import type { Roles } from '@/enums/roles';

const route = useRoute();
const forType = route.meta.forType as Roles;
let titleFirstWords: string|undefined, titleLastWord: string|undefined;
if (route.meta.title as string) {
    const title: string[] = (route.meta.title as string).split(" ") ?? [];
    titleLastWord = title.pop();
    titleFirstWords = title.join(" ");
}

</script>

<template>
    <v-app>
        <Navbar :for="forType" />
        <v-main>
            <v-container :fluid="route.meta.fluid as boolean">
                <h1 v-if="route.meta.title" class="mb-3 text-h4 font-weight-bold heading-sentence">{{titleFirstWords}} <span>{{titleLastWord}}</span></h1>
                <router-view></router-view>
            </v-container>
        </v-main>
    </v-app>
</template>

<style scoped lang="scss">

</style>