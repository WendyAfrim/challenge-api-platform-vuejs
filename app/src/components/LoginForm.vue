<template>
    <v-layout class="d-flex flex-column justify-center items-center">
      <div class="ma-auto w-25">
        <v-alert v-if="message.text" class="mb-10 text-white" :text="message.text" :color="message.type"></v-alert>
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
  import { useRouter } from "vue-router";
  import { ref } from "vue";
  import { useAuthStore } from '@/stores/auth.store';
  import { Field, Form } from 'vee-validate';
  import * as yup from 'yup';
  
  const router = useRouter();
  const loading = ref<Boolean>(false);
  const message = ref({
    text: '',
    type: ''
  })

  const schema = yup.object({
    email: yup.string().email().required(),
    password: yup.string().min(4).required(),
  });

  const authStore = useAuthStore();

  async function handleSubmit(values: any) {
    loading.value = true;
    try {
      message.value.text = '';
      message.value.type = '';
      await authStore.login(values.email, values.password);
      await router.push({ name: 'tenant_dashboard'});
    } catch (error: any) {
      message.value.text = (error as Error).message;
      message.value.type = 'error';
    }
    loading.value = false;
  }
</script>

<style scoped lang="scss">
</style>
