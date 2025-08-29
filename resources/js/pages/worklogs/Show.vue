<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import ButtonLink from '@/components/ButtonLink.vue';
import {
    Plus,
    Paperclip,
    Calendar,
    SquarePen,
    Trash,
    List,
    ChevronLeft,
    Download,
    FileText,
    FileSpreadsheet,
    FileArchive,
    Image,
    Clapperboard,
    FileChartPie,
    FileAudio2,
} from 'lucide-vue-next';
import BodyText from '@/components/BodyText.vue';
import HelperText from '@/components/HelperText.vue';
import Heading from '@/components/Heading.vue';
import Button from '@/components/Button.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Worklog } from '@/types';
import { formatDate, formatDateTime } from '@/lib/utils';

interface Props {
    worklog: Worklog;
}

const props = defineProps<Props>();

const deleteWorklog = () => {
    if (confirm('Are you sure you want to delete this work log? This action cannot be undone.')) {
        router.delete(route('worklogs.destroy', props.worklog.id));
    }
};

const getFileIcon = (mimeType: string) => {
    if (mimeType.startsWith('image/')) return Image;
    if (mimeType.startsWith('video/')) return Clapperboard;
    if (mimeType.startsWith('audio/')) return FileAudio2;
    if (mimeType.includes('pdf')) return FileText;
    if (mimeType.includes('word')) return FileText;
    if (mimeType.includes('sheet') || mimeType.includes('excel')) return FileSpreadsheet;
    if (mimeType.includes('presentation') || mimeType.includes('powerpoint')) return FileChartPie;
    if (mimeType.includes('zip') || mimeType.includes('rar')) return FileArchive;
    return Paperclip;
};

const downloadFile = (fileId: number) => {
    window.location.href = route('worklog-files.download', fileId);
};
</script>

<template>
    <AppLayout :page-title="worklog.title">
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

                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between">
                    <div class="flex-1">
                        <Heading variant="md" class="mb-2">
                            {{ worklog.title }}
                        </Heading>
                        <div class="flex items-center space-x-2">
                            <HelperText class="inline-flex items-center">
                                <Calendar class="mr-1" :size="14" />
                                {{ formatDate(worklog.log_date) }}
                            </HelperText>
                            <HelperText>•</HelperText>
                            <HelperText>Created {{ formatDateTime(worklog.created_at) }}</HelperText>
                            <HelperText v-if="worklog.updated_at !== worklog.created_at">
                                • Updated {{ formatDateTime(worklog.updated_at) }}
                            </HelperText>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center space-x-3 sm:mt-0">
                        <ButtonLink :href="route('worklogs.edit', worklog.id)" class="inline-flex items-center">
                            <SquarePen :size="16" class="mr-2" />
                            Edit
                        </ButtonLink>

                        <Button @click="deleteWorklog" variant="danger" class="inline-flex items-center">
                            <Trash :size="16" class="mr-2" />
                            Delete
                        </Button>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="mb-6 rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="p-6">
                    <Heading variant="sm" class="mb-4">Content</Heading>
                    <div class="prose prose-gray dark:prose-invert max-w-none">
                        <div class="leading-relaxed whitespace-pre-wrap text-gray-700 dark:text-gray-300">{{ worklog.content }}</div>
                    </div>
                </div>
            </div>

            <!-- Files Section -->
            <div v-if="worklog.files.length > 0" class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="p-6">
                    <h2 class="mb-4 text-lg font-medium text-gray-900 dark:text-white">Attached Files ({{ worklog.files.length }})</h2>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <div
                            v-for="file in worklog.files"
                            :key="file.id"
                            class="flex items-center rounded-lg border border-gray-200 p-4 transition-colors hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700"
                        >
                            <div class="mr-3 flex-shrink-0 text-2xl">
                                <component :is="getFileIcon(file.mime_type)" class="text-gray-400 dark:text-gray-500" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-gray-900 dark:text-white">
                                    {{ file.og_filename }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ file.human_file_size }}
                                </p>
                            </div>
                            <div class="ml-2 flex-shrink-0">
                                <button
                                    @click="downloadFile(file.id)"
                                    class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300"
                                    title="Download file"
                                >
                                    <Download />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty Files State -->
            <div v-else class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex flex-col items-center justify-center p-6 text-center">
                    <Paperclip :size="40" class="text-secondary-600 dark:text-secondary-400" />
                    <BodyText class="mt-2">No files attached</BodyText>
                    <HelperText class="mt-1">This work log doesn't have any attached files.</HelperText>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8 flex flex-col gap-4 sm:flex-row">
                <ButtonLink :href="route('worklogs.create')" variant="success" class="inline-flex items-center">
                    <Plus :size="16" class="mr-2" />
                    Create Another Log
                </ButtonLink>

                <ButtonLink :href="route('worklogs.index')" variant="secondary" class="inline-flex items-center">
                    <List :size="16" class="mr-2" />
                    View All Logs
                </ButtonLink>
            </div>
        </div>
    </AppLayout>
</template>
