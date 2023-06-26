<template>
    <Head title="Menus" />

    <AdminLayout>
        
        <Alert :message="message" :snackbar="snackbar"/>
        <v-container>
            <v-row class="mt-12 pt-12">
                <v-col cols="12" md="10" class="mx-auto">


                    <v-data-table-server v-model:items-per-page="props.menus.per_page"
                        :items-length="props.menus.total" :headers="headers" :items="props.menus.data"
                        class="elevation-1" item-value="name" item-key="id" :loading="isLoading">

                        <template v-slot:top>
                            <v-toolbar>
                                <v-row>
                                    <v-col cols="6">
                                        <v-text-field v-model="searchMenu" hide-details placeholder="Search name..."
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
                                            Add Menu
                                        </v-btn>
                                    </template>
                                    <v-card>
                                        <v-card-title>
                                            <span class="text-h5">{{ editMode ? "Edit" : "Add" }} Menu</span>
                                        </v-card-title>
                                        <v-card-text>
                                            <v-container>
                                                <v-form ref="categoryForm">
                                                    <v-row>
                                                        <v-col cols="12">
                                                            <v-text-field v-model="formItem.name" label="Category Name"
                                                                :error-messages="props.errors?.name"></v-text-field>
                                                        </v-col>
                                                        <v-col cols="12">
                                                            <v-combobox 
                                                                v-model="formItem.selectedCategory"
                                                                :items="props.categories" 
                                                                label="Select / Search Category"
                                                                item-title="name"
                                                                :error-messages="props.errors?.selectedCategory"
                                                                :multiple="true" :chips="true" :clearable="true"
                                                                item-value="id" :return-object="false"></v-combobox>
                                                        </v-col>
                                                        <v-col cols="12">
                                                            <v-text-field v-model="formItem.price" label="Price"
                                                                :error-messages="props.errors?.price"></v-text-field>
                                                        </v-col>
                                                        <v-col cols="12">
                                                            <v-textarea label="Description" v-model="formItem.description"
                                                                clearable required clear-icon="mdi-close-circle"
                                                                :error-messages="props.errors?.description"></v-textarea>
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
                                            <v-btn color="blue-darken-1" variant="text" @click="cancelAddMenu">
                                                Close
                                            </v-btn>
                                            <v-btn color="blue-darken-1" variant="text" @click="PostMenu"
                                                v-show="!editMode">
                                                Save
                                            </v-btn>
                                            <v-btn color="blue-darken-1" variant="text" @click="UpdateMenu"
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

                        <template v-slot:item.price="{item}">
                            <v-chip  
                            color="blue"
                            text-color="white" 
                           >  
                            ${{ item.raw.price }}
                            </v-chip>
                        </template>
                        <template v-slot:item.categories="{item}">
                            <v-chip  
                            color="primary"
                            text-color="white" 
                            class="ma-1"
                            v-for="category in item.raw.categories" :key="category.id">
                            {{ category.name }}
                            </v-chip>
                        </template>
                        <template v-slot:item.image="{ item }">
                            <v-img :width="60" height="60" class="my-2" cover
                                :src="showImage() + item.selectable.image "></v-img>
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
import { ref , reactive, onMounted, watchEffect } from 'vue';

const currentPage = ref(1);
const totalPages = ref(1);
const searchMenu = ref('');
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
const image = ref(null);
const message = ref('');
const snackbar = ref(false);

onMounted(() => {
  searchMenu.value = props.queryString;
})

const props = defineProps({
    errors: Object,
    menus: Object,
    categories: Object,
    queryString: {
        type: String,
        default: "",
    },
});


const formItem = reactive({
    name: null,
    description: null,
    image: null,
    selectedCategory: [],
    price: null,
});


const headers = ref([
        {title: 'Image', key: 'image' },
        {title: 'Name', key: 'name' },
        {title: 'Description', key: 'description' },
        {title: 'Price', key: 'price' },
        {title: 'Categories', key: 'categories' },
        {title: 'Actions', key: 'actions' }
      ]);

watchEffect(function (){
  currentPage.value = props.menus.current_page;
  totalPages.value = props.menus.last_page;
  isLoading.value = isLoading.value;
  uploadFromLocal.value  = uploadFromLocal.value;
});


const showImage = () =>{
    return "/storage/";
}


function cancelAddMenu(){
  categoryForm.value.reset();
  categoryImage.value = "";
  dialog.value = false;
}


const paginate = (page) => {
  currentPage.value = page
  fetchCategories();
}

const searchInTable = ()=>{

setTimeout(()=>{
    currentPage.value  = 1 ;
    fetchCategories();
}, 500)
}




function PostMenu() {
    router.post('/admin/menus', formItem, {
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
            categoryForm.value.reset();
            previewImage.value = "";

        }
    });

}
const fetchCategories = ()=>{
  router.visit('/admin/menus',{
    method:'get',
    preserveState: true,
    only:['menus','categories','queryString'],
    data:{
      query: searchMenu.value,
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



function UpdateMenu(){
  router.post(`/admin/menus/${formItem.id}`,{
    _method: "put",
    formItem,
    image: formItem.image,
   
  },{
      onBefore:()=>{
        formLoading.value = true;
      },
      onSuccess:()=>{
        dialog.value = false;
        formLoading.value = false;
        editMode.value = true;
        message.value =  `Success updating ${formItem.name}`;
        snackbar.value = true;
        categoryForm.value.reset()
      }
  });
}
const deleteMenuItem = (item)=>{
  deleteID.value  = item.id;
  dialogDelete.value = true;
}

function deleteItemConfirm(isDeleting){
  router.delete('/admin/menus/'+deleteID.value , {
    onBefore:()=>{
      isDeletingItem.value = isDeleting;
      dialogDelete.value = false;
    },
    onSuccess: ()=>{
      dialogDelete.value = false;
      isDeletingItem.value = false;
      message.value =  `The categort is successfuly deleted`;
      snackbar.value = true;
    }
  });
}
</script>