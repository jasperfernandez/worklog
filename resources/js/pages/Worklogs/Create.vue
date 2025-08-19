<script setup lang="ts">
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3'
import { onMounted } from 'vue'

const page = usePage()
const user = page.props.auth?.user

// Get today's date in YYYY-MM-DD format
const getTodayDate = () => {
    const today = new Date()
    return today.toISOString().split('T')[0]
}

const form = useForm({
    title: '',
    content: '',
    log_date: getTodayDate(),
})

const submit = () => {
    form.post(route('worklogs.store'), {
        onSuccess: () => {
            // Redirect is handled by the controller
        },
    })
}

const logout = () => {
    router.post('/logout')
}

// Focus on title field when component mounts
onMounted(() => {
    const titleInput = document.getElementById('title')
    if (titleInput) {
        titleInput.focus()
    }
})
</script>

<template>
    <Head title="Create Work Log" />

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm dark:bg-gray-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center space-x-8">
                        <Link href="/dashboard" class="text-xl font-semibold text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400">
                            Worklog
                        </Link>
                        <nav class="hidden md:flex space-x-8">
                            <Link href="/dashboard" class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                                Dashboard
                            </Link>
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
        <main class="py-8">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex items-center space-x-4 mb-4">
                        <Link
                            :href="route('worklogs.index')"
                            class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                        >
                            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Back to Work Logs
                        </Link>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Create New Work Log
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Document your daily work activities and achievements
                    </p>
                </div>

                <!-- Form -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                    <form @submit.prevent="submit" class="space-y-6 p-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="title"
                                v-model="form.title"
                                type="text"
                                required
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
                                placeholder="Enter a descriptive title for your work log"
                            />
                            <div v-if="form.errors.title" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.title }}
                            </div>
                        </div>

                        <!-- Log Date -->
                        <div>
                            <label for="log_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Log Date <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="log_date"
                                v-model="form.log_date"
                                type="date"
                                required
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            />
                            <div v-if="form.errors.log_date" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.log_date }}
                            </div>
                        </div>

                        <!-- Content -->
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Content <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                id="content"
                                v-model="form.content"
                                rows="8"
                                required
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
                                placeholder="Describe what you worked on today. Include tasks completed, challenges faced, achievements, and any other relevant details..."
                            />
                            <div v-if="form.errors.content" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.content }}
                            </div>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Tip: Include specific details about tasks, time spent, results achieved, and next steps for better tracking.
                            </p>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                            <Link
                                :href="route('worklogs.index')"
                                class="rounded-md bg-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-400 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500"
                            >
                                Cancel
                            </Link>

                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:opacity-50 disabled:cursor-not-allowed dark:bg-indigo-500 dark:hover:bg-indigo-400"
                            >
                                <svg v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span v-if="form.processing">Creating...</span>
                                <span v-else>Create Work Log</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tips Section -->
                <div class="mt-8 bg-blue-50 rounded-lg border border-blue-200 dark:bg-blue-900/20 dark:border-blue-800">
                    <div class="p-6">
                        <h3 class="text-sm font-medium text-blue-900 dark:text-blue-200 mb-3">
                            Tips for Effective Work Logs
                        </h3>
                        <ul class="text-sm text-blue-800 dark:text-blue-300 space-y-2">
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Be specific about tasks and time spent on each activity</span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Include both completed work and work in progress</span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Note any blockers, challenges, or help needed</span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Document achievements, learnings, and breakthroughs</span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Plan next steps and priorities for tomorrow</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
