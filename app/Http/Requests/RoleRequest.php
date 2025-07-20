<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
  
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            //

            'name' => 'required|unique:roles,name,' . $this->route('role')?->id,
            'permissions' => 'nullable|array',

        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Role name is required',
            'name.unique' => 'Role name already exists',
            'permissions.array' => 'Permissions must be an array',
        ];
    }
}
