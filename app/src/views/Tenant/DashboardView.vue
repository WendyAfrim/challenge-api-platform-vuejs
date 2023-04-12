<script setup lang="ts">
    import { useAuthStore } from '@/stores/auth.store';
    import { computed } from 'vue';
    import { UserValidationStatus } from '@/enums/UserValidationStatus';

    const authStore = useAuthStore();
    const user = await authStore.getUser;

    const chipColor = computed(() => {
        switch (user.validationStatus) {
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
    <v-container>
        <v-chip :color="chipColor" class="mt-4" label outlined>
            {{ $t(`validation_status.${user?.validationStatus}`) }}
        </v-chip>
        <v-row>
            <v-col cols="12" md="6">
                <v-card>
                    <v-card-title class="heading-sentence">
                        Mes <span class="font-weight-medium">informations</span>
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
                    <v-card-title class="heading-sentence">
                        Mes <span class="font-weight-medium">documents</span>
                    </v-card-title>
                    <v-card-text>
                        <v-list lines="three" class="py-0">
                        <v-list-item v-for="item in user.documents" :key="item.name">
                            <v-list-item-title class="text-h7 font-weight-bold">{{ $t(`document_type.${item.type}`) }}</v-list-item-title>
                            <div class="d-flex align-center">
                                <a :href="item.contentUrl" target="_blank" class="mr-1 font-weight-medium">{{ item.name }}</a>
                                <v-icon tag="a" :href="item.contentUrl" target="_blank" icon="mdi-eye-arrow-right" color="primary" class="ml-1"></v-icon>
                            </div>
                        </v-list-item>
                        </v-list>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>