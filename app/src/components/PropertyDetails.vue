<script setup lang="ts">
const props = defineProps(
    {
      property: Object
    }
);

</script>

<template>
  <div id="container">
    <div>
      <v-carousel id="images_slider">
        <v-carousel-item
            v-for="(link,i) in props.property.photos"
            :key="i"
            :src="link"
            cover
        ></v-carousel-item>
      </v-carousel>
    </div>
    <v-card class="h-100">
      <div id="details-area" class=" w-100 row">
        <div>
          <v-list>
              <v-list-item class="text-h4">
                  <v-icon icon="mdi mdi-currency-eur"></v-icon>
                  {{props.property.price}}
              </v-list-item>
            <v-list-item class="text-h5">
              <span class="justify-end" v-if="props.property.type === 'House'"><v-icon icon="mdi mdi-home-outline"></v-icon></span>
              <span class="justify-end" v-if="props.property.type === 'Apartment'"><v-icon icon="mdi mdi-office-building-outline"></v-icon></span>
              <span class="justify-end">{{props.property.type}}</span>
            </v-list-item>
            <v-list-item-title class="text-h5 ml-8">
              {{props.property.title}}
            </v-list-item-title>
            <v-list-item class="text-h7">{{props.property.rooms}} pièces<v-icon icon="mdi mdi-floor-plan"></v-icon></v-list-item>
            <v-list-item class="text-h7">{{props.property.surface}} &#13217; <v-icon icon="mdi mdi-tape-measure"></v-icon></v-list-item>
            <v-list-item class="text-h7" v-if="props.property.state"> {{props.property.state}}<v-icon icon="mdi mdi-door-closed-lock"></v-icon></v-list-item>
            <v-list-item class="text-h7" v-if="props.property.has_elevator">Ascenseur <v-icon icon="mdi mdi-elevator"></v-icon></v-list-item>
              <v-list-item class="text-h7" v-if="props.property.has_balcony">Balcon <v-icon icon="mdi mdi-balcony"></v-icon></v-list-item>
              <v-list-item class="text-h7" v-if="props.property.has_parking">Parking <v-icon icon="mdi mdi-garage"></v-icon></v-list-item>
              <v-list-item class="text-h7" v-if="props.property.has_terrace">Terrasse <v-icon icon="mdi mdi-fence"></v-icon></v-list-item>
            <v-list-item class="text-h7" v-if="props.property.is_furnished">Meublé <v-icon icon="mdi mdi-sofa-single-outline"></v-icon></v-list-item>
            <v-list-item class="text-h7" v-if="props.property.has_cave">Cave <v-icon icon="mdi mdi-door-closed-lock"></v-icon></v-list-item>
            <v-list-item class="text-h7">{{props.property.address}} ({{props.property.zipcode}}), {{props.property.city}} {{props.property.country}}</v-list-item>
          </v-list>
        </div>
        <dev>
          <v-btn class="mr-5 mt-5 ml-auto" color="primary">Intéresse</v-btn>
        </dev>
      </div>
    </v-card>
  </div>
  <v-card-text class="mt-10">
    {{props.property.description}}
  </v-card-text>
</template>

<style scoped>
#details-area{
  display: grid;
  grid-template-columns: 3fr 1fr;
  gap: 30px;
}
#container{
  display: grid;
  grid-template-columns: 3fr 2fr;
  gap: 30px;
}
</style>