<template>
    <h1>Formulaire - Ajout d'un bien</h1>
    <Input type="text" label="" name="title" placeholder="Nom du bien" @update:modelValue="newValue => property.title = newValue"/>
    <Input type="text" label="" name="address" placeholder="Adresse" @update:modelValue="newValue => property.address = newValue" />
    <Input type="text" label="" name="zipcode" placeholder="Code postal" @update:modelValue="newValue => property.zipcode = newValue" />
    <Input type="text" label="" name="country" placeholder="Pays" @update:modelValue="newValue => property.country = newValue" :value="property.country"/>
    <TextAreaField for="description" label="Description" name="description" id="description" cols="5" rows="33" @update:modelValue="newValue => property.description = newValue"></TextAreaField>
    <SelectField for="type" name="type" id="type" label="Type de bien" :options="propertyType" @update:modelValue="newValue => property.type = newValue"></SelectField>
    <SelectField for="number_room" name="number_room" id="number_room" label="Nombre de chambres" :options="numberRoom"></SelectField>
    <Input type="number" name="surface" placeholder="Surface" @update:modelValue="newValue => property.surface = newValue"/>
    <CheckBoxField type="checkbox" label="Balcon" name="has_balcony" @update:modelValue="newValue => property.has_balcony = newValue"/>
    <CheckBoxField type="checkbox" label="Terasse" name="has_terrace" @update:modelValue="newValue => property.has_terrace = newValue"/>
    <CheckBoxField type="checkbox" label="Cave" name="has_cave" @update:modelValue="newValue => property.has_cave = newValue"/>
    <CheckBoxField type="checkbox" label="Ascenseur" name="has_elevator" @update:modelValue="newValue => property.has_elevator = newValue"/>
    <CheckBoxField type="checkbox" label="Parking" name="has_parking" @update:modelValue="newValue => property.has_parking = newValue"/>
    <Input type="text" label="" name="energy_grade" placeholder="CO2 Grade" @update:modelValue="newValue => property.energy_grade = newValue"/>
    <CheckBoxField type="checkbox" label="Equipé" name="is_furnished" @update:modelValue="newValue => property.is_furnished = newValue"/>
    <Input type="number" name="monthly_rent" placeholder="Prix mensuel" @update:modelValue="newValue => property.monthly_rent = newValue"/>
    <SelectField for="state" name="state" id="state" label="Etat du bien" :options="state"></SelectField>

    <button type="submit" @click="addProperty">Enregistrer</button>
</template>

<script setup lang="ts">
    import CheckBoxField from '../Helpers/FormFields/CheckBoxField.vue';
    import Input from '../Helpers/FormFields/InputField.vue';
    import SelectField from '../Helpers/FormFields/SelectField.vue';
    import TextAreaField from '../Helpers/FormFields/TextAreaField.vue';


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
        energy_grade: string
        co2_grade: string
        is_furnished: boolean
        monthly_rent: number
        roommate_accepted: boolean
        // availability_date: Date
        state: string
    };

    let property: Property = {
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
        energy_grade: '',
        co2_grade: '',
        is_furnished: false,
        monthly_rent: 0,
        roommate_accepted: false,
        // availability_date: '01/10/4242',
        state: '',
    };

    let propertyType: string[] = ['Appartment', 'House'];
    let numberRoom = [0, 1, 2, 3, 4, 5];
    let state = ['Busy', 'Available']

    function addProperty(event: MouseEvent) {
        console.log(property.type);
        
    }
</script>

<style scoped>
</style>