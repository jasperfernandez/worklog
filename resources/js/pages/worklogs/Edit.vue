<script setup lang="ts">
import Banner from '@/components/Banner.vue';
import Button from '@/components/Button.vue';
import ButtonLink from '@/components/ButtonLink.vue';
import FormInput from '@/components/FormInput.vue';
import Heading from '@/components/Heading.vue';
import HelperText from '@/components/HelperText.vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { ChevronLeft, Save, Trash } from 'lucide-vue-next';
import { onMounted } from 'vue';

interface WorklogFile {
    id: number;
    filename: string;
    original_name: string;
    file_size: number;
    mime_type: string;
    human_file_size: string;
}

interface Worklog {
    id: number;
    title: string;
    content: string;
    log_date: string;
    created_at: string;
    updated_at: string;
    files: WorklogFile[];
}

interface Props {
    worklog: Worklog;
}

const props = defineProps<Props>();
const page = usePage();
const user = page.props.auth?.user;

const form = useForm({
    title: props.worklog.title,
    content: props.worklog.content,
    log_date: props.worklog.log_date.split('T')[0], // Convert ISO date to YYYY-MM-DD
});

const submit = () => {
    form.put(route('worklogs.update', props.worklog.id), {
        onSuccess: () => {
            // Redirect is handled by the controller
        },
    });
};

const logout = () => {
    router.post(route('logout'));
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};

const getFileIcon = (mimeType: string) => {
    if (mimeType.startsWith('image/')) return '🖼️';
    if (mimeType.startsWith('video/')) return '🎥';
    if (mimeType.startsWith('audio/')) return '🎵';
    if (mimeType.includes('pdf')) return '📄';
    if (mimeType.includes('word')) return '📝';
    if (mimeType.includes('sheet') || mimeType.includes('excel')) return '📊';
    if (mimeType.includes('presentation') || mimeType.includes('powerpoint')) return '📋';
    if (mimeType.includes('zip') || mimeType.includes('rar')) return '🗜️';
    return '📎';
};

// Focus on title field when component mounts
onMounted(() => {
    const titleInput = document.getElementById('title');
    if (titleInput) {
        titleInput.focus();
    }
});
</script>

<template>
    <Head :title="`Edit: ${worklog.title}`" />

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm dark:bg-gray-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <div class="flex items-center space-x-8">
                        <Link
                            :href="route('dashboard')"
                            class="text-xl font-semibold text-gray-900 hover:text-indigo-600 dark:text-white dark:hover:text-indigo-400"
                        >
                            Worklog
                        </Link>
                        <nav class="hidden space-x-8 md:flex">
                            <Link :href="route('dashboard')" class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                                Dashboard
                            </Link>
                            <Link :href="route('worklogs.index')" class="font-medium text-indigo-600 dark:text-indigo-400"> Work Logs </Link>
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
                    <div class="mb-4 flex items-center space-x-4">
                        <Link
                            :href="route('worklogs.index')"
                            class="inline-flex items-center gap-1 text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                        >
                            <ChevronLeft :size="16" />
                            Back to Work Logs
                        </Link>
                    </div>
                    <Heading variant="md">Edit Work Log</Heading>
                    <HelperText class="mt-1">Update your work log details and content</HelperText>
                </div>

                <!-- Form -->
                <div class="mb-6 rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <form @submit.prevent="submit" class="space-y-6 p-6">
                        <!-- Title -->
                        <FormInput
                            id="title"
                            label="Title"
                            v-model="form.title"
                            type="text"
                            requidanger
                            placeholder="Enter a descriptive title for your work log"
                            :error="form.errors.title"
                            showRequidangerAsterisk
                        />

                        <!-- Log Date -->
                        <FormInput
                            id="log_date"
                            label="Log Date"
                            v-model="form.log_date"
                            type="date"
                            required
                            :error="form.errors.log_date"
                            showRequidangerAsterisk
                        />

                        <!-- Content -->
                        <div>
                            <label for="content" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Content <span class="text-danger-600 dark:text-danger-400">*</span>
                            </label>
                            <textarea
                                id="content"
                                v-model="form.content"
                                rows="8"
                                required
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
                                placeholder="Describe what you worked on today. Include tasks completed, challenges faced, achievements, and any other relevant details..."
                            />
                            <div v-if="form.errors.content" class="mt-1 text-sm text-danger-600 dark:text-danger-400">
                                {{ form.errors.content }}
                            </div>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Tip: Include specific details about tasks, time spent, results achieved, and next steps for better tracking.
                            </p>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-between border-t border-gray-200 pt-6 dark:border-gray-700">
                            <ButtonLink :href="route('worklogs.show', worklog.id)" variant="secondary"> Cancel </ButtonLink>

                            <Button type="submit" :disabled="form.processing" :loading="form.processing" loading-text="Saving...">
                                <Save :size="16" class="mr-2 inline-flex items-center" />
                                Save Changes
                            </Button>
                        </div>
                    </form>
                </div>

                <!-- Current Files Section -->
                <div
                    v-if="worklog.files.length > 0"
                    class="mb-6 rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800"
                >
                    <div class="p-6">
                        <h2 class="mb-4 text-lg font-medium text-gray-900 dark:text-white">Current Files ({{ worklog.files.length }})</h2>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            <div
                                v-for="file in worklog.files"
                                :key="file.id"
                                class="flex items-center rounded-lg border border-gray-200 p-4 transition-colors hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700"
                            >
                                <div class="mr-3 flex-shrink-0 text-2xl">
                                    {{ getFileIcon(file.mime_type) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium text-gray-900 dark:text-white">
                                        {{ file.original_name }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ file.human_file_size }}
                                    </p>
                                </div>
                                <div class="ml-2 flex-shrink-0">
                                    <button class="text-danger-600 hover:text-danger-500 dark:text-danger-400 dark:hover:text-danger-300" title="Delete file">
                                        <Trash :size="16" />
                                    </button>
                                </div>
                            </div>
                        </div>

                        <Banner variant="info" class="mt-4">
                            <strong>Note:</strong> File management functionality will be available once file upload is implemented. For now, you can
                            view existing files but cannot add, remove, or replace them.
                        </Banner>
                    </div>
                </div>

                <!-- Metadata Info -->
                <div class="rounded-lg border border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800/50">
                    <div class="p-6">
                        <h3 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Work Log Information</h3>
                        <dl class="grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">
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
                                <dd class="text-gray-900 dark:text-white">
                                    {{ worklog.files.length }} file{{ worklog.files.length !== 1 ? 's' : '' }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
