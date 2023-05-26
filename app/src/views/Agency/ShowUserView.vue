<script setup lang="ts">
    import { useRoute } from 'vue-router';
    import { axios } from '@/services/auth';
    import { ref, computed } from 'vue';
    import { UserValidationStatus } from '@/enums/UserValidationStatus';

    const route = useRoute();
    const user = ref();
    const loading = ref(false);
    const message = ref({ text: '', type: undefined as "error" | "success" | "warning" | "info" | undefined });

    const allDocumentsValidated = computed(() => {
        return user.value.documents.every((document: any) => document.isValid);
    });
    const invalidDocuments = computed(() => {
        return user.value.documents.filter((document: any) => !document.isValid);
    });
    const response = await axios.get(`${import.meta.env.VITE_BASE_API_URL}/users/${route.params.id}`);
    user.value = response.data;
    const baseValidatedDocuments = ref(response.data.documents.filter((document: any) => document.isValid));
    const baseInvalidDocuments = ref(response.data.documents.filter((document: any) => !document.isValid));

    async function handleSubmit() {
        loading.value = true;
        try {
            message.value = { text: '', type: undefined };
            const response = await axios.put(`${import.meta.env.VITE_BASE_API_URL}/users/${user.value.id}`, {
                validationStatus: user.value.documents.every((document: any) => document.isValid) ? UserValidationStatus.Validated : UserValidationStatus.ToComplete,
            }, {
                headers: {
                    'Content-Type': 'application/ld+json',
                }
            });
            user.value.documents.forEach(async (element: any) => {
                const response2 = await axios.put(`${import.meta.env.VITE_BASE_API_URL}/documents/${element.id}`, {
                    isValid: element.isValid,
                });
            });
            message.value = { text: 'Le dossier a bien été mis à jour.', type: 'success' };
            user.value.validationStatus = response.data.validationStatus;
            baseValidatedDocuments.value = user.value.documents.filter((document: any) => document.isValid);
        } catch (error: any) {
            message.value = { text: error.response.data.detail || 'Une erreur est survenue. Veuillez réessayer.', type: 'error' };
            console.log(error.response.data);
        }
        loading.value = false;
    }

    const chipColor = computed(() => {
        switch (user.value.validationStatus) {
            case UserValidationStatus.Validated:
                return 'success';
            case UserValidationStatus.ToComplete:
                return 'warning';
            case UserValidationStatus.ToReview:
            default:
                return 'warning';
        }
    });
</script>

<template>
<v-container class="ma-auto">
    <v-btn class="mb-10" icon @click="$router.go(-1)">
        <v-icon>mdi-arrow-left</v-icon>
    </v-btn>
    <v-row align="center">
        <v-col cols="12" md="6">
            <h4 class="text-h4 font-weight-bold heading-sentence">Dossier de <span>{{ user.firstname }} {{ user.lastname }}</span> (#{{ user.id }})</h4>
            <v-chip :color="chipColor" class="mt-4" label outlined>
                    {{ $t(`validation_status.${user?.validationStatus}`) }}
            </v-chip>
        </v-col>
    </v-row>
    <div class="d-flex flex-column">
                <v-list lines="three">
                    <h3 class="mb-2 text-h5 font-weight-bold">Informations</h3>
                    <v-list-item density="compact">
                        <v-list-item-title class="text-h7 font-weight-bold">Prénom</v-list-item-title>
                        <span class="font-weight-medium">{{ user.firstname }}</span>
                    </v-list-item>
                    <v-list-item density="compact">
                        <v-list-item-title class="text-h7 font-weight-bold">Nom de famille</v-list-item-title>
                        {{ user.lastname }}
                    </v-list-item>
                    <v-list-item density="compact">
                        <v-list-item-title class="text-h7 font-weight-bold">Email</v-list-item-title>
                        {{ user.email }}
                    </v-list-item>
                    <v-list-item density="compact">
                        <v-list-item-title class="text-h7 font-weight-bold">Situation professionnelle</v-list-item-title>
                        {{ user.situation }}
                    </v-list-item>
                    <v-list-item density="compact">
                        <v-list-item-title class="text-h7 font-weight-bold">Salaire</v-list-item-title>
                        {{ user.salary ?? '0' }} €
                    </v-list-item>
                </v-list>

            <div class="d-flex flex-column">
                <v-alert v-if="message.text" :type="message.type" class="mt-2">{{ message.text }}</v-alert>

                <h3 class="mb-4 text-h5 font-weight-bold">Documents</h3>
                
                <v-row>
                    <v-col v-if="user.validationStatus === UserValidationStatus.ToReview" md="5">
                        <v-form ref="form" class="d-flex flex-column align-start" validate-on="submit" @submit.prevent="handleSubmit">
                            <v-list lines="three" class="w-100">
                                <h3 class="mb-4 text-h6 font-weight-bold">À valider</h3>
                                <v-list-item v-for="(item, index) in baseInvalidDocuments" :key="index" density="compact">
                                    <v-list-item-title class="text-h7 font-weight-bold">{{ $t(`document_type.${item.type}`) }}</v-list-item-title>
                                    <div class="d-flex align-center justify-between">
                                        <a :href="item.contentUrl" target="_blank" class="mr-3 font-weight-medium">{{ item.name }}</a>
                                        <v-switch v-model="baseInvalidDocuments[index].isValid" :name="item.type" color="success" hide-details></v-switch>
                                    </div>
                                    <v-divider></v-divider>
                                </v-list-item>
                            </v-list>
                            <v-progress-circular v-if="loading" indeterminate color="primary"></v-progress-circular>
                            <div v-if="!loading">
                                <div v-if="!allDocumentsValidated">
                                    <v-alert type="warning" class="mt-2">
                                        Vous êtes sur le point d'invalider les documents suivants :
                                        <ul>
                                            <li v-for="(item, index) in invalidDocuments" :key="index" class="font-weight-bold">{{ $t(`document_type.${item.type}`) }}</li>
                                        </ul>
                                        Le dossier sera considéré comme <span class="font-weight-bold">à compléter</span> et l'utilisateur devra renvoyer les pièces manquantes avant de pouvoir postuler aux annonces des propriétaires.
                                    </v-alert>
                                </div>
                                <div v-else>
                                    <v-alert type="success" class="mt-2">
                                        Vous êtes sur le point de valider tous les documents.
                                        Le dossier sera considéré comme <span class="font-weight-bold">validé</span> et l'utilisateur pourra dès à présent postuler aux annonces des propriétaires.
                                    </v-alert>
                                </div>
                            </div>
                            <v-btn  type="submit" color="primary" class="mt-2">Valider</v-btn>
                        </v-form>
                    </v-col>

                    <v-col v-if="user.validationStatus === UserValidationStatus.ToComplete" >
                        <v-list lines="three" class="">
                            <h3 class="mb-4 text-h6 font-weight-bold">En attente</h3>
                            <v-list-item v-for="(item, index) in invalidDocuments" :key="index" density="compact">
                                <v-list-item-title class="text-h7 font-weight-bold">{{ $t(`document_type.${item.type}`) }}</v-list-item-title>
                                <a :href="item.contentUrl" target="_blank" class="mr-3 font-weight-medium">{{ item.name }}</a>
                                <v-divider></v-divider>
                            </v-list-item>
                        </v-list>
                    </v-col>

                    <v-col>
                        <v-list lines="three" class="">
                            <h3 class="mb-4 text-h6 font-weight-bold">Validés</h3>
                            <v-list-item v-for="(item, index) in baseValidatedDocuments" :key="index" density="compact">
                                <v-list-item-title class="text-h7 font-weight-bold">{{ $t(`document_type.${item.type}`) }}</v-list-item-title>
                                <a :href="item.contentUrl" target="_blank" class="mr-3 font-weight-medium">{{ item.name }}</a>
                                <v-divider></v-divider>
                            </v-list-item>
                        </v-list>
                    </v-col>
                </v-row>
            </div>
        </div>
</v-container>

</template>

<style scoped lang="scss">
    .v-list-item {
        padding: 4px 8px;
    }
    .item {
        flex: 1 1 0;
        width: 0;
    }
</style>