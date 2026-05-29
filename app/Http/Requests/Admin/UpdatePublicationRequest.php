<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePublicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $publicationId = $this->route('publication')?->id;

        return [
            'title'        => ['sometimes', 'required', 'string', 'max:255'],
            'slug'         => ['sometimes', 'nullable', 'string', 'max:255', Rule::unique('publications', 'slug')->ignore($publicationId)],
            'tag'          => ['sometimes', 'nullable', 'string', 'max:60'],
            'category'     => ['sometimes', 'nullable', 'string', 'max:100'],
            'year'         => ['sometimes', 'required', 'integer', 'min:1900', 'max:2100'],
            'description'  => ['sometimes', 'required', 'string', 'max:2000'],
            'content'      => ['sometimes', 'nullable', 'string'],
            'accent'       => ['sometimes', 'nullable', 'string', 'max:40'],
            'external_url' => ['sometimes', 'nullable', 'url', 'max:2048'],
            'is_published' => ['sometimes', 'boolean'],
            'published_at' => ['sometimes', 'nullable', 'date'],
            'image'        => ['sometimes', 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'file'         => ['sometimes', 'nullable', 'file', 'mimes:pdf,doc,docx,zip', 'max:20480'],
            'remove_image' => ['sometimes', 'boolean'],
            'remove_file'  => ['sometimes', 'boolean'],
        ];
    }
}
