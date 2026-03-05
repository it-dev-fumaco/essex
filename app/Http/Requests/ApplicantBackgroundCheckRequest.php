<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicantBackgroundCheckRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'examinee_name' => ['required'],
            'name_interview' => ['required'],
            'answer' => ['required', 'array'],
            'answer.*' => ['required'],
        ];
    }
}
