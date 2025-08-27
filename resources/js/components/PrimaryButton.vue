<script setup lang="ts">
import { cn } from '@/lib/utils';

const props = defineProps<{
    type?: 'button' | 'submit' | 'reset' | undefined
    disabled?: boolean
    loading?: boolean
    loadingText?: string
    class?: string
}>()

const buttonType = props.type ?? 'button'
const baseClass = 'rounded-lg bg-primary-600 px-3 py-2 text-sm font-semibold text-white hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 disabled:opacity-50 disabled:cursor-not-allowed'
</script>

<template>
    <button
        :type="buttonType"
        :disabled="props.disabled || props.loading"
        :class="cn(baseClass, props.class)"
    >
        <span v-if="props.loading">{{ props.loadingText || 'Loading...' }}</span>
        <span v-else><slot /></span>
    </button>
</template>

<style scoped></style>
