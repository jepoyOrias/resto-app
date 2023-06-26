<script setup>
// import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <v-app>
        <v-main>
            <v-container class="fluid fill-height align-center justify-center" >
                <slot />
            </v-container>
        </v-main>
    </v-app>
</template>
