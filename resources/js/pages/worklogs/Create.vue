<script setup lang="ts">
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import FormInput from '@/components/FormInput.vue';
import HelperText from '@/components/HelperText.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import { ChevronLeft, X, File, Paperclip } from 'lucide-vue-next';
import Heading from '@/components/Heading.vue';

const page = usePage();
const user = page.props.auth?.user;

// File upload refs
const fileInput = ref<HTMLInputElement | null>(null);
const selectedFiles = ref<File[]>([]);

// Get today's date in YYYY-MM-DD format
const getTodayDate = () => {
    const today = new Date();
    return today.toISOString().split('T')[0];
};

const form = useForm({
    title: '',
    content: '',
    log_date: getTodayDate(),
    files: [] as File[],
});

const submit = () => {
    form.files = selectedFiles.value;
    form.post(route('worklogs.store'));
};

const logout = () => {
    router.post('/logout');
};

// File handling methods
const handleFileSelect = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files) {
        const newFiles = Array.from(target.files);
        selectedFiles.value = [...selectedFiles.value, ...newFiles];

        // Reset the input to allow selecting the same file again if needed
        target.value = '';
    }
};

const removeFile = (index: number) => {
    selectedFiles.value.splice(index, 1);
};

const formatFileSize = (bytes: number): string => {
    const units = ['B', 'KB', 'MB', 'GB'];
    let size = bytes;
    let unitIndex = 0;

    while (size >= 1024 && unitIndex < units.length - 1) {
        size /= 1024;
        unitIndex++;
    }

    return `${size.toFixed(1)} ${units[unitIndex]}`;
};

const triggerFileInput = () => {
    fileInput.value?.click();
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
    <Head title="Create Work Log" />

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm dark:bg-gray-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <div class="flex items-center space-x-8">
                        <Link
                            href="/dashboard"
                            class="text-xl font-semibold text-gray-900 hover:text-primary-600 dark:text-white dark:hover:text-primary-400"
                        >
                            Worklog
                        </Link>
                        <nav class="hidden space-x-8 md:flex">
                            <Link href="/dashboard" class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                                Dashboard
                            </Link>
                            <Link href="/worklogs" class="font-medium text-primary-600 dark:text-primary-400"> Work Logs </Link>
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
                            class="inline-flex gap-1 items-center text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                        >
                            <ChevronLeft :size="16" />
                            Back to Work Logs
                        </Link>
                    </div>
                    <Heading variant="md">Create New Work Log</Heading>
                    <HelperText class="mt-1">Document your daily work activities and achievements</HelperText>
                </div>

                <!-- Form -->
                <div class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <form @submit.prevent="submit" class="space-y-6 p-6">
                        <!-- Title -->
                        <FormInput
                            id="title"
                            label="Title"
                            v-model="form.title"
                            type="text"
                            required
                            placeholder="Enter a descriptive title for your work log"
                            :error="form.errors.title"
                            showRequiredAsterisk
                        />

                        <!-- Log Date -->
                        <FormInput
                            id="log_date"
                            label="Log Date"
                            v-model="form.log_date"
                            type="date"
                            required
                            :error="form.errors.log_date"
                            showRequiredAsterisk
                        />

                        <!-- Content -->
                        <div>
                            <label for="content" class="mb-2 block text-sm font-medium text-text-secondary dark:text-gray-300">
                                Content <span class="text-danger-600 dark:text-danger-400">*</span>
                            </label>
                            <textarea
                                id="content"
                                v-model="form.content"
                                rows="8"
                                required
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
                                placeholder="Describe what you worked on today. Include tasks completed, challenges faced, achievements, and any other relevant details..."
                            />
                            <div v-if="form.errors.content" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.content }}
                            </div>
                            <HelperText class="mt-2">
                                Tip: Include specific details about tasks, time spent, results achieved, and next steps for better tracking.
                            </HelperText>
                        </div>

                        <!-- File Attachments -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"> File Attachments </label>

                            <!-- File Input (Hidden) -->
                            <input
                                ref="fileInput"
                                type="file"
                                multiple
                                class="hidden"
                                accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif,.webp,.zip,.rar,.xlsx,.xls,.pptx,.ppt,.csv"
                                @change="handleFileSelect"
                            />

                            <!-- Upload Button -->
                            <button
                                type="button"
                                @click="triggerFileInput"
                                class="mb-4 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                            >
                                <Paperclip :size="18" class="mr-2" />
                                Choose Files
                            </button>

                            <!-- Selected Files List -->
                            <div v-if="selectedFiles.length > 0" class="space-y-2">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Selected Files:</h4>
                                <div class="rounded-md border border-gray-200 bg-gray-50 p-3 dark:border-gray-600 dark:bg-gray-700">
                                    <div v-for="(file, index) in selectedFiles" :key="index" class="flex items-center justify-between py-2">
                                        <div class="flex items-center space-x-3">
                                            <File class="text-secondary-400" :size="18"/>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ file.name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ formatFileSize(file.size) }}</p>
                                            </div>
                                        </div>
                                        <button
                                            type="button"
                                            @click="removeFile(index)"
                                            class="text-danger-600 hover:text-danger-800 dark:text-danger-400 dark:hover:text-danger-300"
                                        >
                                            <X :size="18" />
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- File Upload Help Text -->
                            <HelperText class="mt-2">
                                You can upload up to 10 files. Supported formats: PDF, DOC, DOCX, TXT, Images (JPG, PNG, GIF, WEBP), ZIP, RAR, Excel,
                                PowerPoint, CSV. Max 10MB per file.
                            </HelperText>

                            <!-- File Upload Errors -->
                            <div v-if="form.errors.files" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.files }}
                            </div>
                            <div
                                v-if="Object.keys(form.errors).some((key) => key.startsWith('files.'))"
                                class="mt-1 text-sm text-red-600 dark:text-red-400"
                            >
                                <div v-for="(error, key) in form.errors" :key="key">
                                    <span v-if="key.startsWith('files.')">{{ error }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-between border-t border-gray-200 pt-6 dark:border-gray-700">
                            <Link
                                :href="route('worklogs.index')"
                                class="rounded-md bg-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-400 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500"
                            >
                                Cancel
                            </Link>

                            <PrimaryButton
                                type="submit"
                                :disabled="form.processing"
                                :loading="form.processing"
                                loading-text="Creating...">
                                Create Work Log
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</template>
