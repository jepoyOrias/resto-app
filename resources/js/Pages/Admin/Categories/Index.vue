

<template>
    <Head title="Categories" />

    <AdminLayout>
        <Alert :message="message" :snackbar="snackbar"/>
       <v-container>
        <v-row class="mt-12 pt-12">
          <v-col cols="10" class="mx-auto mt-5">
            <v-data-table-server
            v-model:items-per-page="props.categories.per_page"
            :items-length="props.categories.total"
            :headers="headers"
            :items="props.categories.data"
            class="elevation-1"
            item-value="name"
            item-key="id"
            :loading="isLoading"
          >

          <template v-slot:top>
            <v-toolbar>
              <v-row>
                <v-col cols="6">
                  <v-text-field
                   v-model="searchCategory" 
                   hide-details
                   placeholder="Search name..." 
                   class="ma-2 cursor-pointer" density="compact"
                   background-color="primary"
                   append-icon="mdi-magnify"
                   color="primary"
                   @input="searchInTable"
                   ></v-text-field>
                </v-col>
              </v-row>
             
              <v-divider vertical></v-divider>
              <v-dialog
              v-model="dialog"
              persistent
              width="1024"
            >

              <LoadingOverlay :loadingOverlay="formLoading" />

              <template v-slot:activator="{ props }">
                <v-btn
                  color="primary"
                  v-bind="props"
                  prepend-icon="mdi-plus"
                  @click="editMode =false"
                  variant="flat"
                  rounded="xl"
                  class="mx-2"
                 
                >
                  Add Category
                </v-btn>
              </template>
              <v-card>
                <v-card-title>
                  <span class="text-h5">{{editMode ? "Edit" : "Add"}} Category</span>
                </v-card-title>
                <v-card-text>
                  <v-container>
                    <v-form ref="categoryForm">
                      <v-row>
                        <v-col cols="12">
                              <v-text-field 
                              v-model="formItem.name"
                              label="Category Name"
                              :error-messages="props.errors?.name"
                            ></v-text-field>
                        </v-col>
                <v-col cols="12">
                  <v-textarea
                    label="Description" 
                    v-model="formItem.description"
                    clearable
                    required
                    clear-icon="mdi-close-circle"
                    :error-messages="props.errors?.description"
                  ></v-textarea>
                </v-col >
                  <v-col cols="12" col-md="6">
                    <v-img
                      :width="300"
                      aspect-ratio="16/9"
                      cover
                      :src="!uploadFromLocal ? showImage() + formItem.image : previewImage"
                    ></v-img>
                  </v-col>
                <v-col cols="12">
                    <v-file-input 
                    ref="image"
                    :label="editMode ? 'Change Picture':'Upload Picture'"
                    required
                    accept="image/png, image/jpeg, image/bmp"
                    variant="filled"
                    prepend-icon="mdi-camera"
                    @input="PreviewUploadedFile($event)"
                    :error-messages="props.errors?.image"
                  >
                 
                    </v-file-input>
                 </v-col>
              </v-row>
            </v-form>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="blue-darken-1"
            variant="text"
            @click="cancelAddCategory"
          >
            Close
          </v-btn>
          <v-btn
            color="blue-darken-1"
            variant="text"
            @click="PostCategory"
            v-show="!editMode"
          >
            Save
          </v-btn>
          <v-btn
            color="blue-darken-1"
            variant="text"
            @click="UpdateCategory"
            v-show="editMode"
          >
            Update
          </v-btn>
        </v-card-actions>
      </v-card>
              </v-dialog>

      <DeleteBanner :deleteDialog="dialogDelete" :isDeletingItem="isDeletingItem" @deleteItem="deleteItemConfirm"/>



        </v-toolbar>
          </template>
          <template v-slot:item.image="{ item }">
            <v-img
            :width="60"
            height="60"
            class="my-2"
            cover
            :src="showImage() + item.selectable.image "
          ></v-img>
           </template>
          <template v-slot:item.actions="{ item }">
                <v-icon
                  size="small"
                  class="me-2"
                  color="info"
                  @click="editItem(item.raw)"
                >
                  mdi-pencil
                </v-icon>
                <v-icon
                  size="small"
                  color="error"
                  @click="deleteCategoryItem(item.raw)"
                >
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
                <Pagination 
                :currentPage="currentPage"
                :totalPages="totalPages"
                @pageChange="paginate"
                ></Pagination>
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
import Alert from '@/Components/Alert.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head , router, useForm ,usePage} from '@inertiajs/vue3';
import { ref , reactive, onMounted, watchEffect } from 'vue';

const currentPage =  ref(1);
const  totalPages =  ref(1);
const searchCategory = ref('');
const editMode = ref(false);
const categoryForm = ref(null); 
const previewImage = ref(null);
const  isLoading = ref(false);
const   dialog = ref(false);
const categoryImage = ref();
const formLoading = ref(false);
const uploadFromLocal = ref(false);
const dialogDelete = ref(false);
const deleteID = ref(null);
const isDeletingItem = ref(false);
const image =ref(null);
const message = ref('');
const snackbar = ref(false);
onMounted(() => {
  searchCategory.value = props.queryString;
})

const formItem = reactive({
  name: null,
  description:null,
  image: null,

});


const headers = ref([
        {title: 'Image', key: 'image' },
        {title: 'Category Name', key: 'name' },
        {title: 'Description', key: 'description' },
        {title: 'Actions', key: 'actions' }
      ]);

const props = defineProps({
    errors: Object,
    categories:Object,
    queryString: {
      type: String,
      default: "",
    },
});


watchEffect(function (){
  currentPage.value = props.categories.current_page;
  totalPages.value = props.categories.last_page;
  isLoading.value = isLoading.value;
  uploadFromLocal.value  = uploadFromLocal.value;
  
});


const showImage = () =>{
    return "/storage/";
}


function cancelAddCategory(){
  categoryForm.value.reset();
  categoryImage.value = "";
  dialog.value = false;
}



function PostCategory() {
  router.post('/admin/categories', formItem,{
    onBefore: ()=>{
      formLoading.value = true;
    },
    onError: ()=>{
      formLoading.value = false;
    },
    onSuccess: ()=>{
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

const fetchCategories = ()=>{
  router.visit('/admin/categories',{
    method:'get',
    preserveState: true,
    only:['categories','queryString'],
    data:{
      query: searchCategory.value,
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
  dialog.value = true;
  editMode.value = true;
}


function PreviewUploadedFile(e){
  previewImage.value = URL.createObjectURL(e.target.files[0]);
  uploadFromLocal.value = true;
  formItem.image = e.target.files[0];
}



function UpdateCategory(){
  router.post(`/admin/categories/${formItem.id}`,{
    _method:"put",
    formItem,
    image: formItem.image
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
const deleteCategoryItem = (item)=>{
  deleteID.value  = item.id;
  dialogDelete.value = true;
}

function deleteItemConfirm(isDeleting){
  router.delete('/admin/categories/'+deleteID.value , {
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

console.log(props.categories)
</script>


