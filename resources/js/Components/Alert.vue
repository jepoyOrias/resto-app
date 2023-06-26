<template>
    <v-snackbar
    v-model="snackbar"
    :timeout="timeout"
    color="primary"
    rounded="pill"
>
    {{ props.message }}
</v-snackbar>
</template>


<script setup>
import { watchEffect , ref} from "vue"
const snackbar = ref(false);
const timeout = ref('2000');


    const props = defineProps({
            message: String,
            snackbar: {
                type: Boolean,
                default: false
            },
    })

watchEffect(()=>{
    snackbar.value   = props.snackbar 
});

</script>