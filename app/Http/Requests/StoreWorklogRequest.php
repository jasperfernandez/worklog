<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorklogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'log_date' => ['required', 'date'],
            'files' => ['nullable', 'array', 'max:10'],
            'files.*' => [
                'file',
                'max:10240', // 10MB max per file
                'mimes:pdf,doc,docx,txt,jpg,jpeg,png,gif,webp,zip,rar,xlsx,xls,pptx,ppt,csv'
            ],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'A title is required for your worklog.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'content.required' => 'Please provide content for your worklog.',
            'log_date.required' => 'Please select a log date.',
            'log_date.date' => 'Please provide a valid date.',
            'files.max' => 'You may not upload more than 10 files at once.',
            'files.*.file' => 'Each attachment must be a valid file.',
            'files.*.max' => 'Each file may not be larger than 10MB.',
            'files.*.mimes' => 'File type not supported. Allowed types: PDF, DOC, DOCX, TXT, JPG, JPEG, PNG, GIF, WEBP, ZIP, RAR, XLSX, XLS, PPTX, PPT, CSV.',
        ];
    }
}
