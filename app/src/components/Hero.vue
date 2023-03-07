<script setup lang="ts">
import hero from '@/assets/hero.svg';
import { Roles } from '@/enums/roles';
import { computed } from 'vue';


export interface IHero {
    for?: Roles;
    heading?: string;
    highlighted_words?: string[];
    image?: string;
}

const props = withDefaults(defineProps<IHero>(), {
    image: hero,
});

let heading = props.heading || '';
let highlighted_words = props.highlighted_words || [];
if (props.for === Roles.Homeowner && !heading) {
    heading = 'La mise en location simplifiée et de bout en bout.';
    highlighted_words = ['simplifiée', 'de bout en bout.'];
} else if (props.for === Roles.Tenant && !heading) {
    heading = 'La recherche de logement simplifiée et de bout en bout.';
    highlighted_words = ['simplifiée', 'de bout en bout.'];
} else {
    heading = props.heading || '';
    highlighted_words = props.highlighted_words || [];
}

const highlighted_heading = heading.replace(
    new RegExp(highlighted_words.join('|'), 'gi'),
    (match) => `<span>${match}</span>`
);

const link = computed(() => {
    if (props.for === Roles.Homeowner) {
        return { name: `${Roles.Homeowner}_signup` };
    } else if (props.for === Roles.Tenant) {
        return { name: `${Roles.Tenant}_signup` };
    } else {
        return { name: 'home' };
    }
});

</script>

<template>
    <v-row align="center" justify="space-between" no-gutters>
        <v-col>
            <h1 class="text-h2 font-weight-black heading-sentence" v-html="highlighted_heading"></h1>
            <v-btn color="primary" class="mt-6" :to="link">
                Commencer
            </v-btn>
        </v-col>
        <v-col>
            <img :src="image" alt="hero image" />
        </v-col>
    </v-row>
</template>

<style scoped lang="scss">
.v-col {
    margin-left: 8rem;
    padding: 0;

    img {
        max-width: 100%;
        max-height: 100%;
    }
}
</style>