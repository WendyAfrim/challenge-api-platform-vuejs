<template>
    <v-layout class="d-flex flex-column justify-center items-center">
      <div class="ma-auto w-25">
        <v-alert v-if="message.text" class="mb-10 text-white" :color="message.type">
          {{ message.text }}
          <div v-if="errorType && errorType === 'not_verified_email'">
            <v-btn class="mt-4" color="white" @click="router.push({name: 'email_verification_new_link'})">Je n'ai pas reçu d'email</v-btn>
          </div>
        </v-alert>
        <v-card class="elevation-12">
          <v-toolbar dark color="primary">
            <v-toolbar-title>Connexion</v-toolbar-title>
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
              <Field name="password" v-slot="{ field, errors }">
                <v-text-field v-bind="field" label="Mot de passe" type="password" :error-messages="errors">
                  <template v-slot:prepend>
                    <v-icon>mdi-lock</v-icon>
                  </template>
                </v-text-field>
              </Field>
            </Form>
          </v-card-text>
          <v-card-actions class="pa-20 justify-center">
              <v-progress-circular v-if="loading" indeterminate color="primary"></v-progress-circular>
              <v-btn v-if="!loading" color="primary" variant="tonal" type="submit" form="loginForm">Se connecter</v-btn>
          </v-card-actions>
        </v-card>
      </div>
  </v-layout>
</template>

<script setup lang="ts">
  import { useRoute, useRouter } from "vue-router";
  import { ref } from "vue";
  import { useAuthStore } from '@/stores/auth.store';
  import { Field, Form } from 'vee-validate';
  import * as yup from 'yup';
import type { Roles } from "@/enums/roles";
  
  const router = useRouter();
  const route = useRoute();
  const forType = route.meta.forType as Roles;

  const loading = ref<Boolean>(false);
  const message = ref({
    text: '',
    type: ''
  })
  const errorType = ref('');

  const schema = yup.object({
    email: yup.string().email().required(),
    password: yup.string().min(3).required(),
  });

  const authStore = useAuthStore();

  async function handleSubmit(values: any) {
    loading.value = true;
    try {
      message.value.text = '';
      message.value.type = '';
      await authStore.login(values.email, values.password);
      await router.push({ name: `${forType}_dashboard`});
    } catch (error: any) {
        errorType.value = error.response.data.error_type || '';
      message.value.text = error.response.data.message || 'Une erreur est survenue. Veuillez réessayer.';
      message.value.type = 'error';
    }
    loading.value = false;
  }

  if (router.currentRoute.value.query.status === 'validated') {
    message.value.text = 'Votre email a bien été validé. Vous pouvez maintenant vous connecter.';
    message.value.type = 'info';
  } else if (router.currentRoute.value.query.status === 'error') {
    message.value.text = 'Une erreur est survenue lors de la validation de votre email. Veuillez réessayer.';
    message.value.type = 'error';
  } else if (router.currentRoute.value.query.status === 'expired') {
    message.value.text = 'Votre lien de validation a expiré. Un lien de validation vous a été renvoyé par email.';
    message.value.type = 'info';
  }
</script>

<style scoped lang="scss">
</style>
