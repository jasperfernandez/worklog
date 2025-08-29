<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import Button from '@/components/Button.vue';

const props = defineProps<{
    pageTitle?: string;
}>();

const page = usePage();
const user = page.props.auth?.user;

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <Head :title="props.pageTitle" />

    <div class="min-h-screen bg-background dark:bg-background-dark">
        <!-- Navigation -->
        <nav class="bg-surface shadow-sm dark:bg-surface-dark">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <div class="flex items-center space-x-8">
                        <Link
                            :href="route('dashboard')"
                            class="text-xl font-semibold text-gray-900 hover:text-indigo-600 dark:text-white dark:hover:text-indigo-400"
                            prefetch
                        >
                            Worklog
                        </Link>
                        <nav class="hidden space-x-8 md:flex">
                            <Link
                                :href="route('dashboard')"
                                :class="page.url === '/dashboard' ? 'font-medium text-indigo-600 dark:text-indigo-400' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white'"
                                prefetch
                            >
                                Dashboard
                            </Link>
                            <Link
                                :href="route('worklogs.index')"
                                :class="page.url.startsWith('/worklogs') ? 'font-medium text-indigo-600 dark:text-indigo-400' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white'"
                                prefetch
                            >
                                Work Logs
                            </Link>
                        </nav>
                    </div>

                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-700 dark:text-gray-300">
                            {{ user?.name }}
                        </span>
                        <Button variant="secondary"
                            @click="logout"
                        >
                            Logout
                        </Button>
                    </div>
                </div>
            </div>
        </nav>

        <main class="py-10">
            <slot />
        </main>
    </div>
</template>
