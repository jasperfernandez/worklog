import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

// Get today's date in YYYY-MM-DD format
export const getTodayDate = () => {
    const today = new Date();
    return today.toISOString().split('T')[0];
};

// Format a date string to a more readable format, e.g., January 1, 2023
export const formatDate = (dateString: string): string => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};

// Format a date string to a more readable format with time, e.g., Jan 1, 2023, 10:00 AM
export const formatDateTime = (dateString: string): string => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
