<template>
    <AdminLayout>
        
        <Alert :message="message" :snackbar="snackbar"/>
        <v-container >
            <v-row class="mt-12 pt-12">
                <v-col cols="12" md="10" class="mx-auto">
                    <v-data-table-server v-model:items-per-page="props.table.per_page"
                        :items-length="props.table.total" :headers="headers" :items="props.table.data"
                        class="elevation-1" item-value="name" item-key="id" :loading="isLoading">

                        <template v-slot:top>
                            <v-toolbar>
                                <v-row>
                                    <v-col cols="6">
                                        <v-text-field v-model="searchTable" hide-details placeholder="Search name..."
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
                                            Add Table
                                        </v-btn>
                                    </template>
                                    <v-card>
                                        <v-card-title>
                                            <span class="text-h5">{{ editMode ? "Edit" : "Add" }} Table</span>
                                        </v-card-title>
                                        <v-card-text>
                                            <v-container>
                                                <v-form ref="tableForm">
                                                    <v-row>
                                                        <v-col cols="12">
                                                            <v-text-field v-model="formItem.name" label="Table Name"
                                                                :error-messages="props.errors?.name"></v-text-field>
                                                        </v-col>
                                                        <v-col cols="12">
                                                            <v-text-field 
                                                             v-model="formItem.guest_number"
                                                             type="number"
                                                             label="Guest Number"
                                                             :error-messages="props.errors?.name"></v-text-field>
                                                        </v-col>
                                                        <v-col cols="12">
                                                            <v-combobox 
                                                                v-model="formItem.status"
                                                                :items="props.tableStatus" 
                                                                label="Select Status"
                                                                item-title="text"
                                                                :error-messages="props.errors?.status"
                                                                :chips="true" :clearable="true"
                                                                item-value="value" :return-object="false"></v-combobox>
                                                        </v-col>
                                                        <v-col cols="12">
                                                            <v-combobox 
                                                                v-model="formItem.location"
                                                                :items="props.tableLocation" 
                                                                label="Select Location"
                                                                item-title="text"
                                                                :error-messages="props.errors?.selectedCategory"
                                                                :chips="true" :clearable="true"
                                                                item-value="value" :return-object="false"></v-combobox>
                                                        </v-col>
                                                        <v-col cols="12" col-md="6">
                                                            <v-img :width="300" aspect-ratio="16/9" cover
                                                                :src="!uploadFromLocal ? showImage() + formItem.image : previewImage"></v-img>
                                                        </v-col>
                                                        <v-col cols="12">
                                                            <v-file-input ref="image"
                                                                :label="editMode ? 'Change Picture' : 'Upload Picture'"
                                                                required accept="image/png, image/jpeg, image/bmp"
                                                                variant="filled" prepend-icon="mdi-camera"
                                                                @input="PreviewUploadedFile($event)"
                                                                :error-messages="props.errors?.image">

                                                            </v-file-input>
                                                        </v-col>
                                                    </v-row>
                                                </v-form>
                                            </v-container>
                                        </v-card-text>
                                        <v-card-actions>
                                            <v-spacer></v-spacer>
                                            <v-btn color="blue-darken-1" variant="text" @click="cancelAddTable">
                                                Close
                                            </v-btn>
                                            <v-btn color="blue-darken-1" variant="text" @click="PostTable"
                                                v-show="!editMode">
                                                Save
                                            </v-btn>
                                            <v-btn color="blue-darken-1" variant="text" @click="UpdateTable"
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

                        <template v-slot:item.status="{item}">
                            <v-chip  
                            :color="item.raw.status == 'pending' ? 
                            'blue' : item.raw.status == 'available' ? 
                            'green' : 'warning'"
                            text-color="white" 
                           >  
                            {{ item.raw.status }}
                            </v-chip>
                        </template>
                        <template v-slot:item.location="{item}">
                            <v-chip  
                            color="primary"
                            text-color="white" 
                          >
                            {{ item.raw.location}}
                            </v-chip>
                        </template>
                        <template v-slot:item.image="{ item }">
                            <v-img :width="60" height="60" class="my-2" cover
                                :src="showImage() + item.selectable.image"></v-img>
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
import Alert from '@/Components/Alert.vue';
import { Head , router, useForm ,usePage} from '@inertiajs/vue3';
import { ref , reactive, onMounted, watchEffect,computed } from 'vue';

const currentPage = ref(1);
const totalPages = ref(1);
const searchTable = ref('');
const editMode = ref(false);
const tableForm = ref(null);
const previewImage = ref(null);
const isLoading = ref(false);
const dialog = ref(false);
const categoryImage = ref();
const formLoading = ref(false);
const uploadFromLocal = ref(false);
const dialogDelete = ref(false);
const deleteID = ref(null);
const isDeletingItem = ref(false);
const image = ref(null);
const deletedItemName = ref(null);
const message = ref('');
const snackbar = ref(false);
onMounted(() => {
  searchTable.value = props.queryString;
})



const props = defineProps({
    errors: Object,
    table: Object,
    categories: Object,
    tableLocation: Object,
    tableStatus: Object,
    queryString: {
        type: String,
        default: "",
    },
});


const formItem = reactive({
    name: null,
    guest_number: null,
    status:null,
    location: null,
    image: null,
});


const headers = ref([
        {title: 'Image', key: 'image' },
        {title: 'Name', key: 'name' },
        {title: '# of Guest', key: 'guest_number' },
        {title: 'Location', key: 'location' },
        {title: 'Status', key: 'status' },
      
        {title: 'Actions', key: 'actions' }
      ]);

watchEffect(function (){
  currentPage.value = props.table.current_page;
  totalPages.value = props.table.last_page;
  isLoading.value = isLoading.value;
  uploadFromLocal.value  = uploadFromLocal.value;
});


const showImage = () =>{
    return "/storage/";
}


function cancelAddTable(){
  tableForm.value.reset();
  categoryImage.value = "";
  dialog.value = false;
}


const paginate = (page) => {
  currentPage.value = page
  fetchTables();
}

const searchInTable = ()=>{

setTimeout(()=>{
    currentPage.value  = 1 ;
    fetchTables();
}, 500)
}




function PostTable() {
    router.post('/admin/tables', formItem, {
        onBefore: () => {
            formLoading.value = true;
        },
        onError: () => {
            formLoading.value = false;
        },
        onSuccess: () => {
            dialog.value = false;
            formLoading.value = false;
            editMode.value = true;
            message.value =  `Success creating ${formItem.name}`;
            snackbar.value = true;
            tableForm.value.reset();
            previewImage.value = "";

        },
        onFinish: ()=>{
            snackbar.value = false;
        }
    });

}
const fetchTables = ()=>{
  router.visit('/admin/tables',{
    method:'get',
    preserveState: true,
    only:['table','categories','queryString'],
    data:{
      query: searchTable.value,
      page: currentPage.value,
     
    },
    onBefore: ()=>{
      isLoading.value = true;
    },
    onError: ()=>{
      formLoading.value = false;
    },
    onFinish:()=>{
      isLoading.value = false;
      
    }
  });

}

const editItem = (item) => {
  Object.assign(formItem,item);
  formItem.selectedCategory = item.categories;
  dialog.value = true;
  editMode.value = true;
}


function PreviewUploadedFile(e){
  previewImage.value = URL.createObjectURL(e.target.files[0]);
  uploadFromLocal.value = true;
  formItem.image = e.target.files[0];
}



function UpdateTable(){
  router.post(`/admin/tables/${formItem.id}`,{
    _method: "put",
    formItem,
    image: formItem.image,
   
  },{
      onBefore:()=>{
        formLoading.value = true;
      },
      onError: ()=>{
        formLoading.value = false;
      },
      onSuccess:()=>{
        dialog.value = false;
        formLoading.value = false;
        editMode.value = false;
        message.value =  `Success updating ${formItem.name}`;
        snackbar.value = true;
        tableForm.value.reset()
       
      }, 
      onFinish: ()=>{
        snackbar.value = false;
      }
  });
}
const deleteMenuItem = (item)=>{
  deleteID.value  = item.id;
  deletedItemName.value = item.name;
  dialogDelete.value = true;
}

function deleteItemConfirm(isDeleting){
  router.delete('/admin/tables/'+deleteID.value , {
    onBefore:()=>{
      isDeletingItem.value = isDeleting;
    },
    onSuccess: ()=>{
      dialogDelete.value = false;
      isDeletingItem.value = false;
      message.value =  `${deletedItemName.value} is deleted`;
      snackbar.value = true;
    },
    onFinish: ()=>{
      snackbar.value = false;
    }
  });
}
</script>