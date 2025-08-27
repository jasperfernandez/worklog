<script setup lang="ts">
import { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

const props = defineProps<{
    as?: string
    class?: HTMLAttributes['class']
}>()
const tag = props.as ?? 'h1'
const baseClass = 'text-3xl font-bold tracking-tight text-gray-900 dark:text-white'
</script>

<template>
    <component
        :is="tag"
        :class="cn(baseClass, props.class)"
    >
        <slot />
    </component>
</template>
