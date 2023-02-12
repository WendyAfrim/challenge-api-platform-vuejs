<script setup lang="ts">
    import { useRoute } from 'vue-router';
    import { axios } from '@/services/auth';
    import { ref, computed } from 'vue';

    const route = useRoute();
    const user = ref();
    const loading = ref(false);
    const message = ref({ text: '', type: undefined as "error" | "success" | "warning" | "info" | undefined });

</script>

<template>
<v-container class="ma-auto">
    <v-btn class="mb-10" icon @click="$router.go(-1)">
        <v-icon>mdi-arrow-left</v-icon>
    </v-btn>
    <h1 class="mb-10 text-h4 font-weight-bold">Demandes de visite</h1>
    <v-table>
        <thead>
            <tr class="">
                <th class="text-left font-weight-bold">Prénom</th>
                <th class="text-left">Nom</th>
                <th class="text-left">Bien</th>
                <th class="text-left">Créneau</th>
            </tr>
        </thead>
    </v-table>
</v-container>

</template>

<style scoped lang="scss">
    .item {
        flex: 1 1 0;
        width: 0;
    }
</style>