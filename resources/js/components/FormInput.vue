<script setup lang="ts">
import { useVModel } from '@vueuse/core';
import { cn } from '@/lib/utils';

interface Props {
    id: string;
    label: string;
    defaultValue?: string | number
    modelValue?: string | number;
    type?: string;
    name?: string;
    placeholder?: string;
    autocomplete?: string;
    required?: boolean;
    error?: string;
    disabled?: boolean;
    showRequiredAsterisk?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    type: 'text',
    name: undefined,
    placeholder: '',
    autocomplete: undefined,
    required: false,
    error: undefined,
    disabled: false,
    showRequiredAsterisk: false,
});


const emits = defineEmits<{
    (e: 'update:modelValue', payload: string | number): void
}>()

const modelValue = useVModel(props, 'modelValue', emits, {
    passive: true,
    defaultValue: props.defaultValue,
})
</script>

<template>
    <div>
        <label
            :for="id"
            class="mb-2 block text-sm font-medium text-text-secondary dark:text-text-secondary-dark"
        >
            {{ label }}
            <span v-if="required && showRequiredAsterisk" class="text-danger-600 dark:text-danger-400 ml-1">*</span>
        </label>
        <input
            v-model="modelValue"
            data-slot="input"
            :id="id"
            :name="name || id"
            :type="type"
            :autocomplete="autocomplete"
            :required="required"
            :disabled="disabled"
            :class="cn(
                'relative block w-full rounded-lg border px-3 py-2 placeholder-text-muted focus:z-10 focus:outline-none sm:text-sm',
                'text-text-primary dark:bg-secondary-700 dark:text-text-primary-dark',
                'border-border dark:border-border-dark',
                'focus:border-primary-500 focus:ring-primary-500 dark:focus:border-primary-400 dark:focus:ring-primary-400',
                'placeholder-text-muted dark:placeholder-text-muted-dark',
                'disabled:opacity-50 disabled:cursor-not-allowed',
                error ? 'border-danger-500 focus:border-danger-500 focus:ring-danger-500 dark:border-danger-400 dark:focus:border-danger-400 dark:focus:ring-danger-400' : ''
            )"
            :placeholder="placeholder"
        />
        <div v-if="error" class="mt-1 text-sm text-danger-600 dark:text-danger-400">
            {{ error }}
        </div>
    </div>
</template>
