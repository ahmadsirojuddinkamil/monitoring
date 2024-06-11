<?php

namespace Modules\Logging\App\Services;

use Illuminate\Support\Facades\Auth;
use Modules\User\App\Models\User;
use Illuminate\Validation\ValidationException;

class LoggingService
{
    public function validationUser($saveUuid)
    {
        $userAuth = Auth::user();

        if ($userAuth->uuid != $saveUuid) {
            return abort(404);
        }

        $user = User::with('connection.loggings')->where('uuid', $saveUuid)->firstOrFail();

        if (!$user) {
            return abort(404);
        }

        return [
            'userAuth' => $userAuth,
            'user' => $user
        ];
    }

    public function validationSearch()
    {
        try {
            return request()->validate([
                'type' => 'required|in:local,testing,production',
                'time-start' => 'required|date_format:Y-m-d\TH:i:s',
                'time-end' => 'required|date_format:Y-m-d\TH:i:s',
            ]);
        } catch (ValidationException $errors) {
            return $errors->getMessage();
        }
    }
}
