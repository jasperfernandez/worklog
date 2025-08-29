import { ref } from 'vue';

type FileTypes = '.pdf' | '.doc' | '.docx' | '.txt' | '.jpg' | '.jpeg' | '.png' | '.gif' | '.webp' | '.zip' | '.rar' | '.xlsx' | '.xls' | '.pptx' | '.ppt' | '.csv';

interface UseFileUploadOptions {
    maxFiles?: number;
    maxFileSize?: number; // in bytes
    acceptedTypes?: FileTypes[];
}

export function useFileUpload(options: UseFileUploadOptions = {}) {
    const {
        maxFiles = 10,
        maxFileSize = 10 * 1024 * 1024, // 10MB default
        acceptedTypes = [
            '.pdf', '.doc', '.docx', '.txt',
            '.jpg', '.jpeg', '.png', '.gif', '.webp',
            '.zip', '.rar',
            '.xlsx', '.xls', '.pptx', '.ppt', '.csv',
        ]
    } = options;

    const fileInput = ref<HTMLInputElement | null>(null);
    const selectedFiles = ref<File[]>([]);
    const errors = ref<string[]>([]);

    const handleFileSelect = (event: Event): void => {
        const target = event.target as HTMLInputElement;
        errors.value = [];

        if (target.files) {
            const newFiles = Array.from(target.files);
            const validFiles: File[] = [];

            for (const file of newFiles) {
                // Check file size
                if (file.size > maxFileSize) {
                    errors.value.push(`${file.name} is too large. Maximum size is ${formatFileSize(maxFileSize)}.`);
                    continue;
                }

                // Check if adding this file would exceed max files
                if (selectedFiles.value.length + validFiles.length >= maxFiles) {
                    errors.value.push(`You can only upload up to ${maxFiles} files.`);
                    break;
                }

                validFiles.push(file);
            }

            selectedFiles.value = [...selectedFiles.value, ...validFiles];

            // Reset the input to allow selecting the same file again if needed
            target.value = '';
        }
    };

    const removeFile = (index: number): void => {
        selectedFiles.value.splice(index, 1);
        errors.value = [];
    };

    const clearFiles = (): void => {
        selectedFiles.value = [];
        errors.value = [];
    };

    const formatFileSize = (bytes: number): string => {
        const units = ['B', 'KB', 'MB', 'GB'];
        let size = bytes;
        let unitIndex = 0;

        while (size >= 1024 && unitIndex < units.length - 1) {
            size /= 1024;
            unitIndex++;
        }

        return `${size.toFixed(1)} ${units[unitIndex]}`;
    };

    const triggerFileInput = (): void => {
        fileInput.value?.click();
    };

    const getAcceptAttribute = (): string => {
        return acceptedTypes.join(',');
    };

    return {
        // Refs
        fileInput,
        selectedFiles,
        errors,

        // Methods
        handleFileSelect,
        removeFile,
        clearFiles,
        formatFileSize,
        triggerFileInput,
        getAcceptAttribute,

        // Config
        maxFiles,
        maxFileSize,
        acceptedTypes,
    };
}
