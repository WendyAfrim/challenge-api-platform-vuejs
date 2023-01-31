<script setup lang="ts">
import hero from '@/assets/hero.svg';
import type { Roles } from '@/enums/roles';
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
if (props.for === 'homeowner' && !heading) {
    heading = 'La mise en location simplifiée et de bout en bout.';
    highlighted_words = ['simplifiée', 'de bout en bout.'];
} else if (props.for === 'tenant' && !heading) {
    heading = 'La recherche de logement simplifiée et de bout en bout.';
    highlighted_words = ['simplifiée', 'de bout en bout.'];
} else {
    heading = props.heading || '';
    highlighted_words = props.highlighted_words || [];
}

const highlighted_heading = heading.replace(
    new RegExp(highlighted_words.join('|'), 'gi'),
    (match) => `<span class="span">${match}</span>`
);

const link = computed(() => {
    if (props.for === 'homeowner') {
        return { name: 'homeowner_signup' };
    } else if (props.for === 'tenant') {
        return { name: 'tenant_signup' };
    } else {
        return { name: 'home' };
    }
});

</script>

<template>
    <v-row align="center" justify="space-between">
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

.heading-sentence {
    :deep(span) {
        color: rgb(var(--v-theme-primary));
        font-weight: 900;
    }
}
</style>