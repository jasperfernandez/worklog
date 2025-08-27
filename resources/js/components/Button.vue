<script setup lang="ts">

type ButtonVariant = 'primary' | 'secondary' | 'success' | 'danger'

const props = defineProps<{
    type?: 'button' | 'submit' | 'reset' | undefined
    disabled?: boolean
    loading?: boolean
    loadingText?: string
    variant?: ButtonVariant
    class?: string
}>()

const buttonType = props.type ?? 'button'
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

const baseClass = `rounded-lg px-3 py-2 text-sm font-semibold text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 disabled:opacity-50 disabled:cursor-not-allowed ${getVariantClasses(variant)}`
</script>

<template>
    <button
        :type="buttonType"
        :disabled="props.disabled || props.loading"
        :class="baseClass"
    >
        <span v-if="props.loading">{{ props.loadingText || 'Loading...' }}</span>
        <span v-else :class="props.class"><slot /></span>
    </button>
</template>

<style scoped></style>
