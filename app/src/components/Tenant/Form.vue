<script setup lang="ts">
import { ErrorMessage, Field, useField } from 'vee-validate';

import * as yup from 'yup';
import FormWizard from './FormWizard.vue';
import FormStep from './FormStep.vue';
import { updateUser } from '@/services/user';
import { ref, computed, watch } from 'vue';
import { WorkSituationEnum } from '@/enums/WorkSituation';
import { DOMDirectiveTransforms } from '@vue/compiler-dom';

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
const firstname = ref();
const lastname = ref();
const email = ref();
const rent_receipt = ref();

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
    email: yup.string().required().email().label('Adresse email'),
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
  try {
    console.log()
    const res = await updateUser(1, {
      firstname: formData.firstname,
      lastname: formData.lastname,
      email: formData.email,
      // salary: formData.salary,
      documents: [
        {
          name: formData.id_card[0].name,
          type: 'id_card',
        },
        {
          name: formData.rent_receipt[0].name,
          type: 'rent_receipt',
        },
        {
          name: `formData.${workDocumentType.value.name}[0].name`,
          type: workDocumentType.value.name,
        },
        {
          name: formData.pay_slip[0].name,
          type: 'pay_slip',
        },
        {
          name: formData.tax_notice[0].name,
          type: 'pay_slip',
        },
      ],
    });
    console.log(res);
  } catch (error) {
    console.log(error);
  }
  console.log(JSON.stringify(formData, null, 2));
}
</script>
<template>
  <div>
    <h1>Multi-step Form</h1>
    <FormWizard as="v-form" :validation-schema="schema" @submit="onSubmit">

      <FormStep>
        <h1>Mes informations</h1>
        <Field name="firstname" v-slot="{ field, errors }">
          <v-text-field v-bind="field" label="Prénom" :error-messages="errors" v-model="firstname" />
        </Field>
        <Field name="lastname" v-slot="{ field, errors }">
          <v-text-field v-bind="field" label="Nom de famille" :error-messages="errors" v-model="lastname" />
        </Field>
        <Field name="email" type="email" v-slot="{ field, errors }">
          <v-text-field v-bind="field" label="Adresse email" :error-messages="errors" v-model="email" />
        </Field>
      </FormStep>

      <FormStep>
        <h1>Je dépose ma pièce d'identité</h1>
        <Field name="id_card" type="file" v-slot="{ handleChange, value, errors }">
          <v-file-input :model-value="value" @update:modelValue="handleChange" label="Carte d'identité" :error-messages="errors" />
        </Field>
      </FormStep>

      <FormStep>
        <h1>Je dépose un justificatif de domicile</h1>
        <Field name="rent_receipt" type="file" v-slot="{ handleChange, value, errors }">
          <v-file-input :model-value="value" @update:modelValue="handleChange" label="Dernière quittance de loyer" :error-messages="errors" />
        </Field>
      </FormStep> -->

      <FormStep>
        <h1>Je dépose mon justificatif de situation professionnelle</h1>
          <span>Actuellement, je suis</span>
          <Field name="work_situation" type="select" v-slot="{ field, errors }" class="mb-15" >
            <v-select v-bind="field" label="Situation professionnelle" :error-messages="errors" :items="workSituations" item-title="label" item-value="value" v-model="workSituation" />
          </Field>
            <Field v-if="workSituation" :name="workDocumentType.name" type="file" v-slot="{ handleChange, value, errors }">
              <v-file-input :model-value="value" @update:modelValue="handleChange" :label="workDocumentType.label" :error-messages="errors" />
            </Field>
      </FormStep>

      <FormStep>
        <h1>Je partage et justifie mes revenus</h1>
        <div class="d-flex align-center text-h6 w-50">
          <span class="mr-4">Mon salaire est de </span>
          <Field name="salary" v-slot="{ field, errors }">
            <v-text-field v-bind="field" label="Salaire" type="number" :error-messages="errors" variant="underlined" v-model="salary" />
          </Field>
          <span>€</span>
        </div>
        <Field v-if="salary" name="pay_slip" type="file" v-slot="{ handleChange, value, errors }">
          <v-file-input :model-value="value" @update:modelValue="handleChange" label="Dernière fiche de paie" :error-messages="errors" />
        </Field>
      </FormStep>

      <FormStep>
        <h1>Je justifie ma situation fiscale</h1>
        <div class="d-flex align-center text-h6 w-50">
          <Field name="has_tax_notice" type="select" v-slot="{ field, errors }">
            <v-select v-bind="field" label="Situation fiscale" :error-messages="errors" :items="[{'label': 'Je dispose', 'value': 1}, {'label': 'Je ne dispose pas', 'value': 0}]" item-title="label" item-value="value" v-model="has_tax_notice" variant="underlined"  />
          </Field>
          <span class="mr-4">d'un avis d'imposition à mon nom</span>
        </div>
        <Field v-if="has_tax_notice" name="tax_notice" type="file" v-slot="{ handleChange, value, errors }">
          <v-file-input :model-value="value" @update:modelValue="handleChange" label="Dernier avis d'imposition" :error-messages="errors" />
        </Field>
      </FormStep>

        <!-- <div class="d-flex align-center text-h6 w-50">
          <Field name="has_guarantor" type="select" v-slot="{ field, errors }">
            <v-select v-bind="field" label="Situation fiscale" :error-messages="errors" :items="[{'label': 'Je n\'ajoute pas de garant', 'value': 1}, {'label': 'J\'ajoute un garant', 'value': 0}]" item-title="label" item-value="value" v-model="has_guarantor" variant="underlined"  />
          </Field>
          <span class="mr-4">à mon dossier</span>
          <Field v-if="has_guarantor" name="guarantor" type="file" v-slot="{ field, errors }">
            <v-file-input v-bind="field" label="Dernier avis d'imposition" :error-messages="errors" />
          </Field>
        </div> -->
      <!-- <FormStep>
        <Field name="id_card" type="file" v-slot="{ field, errors }">
          <v-file-input v-bind="field" label="Carte d'identité" :error-messages="errors" />
        </Field>
      </FormStep> -->
    </FormWizard>
  </div>
</template>

<style>
input,
select {
  margin: 10px 0;
  display: block;
}
</style>
