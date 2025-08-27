<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

type ButtonVariant = 'primary' | 'secondary' | 'success' | 'danger'

const props = defineProps<{
    href: string;
    variant?: ButtonVariant;
    class?: HTMLAttributes['class'];
}>()

const variant = props.variant ?? 'primary'

const getVariantClasses = (variant: ButtonVariant): string => {
    const variants = {
        primary: 'bg-primary-600 hover:bg-primary-500 dark:bg-primary-500 dark:hover:bg-primary-400 focus-visible:outline-primary-600',
        secondary: 'bg-secondary-600 hover:bg-secondary-500 dark:bg-secondary-700 dark:hover:bg-secondary-600 focus-visible:outline-secondary-600',
        success: 'bg-success-600 hover:bg-success-500 dark:bg-success-500 dark:hover:bg-success-400 focus-visible:outline-success-600',
        danger: 'bg-danger-600 hover:bg-danger-500 dark:bg-danger-500 dark:hover:bg-danger-400 focus-visible:outline-danger-600'
    }
    return variants[variant]
}

const baseClass = `rounded-md px-3 py-2 text-center text-sm font-semibold text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 ${getVariantClasses(variant)}`
</script>

<template>
    <Link
        :href="props.href"
        :class="cn(baseClass, props.class)"
    >
       <slot />
    </Link>
</template>

<style scoped></style>
