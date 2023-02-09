<template>
    <v-container>

        <h1 class="text-h4 font-weight-bold text-center mb-10">Formulaire - Ajout d'un bien</h1>
        <v-alert v-if="message.text" class="mb-10 text-white" :color="message.type">
          {{ message.text }}
        </v-alert>

        <v-form ref="form" v-model="valid" >
            <v-row class="d-flex justify-center align-start">
                <v-col cols="12" md="6">
                    <v-text-field v-model="property.title" :rules="titleRules" label="Nom du bien" required ></v-text-field>
                </v-col>
                <v-col cols="12" md="6">
                    <v-text-field v-model="property.price" type="number" :rules="monthlyRentRules" label="Prix mensuel" required ></v-text-field>
                </v-col>
            </v-row>
            <v-row class="d-flex justify-center align-start">
                <v-col cols="12" md="6">
                    <v-select v-model="property.type" :items="propertyType" :rules="typeRules" label="Type de bien" solo></v-select>
                </v-col>
                <v-col cols="12" md="6">
                    <v-select v-model="property.state" :items="propertyState" :rules="stateRules" label="Etat du bien" solo></v-select>
                </v-col>
            </v-row>
            <v-row class="d-flex justify-center align-start">
                <v-col cols="12" md="3">
                    <v-text-field v-model="property.address" :rules="addressRules" label="Adresse" required></v-text-field>
                </v-col>
                <v-col cols="12" md="3">
                    <v-text-field v-model="property.zipcode" :rules="zipcodeRules" label="Code postal" required></v-text-field>
                </v-col>
                <v-col cols="12" md="3">
                    <v-text-field v-model="property.city" :rules="zipcodeRules" label="Ville" required></v-text-field>
                </v-col>
                <v-col cols="12" md="3">
                    <v-text-field v-model="property.country" :rules="countryRules" label="Pays" required></v-text-field>
                </v-col>
            </v-row>
            <v-row class="d-flex justify-center align-start">
                <v-col cols="12" md="12">
                    <v-textarea outlined name="input-7-4" label="Description" v-model="property.description"></v-textarea>
                </v-col>
            </v-row>
            <v-row class="d-flex justify-center align-start">
                <v-col cols="12" md="12">
                    <v-file-input
                        v-model="property.photos"
                        placeholder="Upload your documents"
                        label="Photos"
                        multiple
                        prepend-icon="mdi-paperclip"
                    >
                        <template v-slot:selection="{ fileNames }">
                            <template v-for="fileName in fileNames" :key="fileName">
                                <v-chip
                                size="small"
                                label
                                color="primary"
                                class="me-2"
                                >
                                {{ fileName }}
                                </v-chip>
                            </template>
                        </template>
                    </v-file-input>
                </v-col>
            </v-row>
            <v-row class="d-flex justify-center align-start">
                <v-col cols="12" md="12">
                    <v-select :items="numberRoom" label="Nombre de chambres" solo v-model="property.number_rooms"></v-select>
                </v-col>
            </v-row>
            <v-row class="d-flex justify-center align-start">
                <v-col cols="12" md="12">
                    <v-text-field type="number" v-model="property.surface" :rules="surfaceRules" label="Surface" required></v-text-field>
                </v-col>
            </v-row>

            <v-row class="d-flex justify-center align-start">
                <v-col cols="12" md="4">
                    <v-checkbox  v-model="property.has_balcony" label="Balcon"></v-checkbox>
                </v-col>
                <v-col cols="12" md="4">
                    <v-checkbox  v-model="property.has_terrace" label="Terasse"></v-checkbox>
                </v-col>
                <v-col cols="12" md="4">
                    <v-checkbox  v-model="property.has_cave" label="Cave"></v-checkbox>
                </v-col>
            </v-row>

            <v-row class="d-flex justify-center align-start">
                <v-col cols="12" md="4">
                    <v-checkbox  v-model="property.has_elevator" label="Ascenseur"></v-checkbox>
                </v-col>
                <v-col cols="12" md="4">
                    <v-checkbox  v-model="property.has_parking" label="Parking"></v-checkbox>
                </v-col>
                <v-col cols="12" md="4">
                    <v-checkbox  v-model="property.is_furnished" label="Equipé"></v-checkbox>
                </v-col>
            </v-row>
            <v-col cols="12" class="d-flex justify-center">
                <v-btn color="primary" class="mr-4" @click="addProperty"> Ajouter ce bien </v-btn>
            </v-col>
        </v-form>
    </v-container>
</template>

<script setup lang="ts">
    import { useRoute, useRouter } from "vue-router";
    import { axios } from '@/services/auth';
    import { ref, reactive } from 'vue';
    import { useAuthStore } from '@/stores/auth.store';
    import type { Roles } from "@/enums/roles";

    const router = useRouter();
    const route = useRoute();

    const form = ref();
    const valid = ref(false);

    const forType = route.meta.forType as Roles;

    const errorType = ref('');
    const message = ref({
    text: '',
    type: ''
  })

    interface Property {
        title: string,
        address: string,
        zipcode: string,
        city: string,
        country: string,
        description: string,
        photos: [],
        type: string, 
        number_rooms: number,
        surface: any,
        has_balcony: boolean
        has_terrace: boolean
        has_cave: boolean
        has_elevator: boolean
        has_parking: boolean
        is_furnished: boolean
        price: any
        state: string
    }

    const property = reactive<Property>({
        title: '',
        address: '',
        zipcode: '',
        city: '',
        country: 'France',
        description: '',
        photos: [],
        type: '',
        number_rooms: 0,
        surface: 0,
        has_balcony: false,
        has_terrace: false,
        has_cave: false,
        has_elevator: false,
        has_parking: false,
        is_furnished: false,
        price: 0,
        state: '',
    });
    

    let propertyType: string[] = ['Appartement', 'Maison'];
    let propertyState: string[] = ['Libre', 'Occupé'];
    let numberRoom = [0, 1, 2, 3, 4, 5];


    const titleRules = ref([
        (v: string) => !!v || 'Le nom du bien est requis',
    ]);

    const monthlyRentRules = ref([
        (v: string) => !!v || 'Le prix du bien est requis',
    ]);

    const typeRules = ref([
        (v: string) => !!v || 'Le type du bien est requis',
    ]);

    const stateRules = ref([
        (v: string) => !!v || 'L\'état du bien est requis',
    ]);

    const addressRules = ref([
        (v: string) => !!v || 'L\'adresse  du bien est requis',
    ]);

    const zipcodeRules = ref([
        (v: string) => !!v || 'Le code postal du bien est requis',
    ]);

    const countryRules = ref([
        (v: string) => !!v || 'Le pays du bien est requis',
    ]);

    const numberRoomRules = ref([
        (v: string) => !!v || 'Le nombre de chambre du bien est requis',
    ]);


    const surfaceRules = ref([
        (v: number) => !!v || 'La surface du bien est requis',
    ]);


    console.log(property);

    const addProperty = (event: MouseEvent) => {
        event.preventDefault();

        if(valid.value) {
            const data = {
                title: property.title,
                address: property.address,
                zipcode: property.zipcode,
                country: property.country,
                description: property.description,
                photos: property.photos,
                type: property.type, 
                number_rooms: property.number_rooms,
                surface: parseInt(property.surface),
                has_balcony: property.has_balcony,
                has_terrace: property.has_terrace,
                has_cave: property.has_cave,
                has_elevator: property.has_elevator,
                has_parking: property.has_parking,
                is_furnished: property.is_furnished,
                price: parseInt(property.price),
                state: property.state,
            }

            message.value.text = '';
            message.value.type = '';


            axios.post(`${import.meta.env.VITE_BASE_API_URL}/properties`, data)
                .then((response) => {
                    message.value.text = 'Votre bien a été ajouté avec succès';
                    message.value.type = 'info';
                    router.push({ name: `${forType}_dashboard` })
                })
                .catch((error) => {
                    console.log(error);

                    errorType.value = error.response.data.error_type || '';
                    message.value.text = error.response.data.message || 'Une erreur est survenue. Veuillez réessayer.';
                    message.value.type = 'error';

                });
            } else {
                form.value.validate();
            }

        }
    
</script>

<style scoped>
</style>