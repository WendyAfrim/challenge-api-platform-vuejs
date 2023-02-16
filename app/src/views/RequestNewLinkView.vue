<script setup lang="ts">
import { ref } from "vue";
import { Field, Form } from 'vee-validate';
import * as yup from 'yup';
import { requestNewLink } from '@/services/user';
import Navbar from '@/components/Navbar.vue';
import { Roles } from "@/enums/roles";

  const loading = ref<Boolean>(false);
  const message = ref({
    text: '',
    type: ''
  })

  const schema = yup.object({
    email: yup.string().email().required(),
  });

  async function handleSubmit(values: any) {
    loading.value = true;
    try {
      message.value.text = '';
      message.value.type = '';
      const response = await requestNewLink(values.email);
      message.value.text = response.message;
      message.value.type = 'success';
    } catch (error: any) {
      message.value.text = error.response.data.message || 'Une erreur est survenue. Veuillez réessayer.';
      message.value.type = 'error';
    }
    loading.value = false;
  }
</script>

<template>
    <v-layout class="d-flex flex-column justify-center items-center">
        <Navbar :for="Roles.Tenant" />
        <div class="ma-auto w-25">
            <v-alert v-if="message.text" class="mb-5 text-white" :text="message.text" :color="message.type"></v-alert>
            <v-card class="elevation-12">
            <v-toolbar dark color="primary">
                <v-toolbar-title>Recevoir un nouveau mail de confirmation</v-toolbar-title>
            </v-toolbar>
            <v-card-text class="pa-20">
                <Form as="v-form" id="loginForm" :validation-schema="schema" @submit="handleSubmit">
                <Field name="email" v-slot="{ field, errors }">
                    <v-text-field v-bind="field" label="Email" :error-messages="errors">
                    <template v-slot:prepend>
                        <v-icon>mdi-account</v-icon>
                    </template>
                    </v-text-field>
                </Field>
                </Form>
            </v-card-text>
            <v-card-actions class="pa-20 justify-center">
                <v-progress-circular v-if="loading" indeterminate color="primary"></v-progress-circular>
                <v-btn v-if="!loading" color="primary" variant="tonal" type="submit" form="loginForm">Envoyer</v-btn>
            </v-card-actions>
            </v-card>
        </div>
  </v-layout>
</template>

<style scoped lang="scss">

</style>