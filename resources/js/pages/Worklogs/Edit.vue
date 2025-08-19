<script setup lang="ts">
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3'
import { onMounted } from 'vue'

interface WorklogFile {
    id: number
    filename: string
    original_name: string
    file_size: number
    mime_type: string
    human_file_size: string
}

interface Worklog {
    id: number
    title: string
    content: string
    log_date: string
    created_at: string
    updated_at: string
    files: WorklogFile[]
}

interface Props {
    worklog: Worklog
}

const props = defineProps<Props>()
const page = usePage()
const user = page.props.auth?.user

const form = useForm({
    title: props.worklog.title,
    content: props.worklog.content,
    log_date: props.worklog.log_date,
})

const submit = () => {
    form.put(route('worklogs.update', props.worklog.id), {
        onSuccess: () => {
            // Redirect is handled by the controller
        },
    })
}

const logout = () => {
    router.post('/logout')
}

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}

const getFileIcon = (mimeType: string) => {
    if (mimeType.startsWith('image/')) return '🖼️'
    if (mimeType.startsWith('video/')) return '🎥'
    if (mimeType.startsWith('audio/')) return '🎵'
    if (mimeType.includes('pdf')) return '📄'
    if (mimeType.includes('word')) return '📝'
    if (mimeType.includes('sheet') || mimeType.includes('excel')) return '📊'
    if (mimeType.includes('presentation') || mimeType.includes('powerpoint')) return '📋'
    if (mimeType.includes('zip') || mimeType.includes('rar')) return '🗜️'
    return '📎'
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
    <Head :title="`Edit: ${worklog.title}`" />

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
                            :href="route('worklogs.show', worklog.id)"
                            class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                        >
                            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Back to Work Log
                        </Link>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Edit Work Log
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Update your work log details and content
                    </p>
                </div>

                <!-- Form -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700 mb-6">
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
                                :href="route('worklogs.show', worklog.id)"
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
                                <span v-if="form.processing">Saving...</span>
                                <span v-else>Save Changes</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Current Files Section -->
                <div v-if="worklog.files.length > 0" class="bg-white shadow-sm rounded-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700 mb-6">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            Current Files ({{ worklog.files.length }})
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div
                                v-for="file in worklog.files"
                                :key="file.id"
                                class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors"
                            >
                                <div class="flex-shrink-0 mr-3 text-2xl">
                                    {{ getFileIcon(file.mime_type) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        {{ file.original_name }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ file.human_file_size }}
                                    </p>
                                </div>
                                <div class="flex-shrink-0 ml-2">
                                    <button class="text-red-600 hover:text-red-500 dark:text-red-400 dark:hover:text-red-300" title="Delete file">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200 dark:bg-blue-900/20 dark:border-blue-800">
                            <p class="text-sm text-blue-800 dark:text-blue-300">
                                <strong>Note:</strong> File management functionality will be available once file upload is implemented.
                                For now, you can view existing files but cannot add, remove, or replace them.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Metadata Info -->
                <div class="bg-gray-50 rounded-lg border border-gray-200 dark:bg-gray-800/50 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Work Log Information</h3>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div>
                                <dt class="font-medium text-gray-600 dark:text-gray-400">Created</dt>
                                <dd class="text-gray-900 dark:text-white">{{ formatDate(worklog.created_at) }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-600 dark:text-gray-400">Last Updated</dt>
                                <dd class="text-gray-900 dark:text-white">{{ formatDate(worklog.updated_at) }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-600 dark:text-gray-400">Log Date</dt>
                                <dd class="text-gray-900 dark:text-white">{{ formatDate(worklog.log_date) }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-600 dark:text-gray-400">Attached Files</dt>
                                <dd class="text-gray-900 dark:text-white">{{ worklog.files.length }} file{{ worklog.files.length !== 1 ? 's' : '' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
