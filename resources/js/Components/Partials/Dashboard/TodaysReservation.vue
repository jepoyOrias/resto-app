<template>
    <v-card
    class="mt-5"
    elevation="12"
    max-height="500"
    >
      <v-toolbar color="white pt-3">
        <v-toolbar-title>
            Todays Reservation
            <v-spacer></v-spacer>
            <v-chip class="caption">{{ moment().format('MMMM, DD YYYY') }}</v-chip>
            
        </v-toolbar-title>
      </v-toolbar>
      <v-list lines="three">
      <v-list-item
        v-for="reservation in todaysReservation"
        :key="reservation.id"
      >
        <template v-slot:prepend>
          <v-avatar color="grey-lighten-1">
           <v-img :src="showImage() + reservation.table_image"/>
          </v-avatar>
        </template>
        <v-list-item-title class="mt-1 text-capitalize">
            {{ reservation.first_name +' '+ reservation.last_name }}
        </v-list-item-title>
        <v-list-item-subtitle class="mt-1">
            {{ reservation.table_name }}
        </v-list-item-subtitle>
        <template v-slot:append>
          <v-btn
            color="grey-lighten-1"
            icon="mdi-information"
            variant="text"
          ></v-btn>
        </template>
      </v-list-item>
      </v-list>
    </v-card>
  </template>

<script setup>
import {ref,onMounted} from 'vue';
import moment from 'moment';

import Pusher from 'pusher-js';
const  todaysReservation = ref();


const props =defineProps({
    todaysReservation: Object,
});

const showImage = () =>{
    return "/storage/";
}


onMounted(()=>{
    todaysReservation.value = props.todaysReservation;
    
});


var pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY ,{
      cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER
    });

var channel = pusher.subscribe('reserved-tables');
channel.bind('ReservedTable', function(data) {
    todaysReservation.value = data.data;
    console.log(todaysReservation.value);
});
</script>

<style>

</style>