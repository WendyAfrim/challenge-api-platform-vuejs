<template>
    <v-container>
        <h1 class="text-h4 font-weight-bold mb-6">Créneaux proposés - {{ availaibilities[0]?.property.title }}</h1>
        <div v-if="availaibilities.length > 0">
            <v-radio-group v-model="slot" class="w-100">
                <v-radio class="w-100" v-for="(availaibility, index) in availaibilities" :key="index" :value="availaibility.id" :label="availaibility.slot">
                </v-radio>
            </v-radio-group>
            <v-btn color="primary" @click="postSlot">Enregistrer</v-btn>
        </div>
        <div v-else>
            <h1 class="text-h5 font-weight-bold mb-6">Aucun créneau disponible</h1>
        </div>
    </v-container>
</template>

<script setup lang="ts">
    import { ref, computed } from 'vue';
    import { getRequestSlots } from '@/services/tenant/requests';
    import { postSlotChoosenByTheLodger } from '@/services/tenant/requests';
    import { useRoute } from "vue-router";


    const availaibilities = ref();
    const slot = ref();

    let router = useRoute();
    let requestId: any = router.params.id;


    await getRequestSlots(requestId)
        .then((response) => {
            availaibilities.value = response;

        }).catch((error) => {
            console.log(error);
        });

    async function postSlot() {
        await postSlotChoosenByTheLodger(requestId, slot.value)
    }
</script>

<style scoped>
</style>