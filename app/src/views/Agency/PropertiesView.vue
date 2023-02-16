<script setup lang="ts">
    import { useRoute } from 'vue-router';
    import { axios } from '@/services/auth';
    import { ref, computed } from 'vue';
    import PropertiesGrid from '@/components/PropertiesGrid.vue';
</script>

<template>
    <v-container>
        <v-row>
            <v-col cols="12" md="6">
                <h1 class="mb-3 text-h4 font-weight-bold heading-sentence">Biens <span>immobiliers</span></h1>
                <PropertiesGrid />
            </v-col>
        </v-row>
    </v-container>
</template>