<template>
  <v-card class="mt-5" elevation="12" height="680">
    <v-layout>
      <v-app-bar color="teal-darken-2" class="py-4 py-lg-0">
        <v-app-bar-title>
          Today's Reservations
          <v-chip class="caption me-5">{{ moment().format('MMMM, DD YYYY') }}</v-chip>
        </v-app-bar-title>
       
      </v-app-bar>

      <v-main class="overflow-y-auto">
        <v-container class="reservation-item">
          <v-list lines="three">
            <v-list-item v-for="reservation in todaysReservation" :key="reservation.id">
              <template v-slot:prepend>
                <v-avatar color="grey-lighten-1">
                  <v-img :src="showImage() + reservation.table.image" />
                </v-avatar>
              </template>
              <v-list-item-title class="mt-1 text-capitalize">
                {{ reservation.first_name + ' ' + reservation.last_name }}
              </v-list-item-title>
              <v-list-item-subtitle class="mt-1">
                ( {{ reservation.table.name }} )
                <span>at {{ moment(reservation.reservation_time, "HH:mm:ss").format('hh:mm A') }} to
                  {{ moment(reservation.reservation_time, "HH:mm:ss").add('1', 'Hours').format('hh:mm A') }}
                </span>
              </v-list-item-subtitle>
              <template v-slot:append>
                <v-btn color="grey-lighten-1" icon="mdi-information" variant="text"></v-btn>
              </template>
            </v-list-item>
          </v-list>
        </v-container>
      </v-main>
    </v-layout>
  </v-card>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import moment from 'moment';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: import.meta.env.VITE_PUSHER_APP_KEY,
  cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
  wsHost: window.location.hostname,
  wsPort: 6001,
  forceTLS: false,
  disableStatus: true,
  // enabledTransports: ['ws', 'wss'],
});

const todaysReservation = ref();

window.Echo.channel('reserved-tables')
  .listen('.ReservedTable', (event) => {
    todaysReservation.value = event.data
    console.log(todaysReservation.value);
  })





const props = defineProps({
  todaysReservation: Object,
});

const showImage = () => {
  return "/storage/";
}


onMounted(() => {
  todaysReservation.value = props.todaysReservation;
  console.log(todaysReservation.value);

});



</script>

<style>
.reservation-item {
  max-height: 500px;
  overflow-y: auto;
}
.v-toolbar-title__placeholder{
  overflow: initial;
}
.v-toolbar-title__placeholder {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  gap: 10px;
}
</style>