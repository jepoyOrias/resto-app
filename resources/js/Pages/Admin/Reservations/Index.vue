<template>
    <AdminLayout>

        <v-container>
            <v-row class="mt-12 pt-12">
                <v-col cols="12" md="10" class="mx-auto">


                    <v-data-table-server v-model:items-per-page="props.reservations.per_page"
                        :items-length="props.reservations.total" :headers="headers" :items="props.reservations.data"
                        class="elevation-1" item-value="name" item-key="id" :loading="isLoading">

                        <template v-slot:top>
                            <v-toolbar>
                                <v-row>
                                    <v-col cols="6">
                                        <v-text-field v-model="searchReservation" hide-details placeholder="Search name..."
                                            class="ma-2 cursor-pointer" density="compact" background-color="primary"
                                            append-icon="mdi-magnify" color="primary" @input="searchInTable"></v-text-field>
                                    </v-col>
                                </v-row>

                                <v-divider vertical></v-divider>
                                <v-dialog v-model="dialog" persistent width="1024">
                                    <LoadingOverlay :loadingOverlay="formLoading" />
                                    <template v-slot:activator="{ props }">
                                        <v-btn color="primary" v-bind="props" prepend-icon="mdi-plus"
                                            @click="editMode = false" variant="flat" rounded="xl" class="mx-2">
                                            Add Reservation
                                        </v-btn>
                                    </template>
                                    <v-card>
                                        <v-card-title>
                                            <span class="text-h5">{{ editMode ? "Edit" : "Add" }} Reservation</span>
                                        </v-card-title>
                                        <v-card-text>
                                            <v-container>
                                                <div class="text-end text-red-600 my-3 text-caption font-bold">
                                                    {{ customError }}
                                                </div>
                                                <v-form ref="categoryForm">
                                                    <v-row>
                                                        <v-col cols="12">
                                                            <v-text-field v-model="formItem.first_name" label="First Name"
                                                                :error-messages="props.errors?.first_name"></v-text-field>
                                                        </v-col>
                                                        <v-col cols="12">
                                                            <v-text-field v-model="formItem.last_name" label="Last Name"
                                                                :error-messages="props.errors?.last_name"></v-text-field>
                                                        </v-col>
                                                        <v-col cols="12">
                                                            <v-text-field v-model="formItem.email" label="Email Address"
                                                                :error-messages="props.errors?.email"></v-text-field>
                                                        </v-col>
                                                        <v-col cols="12">
                                                            <v-text-field v-model="formItem.phone_number"
                                                                label="Phone Number"
                                                                :error-messages="props.errors?.phone_number"></v-text-field>
                                                        </v-col>
                                                        <v-col cols="12">
                                                            <v-text-field v-model="formItem.guest_number" type="number"
                                                                label="Guest Number"
                                                                :error-messages="props.errors?.guest_number"></v-text-field>
                                                        </v-col>
                                                        <v-col cols="12">
                                                            <v-combobox 
                                                                v-model="formItem.table_id" 
                                                                :items="props.tables"
                                                                label="Select Table" 
                                                                item-title="name"
                                                                :persistent-hint="true"
                                                                :hint="formItem.table_id?.guest_number == undefined ? 'Please choose a table':`The capactity of this table is ${formItem.table_id?.guest_number}`" 
                                                                :error-messages="props.errors?.table_id" :chips="true"
                                                                :clearable="true"
                                                                item-value="id"
                                                                :return-object="true"
                                                                @change="getAvailableTime">
                                                            </v-combobox>
                                                        </v-col>
                                                        <v-col cols="12">
                                                            <v-text-field type="date"
                                                                v-model="formItem.reservation_date"
                                                                :min="moment().format('YYYY-MM-DDTHH:mm:ss')"
                                                                :max="moment().add('7', 'days').format('YYYY-MM-DDTHH:mm:ss')"
                                                                label="Reservation Date"
                                                                :error-messages="props.errors?.reservation_date"
                                                                @change="getAvailableTime"></v-text-field>
                                                        </v-col>
                                                        <v-col cols="12">
                                                            <v-select
                                                                :disabled="availableTimes.length <= 0"
                                                                v-model="formItem.reservation_time" 
                                                                :items="availableTimes"
                                                                label="Select Time"
                                                                return-object
                                                                :error-messages="props.errors?.reservation_time"
                                                                @input="test"
                                                            >
                                                            <template v-slot:selection="{ item }">
                                                                {{ moment(item.raw.time ? item.raw.time : item.raw , "HH:mm:ss").format("hh:mm A") }}
                                                                <span>
                                                                    <v-chip
                                                                    class="caption ms-3"
                                                                                size="small"
                                                                                :color="item.raw.isReserved ? 'danger' : 'primary'"  
                                                                    >
                                                                        {{ item.raw.isReserved ? "Reserved": "available" }}
                                                                    </v-chip>
                                                                </span>
                                                            </template>
                                                            <template #item="{ item, props: {onClick, title, value} }" >
                                                                <v-list-item 
                                                                @click="onClick"
                                                                :class="item.raw.isReserved || item.raw.isAlreadyPassed? 'disabled' :''"> 
                                                                    <v-list-item-title>
                                                                        {{ item.value }}
                                                                        {{ moment(item.raw.time, "HH:mm:ss").format("hh:mm A") }}
                                                                       <span>
                                                                            <v-chip
                                                                                class="caption ms-3"
                                                                                size="small"
                                                                                :color="item.raw.isReserved ? 'danger' :  item.raw.isAlreadyPassed ?'warning':'primary'"
                                                                            >
                                                                                {{
                                                                                    item.raw.isReserved ? "Reserved":  
                                                                                    item.raw.isAlreadyPassed ? "The time is already passed" 
                                                                                    :"available" 
                                                                                }}
                                                                            </v-chip>
                                                                        </span>
                                                            </v-list-item-title>

                                                            </v-list-item>
                                                            </template>
                                                            </v-select>
                                                        </v-col>
                                                    </v-row>
                                                </v-form>
                                            </v-container>
                                        </v-card-text>
                                        <v-card-actions>
                                            <v-spacer></v-spacer>
                                            <v-btn color="blue-darken-1" variant="text" @click="cancelAddReservation">
                                                Close
                                            </v-btn>
                                            <v-btn color="blue-darken-1" variant="text" @click="PostReservation"
                                                v-show="!editMode">
                                                Save
                                            </v-btn>
                                            <v-btn color="blue-darken-1" variant="text" @click="UpdateReservation"
                                                v-show="editMode">
                                                Update
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-dialog>

                                <DeleteBanner :deleteDialog="dialogDelete" :isDeletingItem="isDeletingItem"
                                    @deleteItem="deleteItemConfirm" />
                            </v-toolbar>
                        </template>

                        <template v-slot:item.name="{ item }">
                            {{ item.raw.first_name }} {{ item.raw.last_name }}
                        </template>
                        <template v-slot:item.reservation_date="{ item }">
                            <div>
                                <v-chip
                                    class="ma-2"
                                    color="info"
                                    label
                                    text-color="white"
                                    >
                                    <v-icon start icon="mdi-calendar-badge"></v-icon>
                                    {{ moment(new Date(item.raw.reservation_date)).utc().format('ddd, MMMM DD YYYY') }}
                                </v-chip>
                                <v-chip
                                    class="ma-2 text-caption"
                                    color="primary"
                                    label
                                    text-color="white"

                                    >
                                    <v-icon start icon="mdi-clock-time-eight"></v-icon>
                                    {{ moment(item.raw.reservation_time, "HH:mm:ss").format("hh:mm A") }} to  
                                    {{ moment(item.raw.reservation_time, "HH:mm:ss").add('1','Hours').format("hh:mm A")}}
                                </v-chip>
                            </div>  
                            
                        </template>
                        <template v-slot:item.table="{ item }">
                            {{ item.raw.table.name }}
                        </template>
                        <template v-slot:item.actions="{ item }">
                            <v-icon size="small" class="me-2" color="info" @click="editItem(item.raw)">
                                mdi-pencil
                            </v-icon>
                            <v-icon size="small" color="error" @click="deleteMenuItem(item.raw)">
                                mdi-delete
                            </v-icon>
                        </template>


                        <template v-slot:no-data>
                            <td colspan="6" class="text-center py-5">
                                <p class="text-center my-5">
                                    <v-icon>mdi-alert-circle-outline</v-icon>
                                    No results found.
                                </p>
                            </td>

                        </template>

                        <template v-slot:bottom>
                            <Pagination :currentPage="currentPage" :totalPages="totalPages" @pageChange="paginate">
                            </Pagination>
                        </template>
                    </v-data-table-server>
                </v-col>
            </v-row>


        </v-container>

    </AdminLayout>
</template>

<script setup>
import { VDataTableServer } from 'vuetify/labs/VDataTable';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DeleteBanner from '@/Components/DeleteBanner.vue';
import LoadingOverlay from '@/Components/LoadingOverlay.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { ref, reactive, onMounted, watchEffect, computed } from 'vue';


import moment from 'moment';
import axios from 'axios';

const currentPage = ref(1);
const totalPages = ref(1);
const searchReservation = ref('');
const editMode = ref(false);
const categoryForm = ref(null);
const previewImage = ref(null);
const isLoading = ref(false);
const dialog = ref(false);
const categoryImage = ref();
const formLoading = ref(false);
const uploadFromLocal = ref(false);
const dialogDelete = ref(false);
const deleteID = ref(null);
const isDeletingItem = ref(false);
const customError = ref(null);
const availableTimes = ref([]);
onMounted(() => {
    searchReservation.value = props.queryString;
})



const props = defineProps({
    errors: Object,
    reservations: Object,
    tables: Object,
 
    queryString: {
        type: String,
        default: "",
    },
});


const formItem = reactive({
    id: null,
    name: null,
    first_name: null,
    last_name: null,
    email: null,
    guest_number: null,
    phone_number: null,
    reservation_date:null,
    reservation_time: null,
    table_id: null,
    currentTime : moment().format('HH:mm::ss'),
});


const headers = ref([
    { title: 'Name', key: 'name' },
    { title: '# of Guest', key: 'guest_number' },
    { title: 'Email', key: 'email' },
    { title: 'Phone', key: 'phone_number' },
    { title: 'Reservation Date', key: 'reservation_date' },
    { title: 'Table', key: 'table' },
    { title: 'Actions', key: 'actions' }
]);

watchEffect(function () {
    currentPage.value = props.reservations.current_page;
    totalPages.value = props.reservations.last_page;
    isLoading.value = isLoading.value;
    uploadFromLocal.value = uploadFromLocal.value;
    formItem.value  = formItem.value;
    customError.value = props.errors.guest_number;
    availableTimes.value = availableTimes.value;
});


const showImage = () => {
    return "/storage/";
}


function cancelAddReservation() {
    categoryForm.value.reset();
    categoryImage.value = "";
    dialog.value = false;
    customError.value = "";
}


const paginate = (page) => {
    currentPage.value = page
    fetchReservations();
}

const searchInTable = () => {

    setTimeout(() => {
        currentPage.value = 1;
        fetchReservations();
    }, 500)
}



const  getAvailableTime = ()=>{
    axios.get ('/reservations/availableTime',{
       method: 'get', // *GET, POST, PUT, DELETE, etc.
       headers: {
           'Content-Type': 'application/json',
         
           'Content-Type': 'application/x-www-form-urlencoded',
        },
        params:{
        'reservation_date': formItem.reservation_date,
        'table_id': formItem.table_id.id,
        'currentTime': formItem.currentTime,
        'id': formItem.id,
       }
    }).then((res)=>{
        availableTimes.value = res?.data?.availableTimes;
    });
}




function PostReservation(){
    router.post('/admin/reservations', formItem,
        {
        onBefore: () => {
            formLoading.value = true;
        },
        onError: (error) => {
            formLoading.value = false;
            customError.value = error.message;
            formItem.table_id = formItem.table_id;
        },
        onSuccess: () => {
            dialog.value = false;
            formLoading.value = false;
            editMode.value = true;
            categoryForm.value.reset();
            previewImage.value = "";

        }
    });

}
const fetchReservations = () => {
    router.visit('/admin/reservations', {
        method: 'get',
        preserveState: true,
        only: ['table', 'categories', 'queryString'],
        data: {
            query: searchReservation.value,
            page: currentPage.value,

        },
        onBefore: () => {
            isLoading.value = true;
        },
        onError: () => {
            formLoading.value = false;
        },
        onFinish: () => {
            isLoading.value = false;

        }
    });

}

const editItem = (item) => {
    Object.assign(formItem, item);
    formItem.selectedCategory = item.categories;
    formItem.reservation_date = moment(item.reservation_date).utc().format('YYYY-MM-DD');
    formItem.table_id = item.table;
    dialog.value = true;
    editMode.value = true;
    getAvailableTime();
}





function UpdateReservation() {
    router.put(`/admin/reservations/${formItem.id}`, formItem,
    {
        onBefore: () => {
            formLoading.value = true;
        },
        onError:()=>{   
            formLoading.value = false;
        },
        onSuccess: () => {
            dialog.value = false;
            formLoading.value = false;
            editMode.value = true;
            categoryForm.value.reset()
        }
    });
}
const deleteMenuItem = (item) => {
    deleteID.value = item.id;
    dialogDelete.value = true;
}

function deleteItemConfirm(isDeleting) {
    router.delete('/admin/reservations/' + deleteID.value, {
        onBefore: () => {
            isDeletingItem.value = isDeleting;
        },
        onSuccess: () => {
            dialogDelete.value = false;
            isDeletingItem.value = false;
        }
    });
}


const test = ()=>{

        console.log(test);
}


</script>

<style>

    .disabled {
      pointer-events:none;
      color: #bfcbd9;
      cursor: not-allowed;
      background-image: none;
      background-color: #eef1f6;
      border-color: #d1dbe5;   
    }
</style>