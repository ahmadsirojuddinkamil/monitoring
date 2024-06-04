<?php

namespace Modules\Connection\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConnectionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'endpoint' => 'required|string',
            'register' => 'required|string',
            'login' => 'required|string',
            'get_log' => 'required|string',
            'get_log_by_type' => 'required|string',
            'get_log_by_time' => 'required|string',
            'delete_log' => 'required|string',
            'delete_log_by_type' => 'required|string',
            'delete_log_by_time' => 'required|string',
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
