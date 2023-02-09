import { createApp } from "vue";
import { createPinia } from "pinia";

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
      identity: 'Identité',
      address: 'Domiciliation',
      professional: 'Situation professionnelle',
      income: 'Ressources',
      tax_status: 'Situation fiscale',
    }
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
app.use(router);
app.use(vuetify);
app.use(i18n);

app.mount("#app");
