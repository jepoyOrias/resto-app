<script setup>
import Pusher from 'pusher-js';
import { onMounted, ref } from 'vue'
import Hero from '@/Components/Landing/Hero.vue';
import Menus from '@/Components/Landing/Menus.vue';
import Feedback from '@/Components/Landing/Feedback.vue';
import { Head, Link } from '@inertiajs/vue3';
// Pusher.logToConsole = true;


var pusher = new Pusher('f83e8c3a49ae8868e487', {
    cluster: 'ap1'
});

var channel = pusher.subscribe('reserved-tables');
channel.bind('ReservedTable', function (data) {
    console.log(JSON.stringify(data.data));
});

const colors = ref([
    'indigo',
    'warning',
    'pink darken-2',
    'red lighten-1',
    'deep-purple accent-4',
]);
const slides = ([
    'First',
    'Second',
    'Third',
    'Fourth',
    'Fifth',
]);
defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="CrunchCrave" />
    <v-app>
        <v-app-bar elevation="0" class="bg-teal-darken-2 px-12" permanent>
            <div>
                <v-img src="/Images/logo.png" width="60" height="60"></v-img>
            </div>
            <v-app-bar-title class="py-5 mx-0 d-flex  align-center w-100">
                runchCrave
            </v-app-bar-title>
            <v-spacer></v-spacer>
            <div v-if="canLogin" class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
                <Link v-if="$page.props.auth.user && $page.props.auth.user.is_admin" :href="route('admin.index')"
                    class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                Dashboard</Link>

                <template v-else>
                    <Link :href="route('login')"
                        class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                    <v-btn primary class="text-white bg-primary">Login</v-btn>
                    </Link>
                    <Link v-if="canRegister" :href="route('register')">
                    <v-btn class="text-white">Register</v-btn>
                    </Link>
                </template>
            </div>
        </v-app-bar>
        <v-main>
            <Hero/>
            <Menus class="py-16 my-16"/>
            <Feedback  class="py-16 my-16"/>
        </v-main>
    </v-app>
</template>


<style>
.carousel-btn-prev {
    transform: rotate(-135deg) !important;
}

.carousel-btn-next {
    transform: rotate(45deg) !important;
}

.bg-wave {
    background: url('/Images/wave.svg') !important;
    background-size: cover !important;
    background-repeat: no-repeat;
    height: 100%;
}
@media screen and (min-width: 1600px) {
    .v-container {
        max-width: 1440px;

    }
    
}
</style>