<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { cn } from '@/lib/utils';

const props = defineProps<{
    href: string
    class?: string
}>()

const baseClass = 'font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300'
</script>

<template>
    <Link
        :href="props.href"
        :class="cn(baseClass, props.class)"
    >
        <slot />
    </Link>
</template>
