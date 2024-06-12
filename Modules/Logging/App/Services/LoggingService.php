<?php

namespace Modules\Logging\App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Modules\Connection\App\Models\Connection;
use Modules\Logging\App\Models\Logging;
use Modules\User\App\Models\User;

class LoggingService
{
    public function validationUser($saveUuid)
    {
        $user = User::with('connection.loggings')->where('uuid', $saveUuid)->firstOrFail();

        if (! $user) {
            return abort(404);
        }

        $userAuth = Auth::user();

        if ($userAuth->uuid != $saveUuid) {
            return abort(404);
        }

        return [
            'userAuth' => $userAuth,
            'user' => $user,
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

    public function generateConnectionLog($saveUser)
    {
        $connection = Connection::factory()->create([
            'user_uuid' => $saveUser->uuid,
        ]);

        Logging::factory()->create([
            'connection_uuid' => $connection->uuid,
        ]);
    }
}
