<script setup>
import Pusher from 'pusher-js';
import { Head, Link } from '@inertiajs/vue3';

// Pusher.logToConsole = true;

var pusher = new Pusher('f83e8c3a49ae8868e487', {
  cluster: 'ap1'
});

var channel = pusher.subscribe('reserved-tables');
    channel.bind('ReservedTable', function(data) {
                console.log(JSON.stringify(data.data));
});

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
    <Head title="Welcome" />
    <v-app>
        <v-app-bar title="Welcome">
            <v-spacer></v-spacer>
            <div v-if="canLogin" class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
            <Link
                v-if="$page.props.auth.user && $page.props.auth.user.is_admin"
                :href="route('admin.index')"
                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >Dashboard</Link
            >

            <template v-else>
                <Link
                    :href="route('login')"
                    class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >
                    <v-btn>Login</v-btn>
                    </Link
                >
                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >
                    <v-btn>Register</v-btn>
                    </Link
                >
            </template>
        </div>
        </v-app-bar>
   
    </v-app>

    
</template>
