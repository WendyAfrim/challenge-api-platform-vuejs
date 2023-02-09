<script setup lang="ts">
import { Field } from 'vee-validate';

import * as yup from 'yup';
import FormWizard from './FormWizard.vue';
import FormStep from './FormStep.vue';
import { ref, computed, watch } from 'vue';
import { WorkSituationEnum } from '@/enums/WorkSituation';
import { useAuthStore } from '@/stores/auth.store';
import { axios } from '@/services/auth';
import { useDisplay } from 'vuetify/lib/framework.mjs';
import { useRouter } from 'vue-router';

const SUPPORTED_FORMATS = [
  "image/jpg",
  "image/jpeg",
  "application/pdf",
];

const workDocument = ref();
const workDocumentType = ref({ label: '', name: 'work_document' });
const workSituation = ref();
const salary = ref();
const has_tax_notice = ref();
const selectedFile = ref();
const authStore = useAuthStore();
const router = useRouter();
const loading = ref(false);
const firstname = ref();
const lastname = ref();
const done = ref(false);

const workSituations = ref([
  { label: 'Salarié dans le privé', value: 'employee' },
  { label: 'Agent de la fonction publique', value: 'public_official' },
  { label: 'Alternant', value: 'alternating_student' },
  { label: 'Etudiant', value: 'student' },
]);

watch(workSituation, (newValue) => {
  workDocument.value = null;
  switch(newValue) {
    case WorkSituationEnum.Employee:
      workDocumentType.value = { label: 'Contrat de travail', name: 'work_contract' };
      break;
    case WorkSituationEnum.PublicOfficial:
      workDocumentType.value = { label: 'Arrete de nomination', name: 'appointment_order' };
      break;
    case WorkSituationEnum.AlterningStudent:
      workDocumentType.value = { label: 'Contrat d\'alternance', name: 'work_study_contract' };
      break;
    case WorkSituationEnum.Student:
      workDocumentType.value = { label: 'Certificat de scolarite', name: 'school_certificate' };
      break;
    default:
      workDocumentType.value = { label: '', name: 'work_document' };
  }
});

yup.addMethod(yup.mixed, 'fileType', function(supportedFormats) {
    return this.test('fileType', "Format de fichier invalide", value => value ? supportedFormats.includes(value[0].type) : true);
});

yup.addMethod(yup.mixed, 'maxFileSize', function(maxFileSize = 1000000) {
  return this.test("maxFileSize", "Le fichier est trop large", (value) => value ? value[0].size <= maxFileSize : true);
});

const schema = computed(() => {
  return [
  yup.object({
    firstname: yup.string().required().label('Prénom'),
    lastname: yup.string().required().label('Nom de famille'),
    // email: yup.string().required().email().label('Adresse email'),
  }),
  yup.object({
    id_card: yup.mixed().required().maxFileSize().fileType(SUPPORTED_FORMATS).label('Carte d\'identité'),
  }),
  yup.object({
    rent_receipt: yup.mixed().required().maxFileSize().fileType(SUPPORTED_FORMATS).label('Quittance de loyer'),
  }),
  yup.object({
    work_situation: yup.string().required().label('Situation professionnelle'),
    [`${workDocumentType.value.name}`]: yup.mixed().required().maxFileSize().fileType(SUPPORTED_FORMATS).label('document de situation pro'),
  }),
  yup.object({
    salary: yup.number().required().label('Salaire'),
    pay_slip: yup.mixed().required().maxFileSize().fileType(SUPPORTED_FORMATS).label('Dernière fiche de paie'),
  }),
];
});
/**
 * Only Called when the last step is submitted
 */
async function onSubmit(formData) {
  loading.value = true;
  try {
      await axios.post('https://localhost/documents', {
        file: formData.id_card[0],
        name: formData.id_card[0].name,
        type: 'identity',
        user_id: authStore.user.id
      }, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });
      await axios.post('https://localhost/documents', {
        file: formData.rent_receipt[0],
        name: formData.rent_receipt[0].name,
        type: 'address',
        user_id: authStore.user.id
      }, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });
      await axios.post('https://localhost/documents', {
        file: formData[`${workDocumentType.value.name}`][0],
        name: formData[`${workDocumentType.value.name}`][0].name,
        type: `professional`,
        user_id: authStore.user.id
      }, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });
      await axios.post('https://localhost/documents', {
        file: formData.pay_slip[0],
        name: formData.pay_slip[0].name,
        type: 'tax_status',
        user_id: authStore.user.id
      }, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });
      await axios.put(`https://localhost/users/${authStore.user.id}`, {
        firstname: formData.firstname,
        lastname: formData.lastname,
        salary: formData.salary,
        situation: formData.work_situation,
        validationStatus: 'to_review'
      }, {
        headers: {
          'Content-Type': 'application/json'
        }
      });
      done.value = true;
      authStore.user.validationStatus = 'to_review';
  } catch (error) {
    console.log(error);
  }
  loading.value = false;
  console.log(JSON.stringify(formData, null, 2));
}
const { lgAndUp } = useDisplay();
</script>
<template>
    <div v-if="done">
      <v-card class="mx-auto" max-width="500">
        <v-card-title class="text-h5 font-weight-bold">Votre demande a bien été envoyée</v-card-title>
        <v-card-text>
          <p class="text-body-1">Votre demande est en cours de validation. Vous recevrez un email dès que votre demande aura été traitée.</p>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="router.push({ name: 'tenant_dashboard' })">Fermer</v-btn>
        </v-card-actions>
      </v-card>
    </div>
    <v-progress-circular v-else-if="loading" indeterminate color="primary" class="m-auto"></v-progress-circular>
    <FormWizard class="ma-auto" v-else as="v-form" :validation-schema="schema" @submit="onSubmit" :class="[lgAndUp ? 'w-50' : 'w-75']">

      <template #stepper_name_1>Mes informations</template>
      <FormStep>
        <h1 class="mb-10 text-h4 font-weight-bold">Mes informations</h1>
        <Field name="firstname" v-slot="{ field, errors }">
          <v-text-field v-bind="field" label="Prénom" :error-messages="errors" v-model="firstname" class="mb-6" />
        </Field>
        <Field name="lastname" v-slot="{ field, errors }">
          <v-text-field v-bind="field" label="Nom de famille" :error-messages="errors" v-model="lastname" />
        </Field>
      </FormStep>

      <template #stepper_name_2>Identité</template>
      <FormStep>
        <h1 class="mb-10 text-h4 font-weight-bold">Je dépose ma pièce d'identité</h1>
        <Field name="id_card" type="file" v-slot="{ handleChange, value, errors }" v-model="selectedFile">
          <v-file-input :model-value="value" @update:modelValue="handleChange" label="Carte d'identité" :error-messages="errors" />
        </Field>
      </FormStep>

      <template #stepper_name_3>Domicile</template>
      <FormStep>
        <h1 class="mb-10 text-h4 font-weight-bold">Je dépose un justificatif de domicile</h1>
        <Field name="rent_receipt" type="file" v-slot="{ handleChange, value, errors }">
          <v-file-input :model-value="value" @update:modelValue="handleChange" label="Dernière quittance de loyer" :error-messages="errors" />
        </Field>
      </FormStep>

      <template #stepper_name_4>Situation professionnelle</template>
      <FormStep>
        <h1 class="mb-10 text-h4 font-weight-bold">Je dépose mon justificatif de situation professionnelle</h1>
        <span>Actuellement, je suis</span>
        <Field name="work_situation" type="select" v-slot="{ field, errors }" class="mb-15" >
          <v-select v-bind="field" label="Situation professionnelle" :error-messages="errors" :items="workSituations" item-title="label" item-value="value" v-model="workSituation" class="mb-6" />
        </Field>
        <Field v-if="workSituation" :name="workDocumentType.name" type="file" v-slot="{ handleChange, value, errors }">
          <v-file-input :model-value="value" @update:modelValue="handleChange" :label="workDocumentType.label" :error-messages="errors" />
        </Field>
      </FormStep>

      <template #stepper_name_5>Ressources</template>
      <FormStep>
        <h1 class="mb-10 text-h4 font-weight-bold">Je partage et justifie mes revenus</h1>
        <div class="d-flex align-center justify-start text-h6 mb-6">
          <span class="mr-4">Mon salaire est de </span>
          <Field name="salary" v-slot="{ field, errors }">
            <v-text-field v-bind="field" label="Salaire" type="number" :error-messages="errors" variant="underlined" v-model="salary"/>
          </Field>
          <span>€</span>
        </div>
        <Field v-if="salary" name="pay_slip" type="file" v-slot="{ handleChange, value, errors }">
          <v-file-input :model-value="value" @update:modelValue="handleChange" label="Dernière fiche de paie" :error-messages="errors" />
        </Field>
      </FormStep>

      <template #stepper_name_6>Impôts</template>
      <FormStep>
        <h1 class="mb-10 text-h4 font-weight-bold">Je justifie ma situation fiscale</h1>
        <div class="d-flex align-center text-h6 mb-6">
          <Field name="has_tax_notice" type="select" v-slot="{ field, errors }">
            <v-select v-bind="field" label="Situation fiscale" :error-messages="errors" :items="[{'label': 'Je dispose', 'value': 1}, {'label': 'Je ne dispose pas', 'value': 0}]" item-title="label" item-value="value" v-model="has_tax_notice" variant="underlined"  />
          </Field>
          <span class="ml-6">d'un avis d'imposition à mon nom</span>
        </div>
        <Field v-if="has_tax_notice" name="tax_notice" type="file" v-slot="{ handleChange, value, errors }">
          <v-file-input :model-value="value" @update:modelValue="handleChange" label="Dernier avis d'imposition" :error-messages="errors" />
        </Field>
      </FormStep>
    </FormWizard>
</template>

<style>
input,
select {
  margin: 10px 0;
  display: block;
}
</style>
