<template>
      <v-pagination
      class="mt-5 pt-5"
      v-model="currentPage"
      :length="props.totalPages"
      rounded="circle"
      @click="fetchData"
      primary
    ></v-pagination>
    <div>

    </div>
</template>


<script setup>
import { ref, watchEffect } from 'vue';
const emit = defineEmits(['pageChange']);
const props = defineProps({
    currentPage: {
        type: Number,
        default: 1,
    },
    totalPages:{
        type: Number,
        default: 1,
    }

});

const currentPage = ref();
watchEffect(()=>{
    currentPage.value = props.currentPage
});


const fetchData = () => {
    emit('pageChange', currentPage.value);
}


</script>


<style>
.v-pagination__item--is-active .v-btn__overlay{
    background-color: rgb(var(--v-theme-primary)) !important;
    opacity: 1;
}
.v-pagination__item--is-active .v-btn__content {
    position: absolute;
    left: 50%;
    transform: translate(-50%, -50%);
    top: 50%;
    color: rgb(var(--v-theme-light)) !important;
}
</style>