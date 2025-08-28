<script setup lang="ts">
import { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

type BannerVariant = 'info' | 'success' | 'warning' | 'danger';

const props = defineProps<{
    class?: HTMLAttributes['class'];
    variant?: BannerVariant;
}>();

const variant = props.variant ?? 'info';

const getVariantClasses = (variant: BannerVariant): string => {
    const variants = {
        info: 'border-blue-200 bg-blue-50 text-blue-800 dark:border-blue-800 dark:bg-blue-900/20 dark:text-blue-300',
        success: 'border-green-200 bg-green-50 text-green-800 dark:border-green-800 dark:bg-green-900/20 dark:text-green-300',
        warning: 'border-yellow-200 bg-yellow-50 text-yellow-800 dark:border-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300',
        danger: 'border-red-200 bg-red-50 text-red-800 dark:border-red-800 dark:bg-red-900/20 dark:text-red-300'
    };
    return variants[variant];
};

const baseClasses = 'rounded-lg border p-4';
</script>

<template>
    <div :class="cn(baseClasses, getVariantClasses(variant), props.class)">
        <p class="text-sm">
            <slot />
        </p>
    </div>
</template>

<style scoped></style>
