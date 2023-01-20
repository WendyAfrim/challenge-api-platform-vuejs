<template>
    <v-container>
        <template>
            <h1 class="text-h4 font-weight-bold text-center mb-3">Formulaire - Ajout d'un bien</h1>
        </template>
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
                <v-col cols="12" md="4">
                    <v-text-field v-model="property.address" :rules="addressRules" label="Adresse" required></v-text-field>
                </v-col>
                <v-col cols="12" md="4">
                    <v-text-field v-model="property.zipcode" :rules="zipcodeRules" label="Code postal" required></v-text-field>
                </v-col>
                <v-col cols="12" md="4">
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

    import axios from 'axios';
    import { ref, reactive } from 'vue';

    const form = ref();
    const valid = ref(false);

    interface Property {
        title: string,
        address: string,
        zipcode: string,
        country: string,
        description: string,
        type: string, 
        number_rooms: number,
        surface: number,
        has_balcony: boolean
        has_terrace: boolean
        has_cave: boolean
        has_elevator: boolean
        has_parking: boolean
        is_furnished: boolean
        price: number
        state: string
    };

    const property = reactive<Property>({
        title: '',
        address: '',
        zipcode: '',
        country: 'France',
        description: '',
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
        (v: string) => !!v || 'La surface du bien est requis',
    ]);


    const addProperty = (event: MouseEvent) => {
        event.preventDefault();

        if(valid.value) {
            const data = {
                title: property.title,
                address: property.address,
                zipcode: property.zipcode,
                country: property.country,
                description: property.description,
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

            axios.post(`${import.meta.env.VITE_BASE_API_URL}/properties`, data)
                .then((response) => {
                    console.log(response);
                })
                .catch((error) => {
                    console.log(error);
                });
            } else {
                form.value.validate();
            }
        }
    
</script>

<style scoped>
</style>