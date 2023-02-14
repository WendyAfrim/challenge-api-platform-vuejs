<script setup lang="ts">
    import { useAuthStore } from '@/stores/auth.store';
    import { axios } from '@/services/auth';
    import { ref, computed } from 'vue';

    const authStore = useAuthStore();
    const user = ref();

    try {
        const response = await axios.get(`${import.meta.env.VITE_BASE_API_URL}/users/` + authStore.user.id);
        user.value = response.data;
        console.log(response);
    } catch (error) {
        console.log(error);
    }

    const chipColor = computed(() => {
        switch (user.value?.validationStatus) {
            case 'validated':
                return 'success';
            case 'to_complete':
                return 'warning';
            case 'to_review':
            default:
                return 'primary';
        }
    });
</script>

<template>
    <div class="mb-10">
        <h2 class="mb-4 text-h4 font-weight-bold">Mon dossier</h2>
        <v-chip :color="chipColor" text-color="white" class="mb-6">
            {{ $t(`validation_status.${user?.validationStatus}`) }}
        </v-chip>
    </div>
    <v-row>
        <v-col cols="12" md="6">
            <v-card>
                <v-card-title>
                    <h2 class="headline mb-0">Mes informations</h2>
                </v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col cols="12" md="6">
                            <v-text-field :model-value="user?.firstname" label="Prénom" outlined dense readonly />
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-text-field :model-value="user?.lastname" label="Nom" outlined dense readonly />
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-text-field :model-value="user?.email" label="Email" outlined dense readonly />
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-text-field :model-value="user?.situation" label="Situation" outlined dense readonly />
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-col>
        <v-col cols="12" md="6">
            <v-card>
                <v-card-title>
                    <h2 class="headline mb-0">Mes documents</h2>
                </v-card-title>
                <v-card-text>
                    <v-list lines="three">
                    <v-list-item v-for="item in user.documents" :key="item.name">
                        <v-list-item-title class="text-h7 font-weight-bold">{{ $t(`document_type.${item.type}`) }}</v-list-item-title>
                        <div class="d-flex align-center">
                            <a :href="item.contentUrl" target="_blank" class="mr-3 font-weight-medium">{{ item.name }}</a>
                            <v-icon v-if="item.status === 'validated'" icon="mdi-check-bold"></v-icon>
                            <v-icon v-if="item.status === 'rejected'" icon="mdi-close-thick"></v-icon>
                            <v-icon v-if="item.status === 'to_review'" icon="mdi-account-clock" color="primary"></v-icon>
                            <v-icon tag="a" :href="item.contentUrl" target="_blank" icon="mdi-eye-arrow-right" color="primary" class="ml-3"></v-icon>
                        </div>
                    </v-list-item>
                    </v-list>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>