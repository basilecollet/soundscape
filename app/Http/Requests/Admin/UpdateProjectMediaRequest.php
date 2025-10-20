<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'featuredImage' => [
                'nullable',
                'image',
                'mimes:jpeg,png,gif,webp',
                'max:10240', // 10MB
                'dimensions:min_width=800,min_height=600',
            ],
            'galleryImages' => ['nullable', 'array', 'max:10'],
            'galleryImages.*' => [
                'required',
                'image',
                'mimes:jpeg,png,gif,webp',
                'max:10240',
                'dimensions:min_width=800,min_height=600',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'featuredImage.dimensions' => 'The featured image must be at least 800x600 pixels.',
            'galleryImages.*.dimensions' => 'Each gallery image must be at least 800x600 pixels.',
            'galleryImages.max' => 'You can upload a maximum of 10 images at once.',
        ];
    }
}
