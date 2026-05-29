<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePublicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'slug'         => ['nullable', 'string', 'max:255', 'unique:publications,slug'],
            'tag'          => ['nullable', 'string', 'max:60'],
            'category'     => ['nullable', 'string', 'max:100'],
            'year'         => ['required', 'integer', 'min:1900', 'max:2100'],
            'description'  => ['required', 'string', 'max:2000'],
            'content'      => ['nullable', 'string'],
            'accent'       => ['nullable', 'string', 'max:40'],
            'external_url' => ['nullable', 'url', 'max:2048'],
            'is_published' => ['sometimes', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'image'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'file'         => ['nullable', 'file', 'mimes:pdf,doc,docx,zip', 'max:20480'],
        ];
    }
}
