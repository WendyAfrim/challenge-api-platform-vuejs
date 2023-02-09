<template>
  <v-container>
    <template v-if="props.for === 'homeowner'">
      <h1 class="text-h4 font-weight-bold text-center mb-3">Créez votre compte et regroupez tous vos dossiers au même
        endroit.
      </h1>
    </template>
    <template v-else-if="props.for === 'tenant'">
      <h1 class="text-h4 font-weight-bold text-center mb-3">Créez votre compte et trouvez votre logement idéal en quelques
        clics.
      </h1>
    </template>
    <v-alert v-if="message.content" class="mb-10 text-white" :color="message.type">
      <div v-if="message.type === 'error'">
        <template v-for="(messages, field) in message.content" :key="field">
          <li>{{ field }}: </li>
          <template v-for="(message, index) in messages" :key="index">
            <li class="error-message pl-6"> - {{ message }} </li>
          </template>
        </template>
      </div>
      <div v-else>
        {{ message.content }}
      </div>
    </v-alert>
    <v-form ref="form" v-model="valid" lazy-validation class="d-flex justify-center align-start flex-grow-1">
      <v-row class="d-flex justify-center align-start">
        <v-col cols="12" md="6">
          <v-text-field v-model="user.email" :rules="emailRules" label="Email" required></v-text-field>
          <v-text-field v-model="user.password" :rules="passwordRules" label="Mot de passe" type="password"
            required></v-text-field>
          <v-text-field v-model="user.passwordConfirm" :rules="passwordConfirmRules"
            label="Confirmation du mot de passe" type="password" required></v-text-field>
        </v-col>
        <v-col cols="12" class="d-flex justify-center">
          <v-progress-circular v-if="loading" indeterminate color="primary"></v-progress-circular>
          <v-btn v-if="!loading" color="primary" @click="register">S'inscrire</v-btn>
        </v-col>
      </v-row>
    </v-form>
  </v-container>
</template>

<script setup lang="ts">
import type { Roles } from '@/enums/roles';
import { axios } from '@/services/auth';
import { ref, reactive } from 'vue';

const props = defineProps<{
  for: Roles;
}>();

const form = ref();

interface User {
  email: string;
  password: string;
  passwordConfirm: string;
  roles: string[];
  situation?: string;
  documents?: File[];
}

const user = reactive<User>({
  email: '',
  password: '',
  passwordConfirm: '',
  roles: [],
  situation: '',
  documents: [],
});

// @TODO: fetch from API
const roles = {
  homeowner: ['ROLE_HOMEOWNER'],
  tenant: ['ROLE_TENANT'],
}

const valid = ref(false);
const emailRules = ref([
  (v: string) => !!v || 'L\'email est requis',
  (v: string) => /.+@.+\..+/.test(v) || 'L\'email doit être valide',
]);
const passwordRules = ref([
  (v: string) => !!v || 'Le mot de passe est requis',
  (v: string) => v.length >= 3 || 'Le mot de passe doit contenir au moins 3 caractères',
]);
const passwordConfirmRules = ref([
  (v: string) => !!v || 'La confirmation du mot de passe est requise',
  (v: string) => v === user.password || 'Les mots de passe ne correspondent pas',
]);
const situationRules = ref([
  (v: string) => !!v || 'Votre situation est requise',
]);

const message = ref({
  content: null,
  type: ''
})

const loading = ref<Boolean>(false);

const register = async (event: MouseEvent) =>{
    event.preventDefault();
  loading.value = true;
  if (valid.value) {
    type FormUserData = Omit<User, "passwordConfirm" | "password"> & { plainPassword: string };
    const data: FormUserData = {
      email: user.email,
      plainPassword: user.password,
      roles: roles[props.for as keyof typeof roles],
    };
    if (props.for === 'tenant') {
      data.situation = user.situation;
    }
    try {
      const response = await axios.post(`${import.meta.env.VITE_BASE_API_URL}/register`, data, {headers: {'Content-Type': 'application/json'}});
      message.value.content = response.data.message;
      message.value.type = 'success';
    } catch (error: any) {
      message.value.content = error.response.data.errors;
      message.value.type = 'error';
      console.log(error);
    }
  } else {
    form.value.validate();
  }
  loading.value = false;
}
</script>

<style scoped lang="scss">
.v-text-field {
  margin-bottom: .5rem;
}

.error-message {
  list-style: none;
}
</style>
