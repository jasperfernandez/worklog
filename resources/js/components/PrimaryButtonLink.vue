<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

const props = defineProps<{
    href: string;
    class?: HTMLAttributes['class'];
}>()
</script>

<template>
    <Link
        :href="props.href"
        :class="cn(
        'rounded-md bg-primary-600 px-3 py-2 text-center text-sm font-semibold text-white hover:bg-primary-500 dark:bg-primary-500 dark:hover:bg-primary-400',
        props.class)"
    >
       <slot />
    </Link>
</template>

<style scoped></style>
