<script setup lang="ts">
import { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

const props = defineProps<{
    class?: HTMLAttributes['class'];
}>()
</script>


<template>
    <p :class="cn('text-sm font-medium text-text-primary dark:text-text-primary-dark', props.class)">
        <slot />
    </p>
</template>

<style scoped></style>
