<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KioskLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        if ((bool) $this->input('using_access_id')) {
            return [
                'access_id' => ['required'],
                'password' => ['required'],
            ];
        }

        return [
            'id_key' => ['required'],
        ];
    }
}
