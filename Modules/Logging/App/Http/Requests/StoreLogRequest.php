<?php

namespace Modules\Logging\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLogRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'type' => 'required|string|in:get_log,get_log_by_type,get_log_by_time,delete_log,delete_log_by_type,delete_log_by_time',
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
