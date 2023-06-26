<template>
  <Link :href="item.route" class="text-decoration-none">
  <v-list-item :class="getItemClasses(item)" :value="item" :rounded="!rail ? 'shaped' : 'xl'">
    <template v-slot:prepend>
      <v-icon color="none">{{ item.icon }}</v-icon>
    </template>
    <v-list-item-title>
      {{ item.name }}
    </v-list-item-title>
  </v-list-item>
  </Link>
</template>

<script setup>
import { ref, watchEffect } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
  item: Object,
  rail: Boolean,
})

const page = usePage();

const currentRoute = ref('');
watchEffect(() => {
  currentRoute.value = page.props.ziggy.location;

});

const getItemClasses = (item) => {
  return {
    'v-list-item--active': props.item.route === currentRoute.value,
  };
};


</script>