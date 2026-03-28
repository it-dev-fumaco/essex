<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'asset_code' => ['required'],
            'brand' => ['required'],
            'qty' => ['required'],
            'model' => ['required'],
            'serial' => ['required'],
            'mcaddress' => ['required'],
            'status' => ['required'],
            'assetdesc' => ['required'],
        ];
    }
}
