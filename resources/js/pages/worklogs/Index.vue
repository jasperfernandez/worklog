<script setup lang="ts">
import BodyText from '@/components/BodyText.vue';
import FormInput from '@/components/FormInput.vue';
import Heading from '@/components/Heading.vue';
import HelperText from '@/components/HelperText.vue';
import ButtonLink from '@/components/ButtonLink.vue';
import { Link, router } from '@inertiajs/vue3';
import { FileText, Plus } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import Button from '@/components/Button.vue';
import { Paginated, Worklog } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { formatDate } from '@/lib/utils';

interface Props {
    worklogs: Paginated<Worklog>;
    filters: {
        search?: string;
        from_date?: string;
        to_date?: string;
    };
}

const props = defineProps<Props>();

// Filter form data
const search = ref(props.filters.search || '');
const fromDate = ref(props.filters.from_date || '');
const toDate = ref(props.filters.to_date || '');

// Watch for changes and update URL params
const updateFilters = () => {
    router.get(
        route('worklogs.index'),
        {
            search: search.value || undefined,
            from_date: fromDate.value || undefined,
            to_date: toDate.value || undefined,
        },
        {
            preserveState: true,
            replace: true,
        },
    );
};

// Debounced search
let searchTimeout: number;
watch(search, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(updateFilters, 500);
});

// Immediate date filter updates
watch([fromDate, toDate], updateFilters);

const clearFilters = () => {
    search.value = '';
    fromDate.value = '';
    toDate.value = '';
    updateFilters();
};

const truncateContent = (content: string, maxLength: number = 150) => {
    return content.length > maxLength ? content.substring(0, maxLength) + '...' : content;
};
</script>

<template>
    <AppLayout page-title="Work Logs" >
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <Heading variant="md">Work Logs</Heading>
                    <HelperText class="mt-1">Track your daily work activities and achievements</HelperText>
                </div>
                <div class="mt-4 sm:mt-0">
                    <ButtonLink :href="route('worklogs.create')" class="inline-flex items-center">
                        <Plus class="mr-2" :size="16" />
                        Add New Log
                    </ButtonLink>
                </div>
            </div>

            <!-- Filters -->
            <div class="mb-6 rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="p-6">
                    <Heading variant="sm" class="mb-4">Filters</Heading>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                        <div class="md:col-span-2">
                            <FormInput id="search" v-model="search" label="Search" name="search" placeholder="Search by title or content..." />
                        </div>
                        <FormInput id="from_date" label="From Date" v-model="fromDate" type="date" />
                        <FormInput id="to_date" label="To Date" v-model="toDate" type="date" />
                    </div>
                    <div class="mt-4 flex justify-end">
                        <Button variant="secondary" @click="clearFilters"> Clear Filters </Button>
                    </div>
                </div>
            </div>

            <!-- Work Logs List -->
            <div class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div v-if="worklogs.data.length === 0" class="flex flex-col items-center justify-center py-12 text-center">
                    <FileText class="text-secondary-600 dark:text-secondary-400" :size="40" />
                    <BodyText class="mt-2">No work logs</BodyText>
                    <HelperText class="mt-1">Get started by creating your first work log.</HelperText>
                    <div class="mt-6">
                        <ButtonLink :href="route('worklogs.create')" class="inline-flex items-center">
                            <Plus class="mr-2" :size="16" />
                            Add New Log
                        </ButtonLink>
                    </div>
                </div>

                <div v-else class="divide-y divide-gray-200 dark:divide-gray-700">
                    <div v-for="worklog in worklogs.data" :key="worklog.id" class="p-6 transition-colors hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="mb-2 flex items-center space-x-2">
                                    <Heading variant="sm">
                                        <Link :href="route('worklogs.show', worklog.id)" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                            {{ worklog.title }}
                                        </Link>
                                    </Heading>
                                    <span
                                        v-if="worklog.files.length > 0"
                                        class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200"
                                    >
                                    {{ worklog.files.length }} file{{ worklog.files.length !== 1 ? 's' : '' }}
                                </span>
                                </div>

                                <p class="mb-3 text-gray-600 dark:text-gray-300">
                                    {{ truncateContent(worklog.content) }}
                                </p>

                                <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                    <HelperText>{{ formatDate(worklog.log_date) }}</HelperText>
                                    <span>•</span>
                                    <HelperText>Created {{ formatDate(worklog.created_at) }}</HelperText>
                                </div>
                            </div>

                            <div class="ml-6 flex items-center space-x-2">
                                <Link
                                    :href="route('worklogs.show', worklog.id)"
                                    class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300"
                                >
                                    View
                                </Link>
                                <Link
                                    :href="route('worklogs.edit', worklog.id)"
                                    class="text-gray-600 hover:text-gray-500 dark:text-gray-400 dark:hover:text-gray-300"
                                >
                                    Edit
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="worklogs.last_page > 1" class="border-t border-gray-200 bg-gray-50 px-6 py-3 dark:border-gray-600 dark:bg-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            Showing {{ (worklogs.current_page - 1) * worklogs.per_page + 1 }} to
                            {{ Math.min(worklogs.current_page * worklogs.per_page, worklogs.total) }} of {{ worklogs.total }} results
                        </div>
                        <div class="flex space-x-1">
                            <Link
                                v-for="link in worklogs.links"
                                :key="link.label"
                                :href="link.url || '#'"
                                :class="{
                                'bg-indigo-600 text-white': link.active,
                                'bg-white text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700':
                                    !link.active && link.url,
                                'cursor-not-allowed bg-gray-200 text-gray-400 dark:bg-gray-600 dark:text-gray-500': !link.url,
                            }"
                                class="rounded-md border border-gray-300 px-3 py-2 text-sm font-medium dark:border-gray-600"
                            >
                                {{ link.label === '&laquo; Previous' ? '← Previous' : link.label === 'Next &raquo;' ? 'Next →' : link.label }}
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
