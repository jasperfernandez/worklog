<script setup lang="ts">
import { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

type HeadingVariant = 'lg' | 'md' | 'sm'

const props = defineProps<{
    variant?: HeadingVariant
    as?: string
    class?: HTMLAttributes['class']
}>()

const tag = props.as ?? 'h1'
const variant = props.variant ?? 'lg'

const variantClasses: Record<HeadingVariant, string> = {
    lg: 'text-3xl font-bold tracking-tight',
    md: 'text-2xl font-bold tracking-tight',
    sm: 'text-lg font-medium'
}

const baseClass = 'text-text-primary dark:text-text-primary-dark ' + variantClasses[variant]
</script>

<template>
    <component
        :is="tag"
        :class="cn(baseClass, props.class)"
    >
        <slot />
    </component>
</template>
