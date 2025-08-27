<script setup lang="ts">
import FormInput from '@/components/FormInput.vue';
import Heading from '@/components/Heading.vue';
import HelperText from '@/components/HelperText.vue';
import LinkText from '@/components/LinkText.vue';
import Button from '@/components/Button.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <Head title="Register" />

    <div class="flex min-h-screen flex-col items-center justify-center bg-gray-50 px-4 py-12 sm:px-6 lg:px-8 dark:bg-gray-900">
        <div class="w-full max-w-md space-y-8">
            <div>
                <Heading class="text-center"> Create your account </Heading>
                <HelperText class="mt-2 text-center">
                    Or
                    <LinkText :href="route('login')"> sign in to your existing account </LinkText>
                </HelperText>
            </div>

            <form class="mt-8 space-y-6" @submit.prevent="submit">
                <div class="space-y-4">
                    <FormInput
                        id="name"
                        v-model="form.name"
                        label="Full Name"
                        name="name"
                        autocomplete="username"
                        placeholder="Enter your full name"
                        :required="true"
                        :error="form.errors.email"
                    />

                    <FormInput
                        id="email"
                        v-model="form.email"
                        label="Email address"
                        type="email"
                        name="email"
                        autocomplete="email"
                        placeholder="Enter your email"
                        :required="true"
                        :error="form.errors.email"
                    />

                    <FormInput
                        id="password"
                        v-model="form.password"
                        label="Password"
                        type="password"
                        name="password"
                        autocomplete="current-password"
                        placeholder="Enter your password"
                        :required="true"
                        :error="form.errors.password"
                    />

                    <FormInput
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        label="Confirm Password"
                        type="password"
                        name="password_confirmation"
                        autocomplete="current-password"
                        placeholder="Confirm your password"
                        :required="true"
                        :error="form.errors.password_confirmation"
                    />
                </div>

                <div>
                    <Button class="w-full" type="submit" :disabled="form.processing" :loading="form.processing" loading-text="Creating account...">
                        Create account
                    </Button>
                </div>
            </form>
        </div>
    </div>
</template>
