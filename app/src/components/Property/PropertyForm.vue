<template>
    <CustomInput type="text" name="title" placeholder="Nom du bien" @update:modelValue="newValue => property.title = newValue"/>
    <CustomInput type="text" name="address" placeholder="Adresse" @update:modelValue="newValue => property.address = newValue" />
    <CustomInput type="text" name="zipcode" placeholder="Code postal" @update:modelValue="newValue => property.zipcode = newValue" />
    <CustomInput type="text" name="country" placeholder="Pays" @update:modelValue="newValue => property.country = newValue" :value="property.country"/>
    <TextAreaField for="description" label="Description" name="description" id="description" cols="5" rows="33" @update:modelValue="newValue => property.description = newValue"></TextAreaField>

    <button type="submit" @click="addProperty">Enregistrer</button>
</template>

<script setup lang="ts">
    import CustomInput from '../Helpers/FormFields/CustomInput.vue';
    import TextAreaField from '../Helpers/FormFields/TextAreaField.vue';


    interface Property {
        title: string,
        address: string,
        zipcode: string,
        country: string,
        description: string,
        type: string
    };

    let property: Property = {
        title: '',
        address: '',
        zipcode: '',
        country: 'France',
        description: '',
        type: '',
    };

    function addProperty(event: MouseEvent) {
        console.log(property);
        
    }
</script>

<style scoped>
</style>