<template>
  
    <v-dialog v-model="deleteDialog"  max-width="500px">
            <v-card v-if="!isDeletingItem">
                <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue-darken-1" variant="text" @click="cancelDelete">Cancel</v-btn>
                    <v-btn color="blue-darken-1" variant="text" @click="deleteItemConfirm">OK</v-btn>
                    <v-spacer></v-spacer>
                </v-card-actions>
            </v-card>
            <v-card
        color="primary" 
        v-else
                >
                    <v-card-text>
                   Deleting your item Please standby
                    <v-progress-circular
                        indeterminate
                        color="white"
                        class="mb-0"
                    ></v-progress-circular>
                    </v-card-text>
                </v-card>
    </v-dialog>
</template>
<script setup>
import {onMounted, ref, watch, watchEffect } from "vue";

const emit = defineEmits(['deleteItem']);
const isDeletingItem = ref(false);
const deleteDialog = ref(false);
const props = defineProps({
    deleteDialog: Boolean ,
    isDeletingItem: {
        type: Boolean,
        default: false,
    },
})

watchEffect(()=>{
    deleteDialog.value = props.deleteDialog;
    isDeletingItem.value = props.isDeletingItem;
});


const cancelDelete = ()=>{
    deleteDialog.value = false;
};

const deleteItemConfirm = ()=>{
    emit('deleteItem' , isDeletingItem.value = true);
}
             
</script>