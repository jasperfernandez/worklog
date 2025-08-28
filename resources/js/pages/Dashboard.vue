<script setup lang="ts">
import BodyText from '@/components/BodyText.vue';
import Card from '@/components/Card.vue';
import HelperText from '@/components/HelperText.vue';
import LinkText from '@/components/LinkText.vue';
import Button from '@/components/Button.vue';
import ButtonLink from '@/components/ButtonLink.vue';
import { Link } from '@inertiajs/vue3';
import { ChartNoAxesColumn, Eye, FileText, Plus, ScrollText } from 'lucide-vue-next';
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Worklog } from '@/types';

interface Props {
    recentWorklogs?: Worklog[];
}

defineProps<Props>();
</script>

<template>
    <AppLayout page-title="Dashboard">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="mb-8">
                <Heading variant="md">Dashboard</Heading>
                <HelperText class="mt-1">Manage your daily work logs and track your productivity.</HelperText>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Add New Log Card -->
                <Card class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <Plus class="text-primary-600 dark:text-indigo-400" />
                        </div>
                        <div class="ml-3">
                            <Heading variant="sm">Add New Log</Heading>
                            <HelperText>Create a new daily work log entry</HelperText>
                        </div>
                    </div>
                    <div class="mt-4">
                        <ButtonLink :href="route('worklogs.create')" class="block w-full"> Create Log </ButtonLink>
                    </div>
                </Card>

                <!-- View Logs Card -->
                <Card class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <Eye class="text-success-600 dark:text-success-400" />
                        </div>
                        <div class="ml-3">
                            <Heading variant="sm">View Logs</Heading>
                            <HelperText>Browse and filter your work logs</HelperText>
                        </div>
                    </div>
                    <div class="mt-4">
                        <ButtonLink
                            :href="route('worklogs.index')"
                            class="block w-full bg-success-600 hover:bg-success-500 dark:bg-success-500 dark:hover:bg-success-400"
                        >
                            View All Logs
                        </ButtonLink>
                    </div>
                </Card>

                <!-- Analytics Card -->
                <Card class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <ChartNoAxesColumn class="text-purple-600 dark:text-purple-400" />
                        </div>
                        <div class="ml-3">
                            <Heading variant="sm">Analytics</Heading>
                            <HelperText>View productivity insights</HelperText>
                        </div>
                    </div>
                    <div class="mt-4">
                        <Button class="w-full bg-purple-600 hover:bg-purple-500 dark:bg-purple-500 dark:hover:bg-purple-400"> View Analytics </Button>
                    </div>
                </Card>
            </div>

            <!-- Recent Activity -->
            <Card class="mt-8">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <Heading variant="sm">Recent Activity</Heading>
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
                                    <ScrollText class="text-primary-600 dark:text-primary-400" :size="18" />
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between">
                                    <Link
                                        :href="route('worklogs.show', worklog.id)"
                                        class="text-sm font-medium text-text-primary hover:text-primary-600 dark:text-text-primary-dark dark:hover:text-primary-400"
                                    >
                                        {{ worklog.title }}
                                    </Link>
                                    <HelperText class="text-xs">
                                        {{ new Date(worklog.log_date).toLocaleDateString() }}
                                    </HelperText>
                                </div>
                                <HelperText class="mt-1 line-clamp-2">
                                    {{ worklog.content.substring(0, 100) }}{{ worklog.content.length > 100 ? '...' : '' }}
                                </HelperText>
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <LinkText :href="route('worklogs.index')" class="text-sm"> View all work logs </LinkText>
                        </div>
                    </div>
                    <div v-else class="flex flex-col items-center justify-center py-8 text-center">
                        <FileText class="text-secondary-600 dark:text-secondary-400" :size="40" />
                        <BodyText class="mt-2">No work logs</BodyText>
                        <HelperText class="mt-1">Get started by creating your first work log entry.</HelperText>
                        <div class="mt-6">
                            <ButtonLink :href="route('worklogs.create')" class="inline-flex items-center gap-2">
                                <Plus />
                                Create your first log
                            </ButtonLink>
                        </div>
                    </div>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>
