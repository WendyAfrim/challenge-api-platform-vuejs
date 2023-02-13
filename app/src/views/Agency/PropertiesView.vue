<script setup lang="ts">
    import { useRoute } from 'vue-router';
    import { axios } from '@/services/auth';
    import { ref, computed } from 'vue';
    import PropertiesGrid from '@/components/PropertiesGrid.vue';
</script>

<template>
    <div class="mx-16 h-75">
        <h1 class="mb-10 text-h4 font-weight-bold">Tous les biens</h1>
        <PropertiesGrid />
    </div>
</template>