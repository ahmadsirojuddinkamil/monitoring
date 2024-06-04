<?php

namespace Modules\User\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string',
            'email' => "required|email|unique:users,email,{$this->user()->id}",
            'old_profile' => 'nullable|string',
            'new_profile' => 'nullable|file|max:1028|mimes:jpg,jpeg,png',
            'old_password' => 'nullable|string',
            'new_password' => 'nullable|string',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
