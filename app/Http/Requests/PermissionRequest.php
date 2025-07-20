<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermissionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $permission = $this->route('permission');
        $permissionId = $permission ? $permission->id : null;

        return [
            'name' => [
                'required',
                'string',
                Rule::unique('permissions', 'name')->ignore($permissionId),
                function ($attribute, $value, $fail) {
                    if (strpos($value, ' ') === false) {
                        $fail('The permission name must contain at least two words (e.g., "edit user").');
                    }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Permission name is required',
            'name.string' => 'Permission name must be a string',
            'name.unique' => 'This permission already exists',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->name) {
            $name = strtolower($this->name);
            $lastSpacePosition = strrpos($name, ' ');

            if ($lastSpacePosition !== false) {
                $action = substr($name, 0, $lastSpacePosition);
                $module = substr($name, $lastSpacePosition + 1);
                $this->merge([
                    'name' => "{$action} {$module}"
                ]);
            }
        }
    }
}