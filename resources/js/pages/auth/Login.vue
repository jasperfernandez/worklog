<script setup lang="ts">
import Checkbox from '@/components/Checkbox.vue';
import FormInput from '@/components/FormInput.vue';
import HelperText from '@/components/HelperText.vue';
import LinkText from '@/components/LinkText.vue';
import Button from '@/components/Button.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>

<template>
    <Head title="Login" />

    <div class="flex min-h-screen flex-col items-center justify-center bg-background px-4 py-12 sm:px-6 lg:px-8 dark:bg-background-dark">
        <div class="w-full max-w-md space-y-8">
            <div>
                <Heading variant="lg" class="text-center">Sign in to your account</Heading>
                <HelperText class="mt-2 text-center">
                    Or
                    <LinkText :href="route('register')"> create a new account </LinkText>
                </HelperText>
            </div>

            <form class="space-y-6" @submit.prevent="submit">
                <div class="space-y-4">
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
                </div>

                <div class="space-y-4">
                    <Checkbox v-model="form.remember" id="remember" name="remember" label="Remember me" />

                    <Button class="w-full" type="submit" :disabled="form.processing" :loading="form.processing" loading-text="Signing in...">
                        Sign in
                    </Button>
                </div>
            </form>
        </div>
    </div>
</template>
