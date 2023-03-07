import { createApp } from "vue";
import { createPinia } from "pinia";
import Vue3Lottie from 'vue3-lottie'
import 'vue3-lottie/dist/style.css'

import App from "./App.vue";
import router from "./router";

// Vuetify
import "vuetify/styles";
import { createVuetify } from "vuetify";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";
import { mdi } from 'vuetify/iconsets/mdi'
import '@mdi/font/css/materialdesignicons.css';
import { configure } from 'vee-validate';
import { createI18n } from 'vue-i18n'
import "./assets/main.css";

const vuetify = createVuetify({
  components,
  directives,
  icons: {
    defaultSet: "mdi",
    sets: {
      mdi,
    },
  },
});

const messages = {
  fr: {
    document_type: {
      infos: 'Informations',
      identity: 'Identité',
      address: 'Domiciliation',
      professional: 'Situation professionnelle',
      income: 'Ressources',
      tax_status: 'Situation fiscale',
    },
    validation_status: {
      to_complete: 'À compléter',
      to_review: 'À valider',
      validated: 'Validé',
    },
    work_situation: {
      employee: 'Salarié',
      student: 'Étudiant',
      public_official: 'Fonctionnaire',
      alternating_student: 'Alternant',
    },
    request_status: {
      pending: 'En attente',
      accepted: 'Accepté',
      rejected: 'Refusé',
      assignment: 'En attente d\'assignation',
    },
  }
}

const i18n = createI18n({
  locale: 'fr',
  messages,
})

configure({
  validateOnModelUpdate: false,
});

const app = createApp(App);

app.use(createPinia());
app.use(Vue3Lottie);
app.use(router);
app.use(vuetify);
app.use(i18n);

app.mount("#app");
