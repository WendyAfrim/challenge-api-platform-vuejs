<template>
    <v-container>
        <v-alert v-if="message.text" class="mb-10 text-white" :color="message.type">
          {{ message.text }}
        </v-alert>
        <div v-if="!viewing">
            <h1 class="text-h4 font-weight-bold mb-6">Créneaux proposés - {{ request?.property.title }}</h1>
            <div v-if="availaibilities">
                <v-radio-group v-model="slot" class="w-100">
                    <v-radio class="w-100" v-for="(availaibility, index) in availaibilities" :key="index" :value="availaibility.id" :label="availaibility.slot">
                    </v-radio>
                </v-radio-group>
                <v-progress-circular v-if="loading" indeterminate color="primary" class="mt-5"></v-progress-circular>
                <v-btn v-else color="primary"  @click="postSlot">Enregistrer</v-btn>
            </div>
        </div>
        <div v-else>
            <h1 class="text-h4 font-weight-bold mb-6">Votre visite</h1>
            <v-card :title="request?.property.title">
                <v-container>
                    Nous vous confirmons une visite le : <strong>{{ viewing.availaibility.slot }}</strong> <br/>
                    Un email a été envoyé au propriétaire ainsi qu'un l'agence à titre d'information. <br/>
                    Prochainement l'agence va attribuer un agent à votre visite. Vous en serez notifié ! 
                </v-container>
                <v-card-actions>
                    <router-link :to="{ name: 'homeowner_dashboard' }">
                        <v-btn variant="tonal" color="primary">Retour à l'accueil</v-btn>
                    </router-link>
                </v-card-actions>
            </v-card>
        </div>
    </v-container>
</template>

<script setup lang="ts">
    import { ref, computed } from 'vue';
    import { getTenantRequest } from '@/services/tenant/requests';
    import { postSlotChoosenByTheLodger } from '@/services/tenant/requests';
    import { useRoute } from "vue-router";
    import type { Availaibility } from '@/interfaces/Availaibility';
    import type { PropertyRequest } from '@/interfaces/PropertyRequest';
    import type { Viewing } from '@/interfaces/Viewing';


    const request = ref<PropertyRequest>();
    const availaibilities = ref<Availaibility[]>();
    const viewing = ref<Viewing>();
    const slot = ref();
    const loading = ref(false);

    const message = ref({
        text: '',
        type: ''
    })

    let router = useRoute();
    let requestId: any = router.params.id;


    await getTenantRequest(requestId)
        .then((response) => {
            request.value = response;
            availaibilities.value = response.availaibilities;

        }).catch((error) => {
            console.log(error);
        });

    async function postSlot() {
        loading.value = true;
        await postSlotChoosenByTheLodger(requestId, slot.value)
        .then((response) => {
            viewing.value = response
        }).catch((error) => {
            message.value.text = error;
            message.value.text = 'red';
        })

        loading.value = false;

    }
</script>

<style scoped>
</style>