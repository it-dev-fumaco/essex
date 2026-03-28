<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'option1_img' => ['nullable', 'image', 'mimes:jpeg,jpg,png,PNG,JPEG,JPG', 'max:2048'],
            'option2_img' => ['nullable', 'image', 'mimes:jpeg,jpg,png,PNG,JPEG,JPG', 'max:2048'],
            'option3_img' => ['nullable', 'image', 'mimes:jpeg,jpg,png,PNG,JPEG,JPG', 'max:2048'],
            'option4_img' => ['nullable', 'image', 'mimes:jpeg,jpg,png,PNG,JPEG,JPG', 'max:2048'],
            'qimage.*' => ['nullable', 'image', 'mimes:jpeg,jpg,png,PNG,JPEG,JPG', 'max:2048'],
        ];
    }
}
