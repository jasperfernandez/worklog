<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';

interface Worklog {
    id: number;
    title: string;
    content: string;
    log_date: string;
    created_at: string;
    updated_at: string;
}

interface Props {
    recentWorklogs?: Worklog[];
}

defineProps<Props>();

const page = usePage();
const user = page.props.auth?.user;

const logout = () => {
    router.post('/logout');
};
</script>

<template>
    <Head title="Dashboard" />

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm dark:bg-gray-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <div class="flex items-center space-x-8">
                        <Link
                            href="/dashboard"
                            class="text-xl font-semibold text-gray-900 hover:text-indigo-600 dark:text-white dark:hover:text-indigo-400"
                        >
                            Worklog
                        </Link>
                        <nav class="hidden space-x-8 md:flex">
                            <Link href="/dashboard" class="font-medium text-indigo-600 dark:text-indigo-400"> Dashboard </Link>
                            <Link href="/worklogs" class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                                Work Logs
                            </Link>
                        </nav>
                    </div>

                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-700 dark:text-gray-300">
                            {{ user?.name }}
                        </span>
                        <button
                            @click="logout"
                            class="rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white hover:bg-gray-500 dark:bg-gray-700 dark:hover:bg-gray-600"
                        >
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="py-10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Welcome Section -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage your daily work logs and track your productivity.</p>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Add New Log Card -->
                    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg
                                        class="h-6 w-6 text-indigo-600 dark:text-indigo-400"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Add New Log</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Create a new daily work log entry</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <Link
                                    :href="route('worklogs.create')"
                                    class="block w-full rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white hover:bg-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-400"
                                >
                                    Create Log
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- View Logs Card -->
                    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg
                                        class="h-6 w-6 text-green-600 dark:text-green-400"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-4.5B4.875 8.25 2.25 10.875 2.25 14.25v2.625c0 .621.504 1.125 1.125 1.125h15.75c.621 0 1.125-.504 1.125-1.125Z"
                                        />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">View Logs</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Browse and filter your work logs</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <Link
                                    :href="route('worklogs.index')"
                                    class="block w-full rounded-md bg-green-600 px-3 py-2 text-center text-sm font-semibold text-white hover:bg-green-500 dark:bg-green-500 dark:hover:bg-green-400"
                                >
                                    View All Logs
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Analytics Card -->
                    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg
                                        class="h-6 w-6 text-purple-600 dark:text-purple-400"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"
                                        />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Analytics</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">View productivity insights</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button
                                    class="w-full rounded-md bg-purple-600 px-3 py-2 text-sm font-semibold text-white hover:bg-purple-500 dark:bg-purple-500 dark:hover:bg-purple-400"
                                >
                                    View Analytics
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="mt-8">
                    <div class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Activity</h3>
                        </div>
                        <div class="p-6">
                            <div v-if="recentWorklogs && recentWorklogs.length > 0" class="space-y-4">
                                <div
                                    v-for="worklog in recentWorklogs"
                                    :key="worklog.id"
                                    class="flex items-start gap-3 rounded-lg border border-gray-100 p-4 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700"
                                >
                                    <div class="flex-shrink-0">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900">
                                            <svg
                                                class="h-4 w-4 text-indigo-600 dark:text-indigo-400"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3-7.036A11.959 11.959 0 0 1 3.75 12c0-6.627 5.373-12 12-12s12 5.373 12 12c0 2.128-.55 4.172-1.536 5.964-2.268 4.134-6.697 6.6-11.464 6.6-1.223 0-2.407-.218-3.536-.64"
                                                />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center justify-between">
                                            <Link
                                                :href="route('worklogs.show', worklog.id)"
                                                class="text-sm font-medium text-gray-900 hover:text-indigo-600 dark:text-white dark:hover:text-indigo-400"
                                            >
                                                {{ worklog.title }}
                                            </Link>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ new Date(worklog.log_date).toLocaleDateString() }}
                                            </span>
                                        </div>
                                        <p class="mt-1 line-clamp-2 text-sm text-gray-600 dark:text-gray-400">
                                            {{ worklog.content.substring(0, 100) }}{{ worklog.content.length > 100 ? '...' : '' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-4 text-center">
                                    <Link
                                        :href="route('worklogs.index')"
                                        class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300"
                                    >
                                        View all work logs →
                                    </Link>
                                </div>
                            </div>
                            <div v-else class="py-8 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-4.5B4.875 8.25 2.25 10.875 2.25 14.25v2.625c0 .621.504 1.125 1.125 1.125h15.75c.621 0 1.125-.504 1.125-1.125Z"
                                    />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No work logs</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating your first work log entry.</p>
                                <div class="mt-6">
                                    <Link
                                        :href="route('worklogs.create')"
                                        class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-400"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                        Create your first log
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
