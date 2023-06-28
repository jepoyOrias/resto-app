
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
                            <v-chip 
                            class="ma-2 bg-blue-darken-3"
                            @click="sortMenus">
                            All categories
                            </v-chip>
                            <v-chip 
                            class="ma-2 bg-blue-darken-3" 
                            v-for="category in props.categories" :key="category.id"
                            @click="sortMenus(category.id)"
                            >
                                {{ category.name }}
                            </v-chip>
                        </v-card-item>
                    </v-card>
                    <TodaysReservation  :todaysReservation="todaysReservation"/>
                </v-col>
            </v-row>
           
        </v-container>
    </AdminLayout>
</template>
<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import RecentlyMenuCard from '@/Components/RecentlyMenuCard.vue';
import TodaysReservation from '@/Components/Partials/Dashboard/TodaysReservation.vue';
import {onMounted, ref,reactive, watchEffect, watch} from 'vue';
import { Head,usePage } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';


const recentlyMenus = ref();
var  tempMenus = [];
watchEffect(()=>{
    tempMenus = props.recentlyMenus;
    tempMenus.forEach(element => {
        Object.assign(element, {"show":true});
    });
    recentlyMenus.value = tempMenus;
})





const props = defineProps({
    recentlyMenus: Object,
    categories: Object,
    todaysReservation: Object,
    sort: {
        type: String,
        default : ""
    }
});

function sortMenus(category_id = ''){
    router.visit('/admin',{
    method:'get',
    preserveState: true,
    only:['recentlyMenus','sort'],
    category_id: category_id,
     onSuccess: function(){
        console.log(props.sort);
     }
  }


  );

}





</script>


<style scoped>
.dashboard-wrapper {
    box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px !important;
}
</style>
