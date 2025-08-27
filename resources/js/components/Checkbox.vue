<script setup lang="ts">
import { useVModel } from '@vueuse/core';

interface Props {
    id: string;
    label: string;
    defaultValue?: boolean;
    modelValue?: boolean;
    name?: string;
    required?: boolean;
    error?: string;
    disabled?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    name: undefined,
    required: false,
    error: undefined,
    disabled: false,
});

const emits = defineEmits<{
    (e: 'update:modelValue', payload: boolean): void;
}>();

const modelValue = useVModel(props, 'modelValue', emits, {
    passive: true,
    defaultValue: props.defaultValue,
});
</script>

<template>
    <div class="flex items-center">
        <input
            :id="id"
            v-model="modelValue"
            :name="name"
            type="checkbox"
            class="h-4 w-4 rounded border-border text-primary-600 focus:ring-primary-500 dark:border-border-dark dark:bg-gray-700 dark:focus:ring-primary-400"
        />
        <label :for="id" class="ml-2 block text-sm text-gray-900 dark:text-gray-300"> {{ label }} </label>
    </div>
</template>

<style scoped></style>
