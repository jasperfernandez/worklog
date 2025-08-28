import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Worklog {
    id: number;
    title: string;
    content: string;
    log_date: string;
    created_at: string;
    updated_at: string;

    files: WorklogFile[];
}

export interface WorklogFile {
    id: number;
    filename: string;
    original_name: string;
    file_size: number;
    mime_type: string;
    human_file_size: string;
}
