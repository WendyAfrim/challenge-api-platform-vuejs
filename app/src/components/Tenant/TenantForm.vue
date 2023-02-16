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
import { DocumentTypeEnum } from '@/enums/DocumentTypeEnum';
import { enumKeys } from '@/enums/EnumHelper';

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

const response = await axios.get(`${import.meta.env.VITE_BASE_API_URL}/users/${authStore.user.id}`);
const user = response.data;
const done = ref(user.validationStatus === 'to_review');

const invalidDocuments = (user.documents.filter((document: any) => !document.isValid));
const invalidDocumentsTypes = invalidDocuments.map((document: any) => document.type);
const missingDocuments = [];
for (const value of enumKeys(DocumentTypeEnum)) {
  if (!user.documents.some((document: any) => document.type === DocumentTypeEnum[value])) {
      missingDocuments.push(DocumentTypeEnum[value]);
  }
}
const documentsTypes = invalidDocumentsTypes.concat(missingDocuments);
const stepsToComplete: any = [];

if (!user.firstname && !user.lastname) {
  stepsToComplete.push('infos');
}
for (const value of enumKeys(DocumentTypeEnum)) {
  documentsTypes.includes(DocumentTypeEnum[value]) && stepsToComplete.push(DocumentTypeEnum[value]);
}

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

const schema = computed(() => {
  const steps = documentsTypes.map((documentType: any) => {
    switch(documentType) {
      case 'identity':
        return yup.object({
          id_card: yup.mixed().required().label('Carte d\'identité')
            .test('maxFileSize','Le fichier est trop large', (value: any) => value ? value[0]?.size <= 1000000 : true)
            .test('fileType', "Format de fichier invalide", (value: any) => value ? SUPPORTED_FORMATS.includes(value[0]?.type) : true)
        });
      case 'address':
        return yup.object({
          rent_receipt: yup.mixed().required().label('Quittance de loyer')
            .test('maxFileSize','Le fichier est trop large', (value: any) => value ? value[0]?.size <= 1000000 : true)
            .test('fileType', "Format de fichier invalide", (value: any) => value ? SUPPORTED_FORMATS.includes(value[0]?.type) : true)
        });
      case 'professional':
        return yup.object({
          work_situation: yup.string().required().label('Situation professionnelle'),
          [`${workDocumentType.value.name}`]: yup.mixed().required().label('document de situation pro')
            .test('maxFileSize','Le fichier est trop large', (value: any) => value ? value[0]?.size <= 1000000 : true)
            .test('fileType', "Format de fichier invalide", (value: any) => value ? SUPPORTED_FORMATS.includes(value[0]?.type) : true)
        });
      case 'income':
        return yup.object({
          salary: yup.number().required().label('Salaire'),
          pay_slip: yup.mixed().required().label('Dernière fiche de paie')
            .test('maxFileSize','Le fichier est trop large', (value: any) => value ? value[0]?.size <= 1000000 : true)
            .test('fileType', "Format de fichier invalide", (value: any) => value ? SUPPORTED_FORMATS.includes(value[0]?.type) : true)
        });
      case 'tax_status': {
        let object: any = {
          has_tax_notice: yup.boolean().required().label('Avis d\'imposition'),
        };
        if (has_tax_notice.value) {
          object = {
            ...object,
            tax_notice: yup.mixed().required().label('Avis d\'imposition')
            .test('maxFileSize','Le fichier est trop large', (value: any) => value ? value[0]?.size <= 1000000 : true)
            .test('fileType', "Format de fichier invalide", (value: any) => value ? SUPPORTED_FORMATS.includes(value[0]?.type) : true)
          }
        }
        return yup.object(object);
      }
    }
  });
  if (stepsToComplete.includes('infos')) {
    steps.unshift(yup.object({
      firstname: yup.string().required().label('Prénom'),
      lastname: yup.string().required().label('Nom'),
    }));
  }
  return steps;
});

async function onSubmit(formData: any) {
  loading.value = true;
  try {
    if (documentsTypes.includes(DocumentTypeEnum.Identity)) {
      const identityPostData: any = {
        file: formData.id_card[0],
        name: formData.id_card[0].name,
        type: 'identity',
        user_id: authStore.user.id
      };
      identityPostData.id = invalidDocumentsTypes.includes(DocumentTypeEnum.Identity) ? invalidDocuments.find((document: any) => document.type === DocumentTypeEnum.Identity).id : null;
      console.log(identityPostData);
      await axios.post(`${import.meta.env.VITE_BASE_API_URL}/documents`, identityPostData, { headers: { 'Content-Type': 'multipart/form-data' }});
    }
    if (documentsTypes.includes(DocumentTypeEnum.Address)) {
      const addressPostData: any = {
        file: formData.rent_receipt[0],
        name: formData.rent_receipt[0].name,
        type: 'address',
        user_id: authStore.user.id
      };
      addressPostData.id = invalidDocumentsTypes.includes(DocumentTypeEnum.Address) ? invalidDocuments.find((document: any) => document.type === DocumentTypeEnum.Address).id : null;
      console.log(addressPostData);
      await axios.post(`${import.meta.env.VITE_BASE_API_URL}/documents`, addressPostData, { headers: { 'Content-Type': 'multipart/form-data' }});
    }
    if (documentsTypes.includes(DocumentTypeEnum.Professional)) {
      const professionalPostData: any = {
        file: formData[`${workDocumentType.value.name}`][0],
        name: formData[`${workDocumentType.value.name}`][0].name,
        type: 'professional',
        user_id: authStore.user.id
      };
      professionalPostData.id = invalidDocumentsTypes.includes(DocumentTypeEnum.Professional) ? invalidDocuments.find((document: any) => document.type === DocumentTypeEnum.Professional).id : null;
      console.log(professionalPostData);
      await axios.post(`${import.meta.env.VITE_BASE_API_URL}/documents`, professionalPostData, { headers: { 'Content-Type': 'multipart/form-data' }});
    }
    if (documentsTypes.includes(DocumentTypeEnum.Income)) {
      const incomePostData: any = {
        file: formData.pay_slip[0],
        name: formData.pay_slip[0].name,
        type: 'income',
        user_id: authStore.user.id
      };
      incomePostData.id = invalidDocumentsTypes.includes(DocumentTypeEnum.Income) ? invalidDocuments.find((document: any) => document.type === DocumentTypeEnum.Income).id : null;
      console.log(incomePostData);
      await axios.post(`${import.meta.env.VITE_BASE_API_URL}/documents`, incomePostData, { headers: { 'Content-Type': 'multipart/form-data' }});
    }
    if (documentsTypes.includes(DocumentTypeEnum.TaxStatus) && has_tax_notice.value) {
      const taxStatusPostData: any = {
        file: formData.tax_notice[0],
        name: formData.tax_notice[0].name,
        type: 'tax_status',
        user_id: authStore.user.id
      };
      taxStatusPostData.id = invalidDocumentsTypes.includes(DocumentTypeEnum.TaxStatus) ? invalidDocuments.find((document: any) => document.type === DocumentTypeEnum.TaxStatus).id : null;
      console.log(taxStatusPostData);
      await axios.post(`${import.meta.env.VITE_BASE_API_URL}/documents`, taxStatusPostData, { headers: { 'Content-Type': 'multipart/form-data' }});
    }
    const userPutData: any = { validationStatus: 'to_review' };
    if (stepsToComplete.includes('infos')) {
      userPutData.firstname = formData.firstname;
      userPutData.lastname = formData.lastname;
    }
    if (documentsTypes.includes(DocumentTypeEnum.Income)) {
      userPutData.salary = parseInt(formData.salary);
    }
    if (documentsTypes.includes(DocumentTypeEnum.Professional)) {
      userPutData.situation = formData.work_situation;
    }
      await axios.put(`${import.meta.env.VITE_BASE_API_URL}/users/${authStore.user.id}`, userPutData, {headers: {'Content-Type': 'application/json' }});
      done.value = true;
      authStore.user.validationStatus = 'to_review';
  } catch (error) {
    console.log(error);
  }
  loading.value = false;
}
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
    <v-progress-circular v-else-if="loading" indeterminate color="primary" class="ma-auto"></v-progress-circular>
    <FormWizard v-else :steps="stepsToComplete" class="w-75 ma-auto" as="v-form" :validation-schema="schema" @submit="onSubmit">

      <FormStep v-if="!user.firstname && !user.lastname">
        <h1 class="mb-10 text-h4 font-weight-bold">Mes informations</h1>
        <Field name="firstname" v-slot="{ field, errors }">
          <v-text-field v-bind="field" label="Prénom" :error-messages="errors" v-model="firstname" class="mb-6" />
        </Field>
        <Field name="lastname" v-slot="{ field, errors }">
          <v-text-field v-bind="field" label="Nom de famille" :error-messages="errors" v-model="lastname" />
        </Field>
      </FormStep>

      <FormStep v-if="documentsTypes.includes('identity')">
        <h1 class="mb-10 text-h4 font-weight-bold">Je dépose ma pièce d'identité</h1>
        <Field name="id_card" type="file" v-slot="{ handleChange, value, errors }" v-model="selectedFile">
          <v-file-input :model-value="value" @update:modelValue="handleChange" label="Carte d'identité" :error-messages="errors" />
        </Field>
      </FormStep>

      <FormStep v-if="documentsTypes.includes('address')">
        <h1 class="mb-10 text-h4 font-weight-bold">Je dépose un justificatif de domicile</h1>
        <Field name="rent_receipt" type="file" v-slot="{ handleChange, value, errors }">
          <v-file-input :model-value="value" @update:modelValue="handleChange" label="Dernière quittance de loyer" :error-messages="errors" />
        </Field>
      </FormStep>

      <FormStep v-if="documentsTypes.includes('professional')">
        <h1 class="mb-10 text-h4 font-weight-bold">Je dépose mon justificatif de situation professionnelle</h1>
        <span>Actuellement, je suis</span>
        <Field name="work_situation" type="select" v-slot="{ field, errors }" class="mb-15" >
          <v-select v-bind="field" label="Situation professionnelle" :error-messages="errors" :items="workSituations" item-title="label" item-value="value" v-model="workSituation" class="mb-6" />
        </Field>
        <Field v-if="workSituation" :name="workDocumentType.name" type="file" v-slot="{ handleChange, value, errors }">
          <v-file-input :model-value="value" @update:modelValue="handleChange" :label="workDocumentType.label" :error-messages="errors" />
        </Field>
      </FormStep>

      <FormStep v-if="documentsTypes.includes('income')">
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

      <FormStep v-if="documentsTypes.includes('tax_status')">
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

<style scoped>
input,
select {
  margin: 10px 0;
  display: block;
}
</style>
