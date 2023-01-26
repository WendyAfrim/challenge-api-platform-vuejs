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
    <v-form ref="form" v-model="valid" lazy-validation class="d-flex justify-center align-start flex-grow-1">
      <v-row class="d-flex justify-center align-start">
        <v-col cols="12" md="6">
          <v-text-field v-model="user.firstname" :rules="firstnameRules" label="Prénom" required></v-text-field>
          <v-text-field v-model="user.lastname" :rules="lastnameRules" label="Nom" required></v-text-field>
          <v-text-field v-model="user.email" :rules="emailRules" label="Email" required></v-text-field>
          <v-text-field v-model="user.password" :rules="passwordRules" label="Mot de passe" type="password"
            required></v-text-field>
          <v-text-field v-model="user.passwordConfirm" :rules="passwordConfirmRules"
            label="Confirmation du mot de passe" type="password" required></v-text-field>
        </v-col>
        <v-col cols="12" md="6">
          <template v-if="props.for === 'tenant'">
            <v-select v-model="user.situation" :items="situations" :rules="situationRules" label="Situation"
              required></v-select>
            <!-- <h2 class="text-h6 font-weight-bold mb-3">On gagne du temps ?</h2>
            <p class="text-body-2 mb-3">
              Gagnez du temps en nous envoyant vos documents. Nous les vérifierons et vous enverrons un email de
              confirmation.
              <v-list lines="one">
                <v-list-item v-for="item in documents" :key="item.name">
                  <v-list-item-title>{{ item.name }}</v-list-item-title>
                  <v-list-item-subtitle>{{ item.description }}</v-list-item-subtitle>
                </v-list-item>
              </v-list>
            </p>
            <v-file-input v-model="user.documents" color="deep-purple accent-4" counter label="Vos documents" multiple
              placeholder="Select your files" prepend-icon="mdi-paperclip" outlined :show-size="1000">
              <template v-slot:selection="{ fileNames }">
                <template v-for="fileName in fileNames" :key="fileName">
                  <v-chip size="small" label color="primary" class="me-2">
                    {{ fileName }}
                  </v-chip>
                </template>
              </template>
            </v-file-input> -->
          </template>
        </v-col>
        <v-col cols="12" class="d-flex justify-center">
          <v-btn color="primary" @click="register">S'inscrire</v-btn>
        </v-col>
      </v-row>
    </v-form>
  </v-container>
</template>

<script setup lang="ts">
import axios from 'axios';
import { ref, reactive } from 'vue';

const props = defineProps<{
  for: 'homeowner' | 'tenant';
}>();

const form = ref();

interface User {
  firstname: string;
  lastname: string;
  email: string;
  password: string;
  passwordConfirm: string;
  roles: string[];
  situation?: string;
  documents?: File[];
}

const user = reactive<User>({
  firstname: '',
  lastname: '',
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
const situations = [
  'Étudiant',
  'Salarié',
  'Indépendant',
  'Retraité',
  'Autre',
];
const documents = [
  {
    name: 'Justificatif de domicile',
    description: 'Moins de 3 mois',
  },
  {
    name: 'Justificatif de revenus',
    description: 'Doit être à jour et de moins de 3 mois',
  },
  {
    name: 'Justificatif d\'identité',
    description: 'Carte d\'identité ou passeport',
  },
  {
    name: 'Justificatif de situation',
    description: 'Étudiant : carte d\'étudiant, salarié : dernier bulletin de salaire, indépendant : dernier avis d\'imposition, retraité : dernier avis de retraite, autre : justificatif de situation (ex: attestation de chômage)',
  },
];

const valid = ref(false);
const firstnameRules = ref([
  (v: string) => !!v || 'Le prénom est requis',
]);
const lastnameRules = ref([
  (v: string) => !!v || 'Le nom est requis',
]);
const emailRules = ref([
  (v: string) => !!v || 'L\'email est requis',
  (v: string) => /.+@.+\..+/.test(v) || 'L\'email doit être valide',
]);
const passwordRules = ref([
  (v: string) => !!v || 'Le mot de passe est requis',
  (v: string) => v.length >= 8 || 'Le mot de passe doit contenir au moins 8 caractères',
]);
const passwordConfirmRules = ref([
  (v: string) => !!v || 'La confirmation du mot de passe est requise',
  (v: string) => v === user.password || 'Les mots de passe ne correspondent pas',
]);
const situationRules = ref([
  (v: string) => !!v || 'Votre situation est requise',
]);


const register = (event: MouseEvent) => {
  event.preventDefault();
  if (valid.value) {
    type FormUserData = Omit<User, "passwordConfirm" | "password"> & { plainPassword: string };
    const data: FormUserData = {
      firstname: user.firstname,
      lastname: user.lastname,
      email: user.email,
      plainPassword: user.password,
      roles: roles[props.for],
    };
    if (props.for === 'tenant') {
      data.situation = user.situation;
      // data.documents = user.documents;
    }
    axios.post(`${import.meta.env.VITE_BASE_API_URL}/users`, data)
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

<style scoped lang="scss">
.v-text-field {
  margin-bottom: .5rem;
}
</style>
