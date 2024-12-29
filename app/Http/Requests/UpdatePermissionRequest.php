<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // or use auth()->user()->can('permission edit')
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:permissions,name,' . $this->permission->id,
            // Add any other fields you need to validate
        ];
    }
}
