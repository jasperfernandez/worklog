<script setup lang="ts">
import { cn } from '@/lib/utils';
import { HTMLAttributes } from 'vue';

const props = defineProps<{
    class?: HTMLAttributes['class'];
}>();
</script>

<template>
    <div :class="cn('rounded-lg border border-border bg-surface shadow-sm dark:border-border-dark dark:bg-surface-dark', props.class)">
        <slot />
    </div>
</template>

<style scoped></style>
