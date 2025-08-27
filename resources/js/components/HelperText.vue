<script setup lang="ts">
import { cn } from '@/lib/utils';

const props = defineProps<{
    class?: string;
}>();

const baseClass = 'text-sm text-gray-600 dark:text-gray-400';
</script>

<template>
    <p :class="cn(baseClass, props.class)">
        <slot />
    </p>
</template>

<style scoped></style>
