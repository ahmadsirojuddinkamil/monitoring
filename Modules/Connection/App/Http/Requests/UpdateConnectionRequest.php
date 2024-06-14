<?php

namespace Modules\Connection\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateConnectionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // 'endpoint' => "required|string|unique:connections,endpoint,{$this->user()->connection->id}",
            'endpoint' => [
                'required',
                'string',
                Rule::unique('connections', 'endpoint')->ignore($this->user()->connection->id ?? null),
            ],
            'register' => 'required|string',
            'login' => 'required|string',
            'get_log' => 'required|string',
            'get_log_by_type' => 'required|string',
            'get_log_by_time' => 'required|string',
            'delete_log' => 'required|string',
            'delete_log_by_type' => 'required|string',
            'delete_log_by_time' => 'required|string',
            'token' => 'required|string',
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
