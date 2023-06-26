
<template>
    <Head title="Dashboard" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Welcome to dashboard</h2>
        </template>
        <v-container>
            <v-row class="mt-12">
                <v-col cols="12" lg="8" class="">
                    <RecentlyMenuCard :menuItem="recentlyMenus"/>
                </v-col>
                <v-col cols="12" lg="4" class="mt-2">
                    <v-card elevation="12" class="mt-12">
                        <v-card-title>
                            Categories
                        </v-card-title>
                        <v-card-item>
                            <v-chip class="ma-2 bg-blue-darken-3" v-for="category in props.categories" :key="category.id">
                                {{ category.name }}
                            </v-chip>
                        </v-card-item>
                    </v-card>
                </v-col>
            </v-row>
           
        </v-container>
    </AdminLayout>
</template>
<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import RecentlyMenuCard from '@/Components/RecentlyMenuCard.vue';
import {onMounted, ref,reactive, watchEffect, watch} from 'vue';
import { Head,usePage } from '@inertiajs/vue3';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

Pusher.logToConsole = true;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '56fad58ba6dde752b3f0',
    cluster: 'ap1',
    // Add other relevant configuration options
});

window.Echo.channel('reserved-tables')
            .listen('ViewReserveDateTime', (event) => {
                // Handle the event and update the data
                    console.log(data);
            });

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     // Add other configuration options as needed
// });


const page = usePage();
console.log(page.props);
const recentlyMenus = reactive([]);

onMounted (()=>{
    
    var tempMenus =  props.recentlyMenus
    tempMenus.forEach(element => {
        Object.assign(element, {"show":true});
    });
    
    Object.assign(recentlyMenus, tempMenus);
})
const props = defineProps({
    recentlyMenus: Object,
    categories: Object,
    reservations: Object,
});

watchEffect(()=>{
    console.log(props.reservations)
})


</script>


<style scoped>
.dashboard-wrapper {
    box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px !important;
}
</style>
