<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const show1 = ref(false);

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />
        <v-card max-width="400" width="100%" class="flex flex-column justify-center">
            <v-card-text>
                <v-form @submit.prevent="submit">
                    <v-text-field 
                    v-model="form.email" 
                    required 
                    label="Email"
                    variant="underlined"
                    :color="form.errors?.email ? 'danger' : 'primary'"
                    :error-messages="form.errors?.email"></v-text-field>
                    <v-text-field 
                        class="mt-4"
                        v-model="form.password"
                        :append-inner-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'" 
                        :type="show1 ? 'text' : 'password'"
                        label="Password" 
                        variant="underlined"
                        :color="form.errors?.email ? 'danger' : 'primary'"
                        :error-messages="form.errors?.password"
                        @click:append-inner="show1 = !show1"></v-text-field>
                    <v-checkbox 
                        class="mt-4"
                        v-model:checked="form.remember"
                        label="Remember me"></v-checkbox>
                    <div class="flex  flex-column justify-end mt-4">
                        <Link v-if="canResetPassword" :href="route('password.request')"
                           >
                        Forgot your password?
                        </Link>
                        <v-btn 
                            class="ml-4" 
                            color="primary"
                            type="submit" 
                            append-icon="mdi-login-variant"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Log in
                        </v-btn>
                    </div>
                </v-form>
            </v-card-text>
        </v-card>
    </GuestLayout>
</template>
