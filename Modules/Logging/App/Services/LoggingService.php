<?php

namespace Modules\Logging\App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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

    public function endpointSelection($saveType)
    {
        $user = Auth::user();

        $endpoints = [
            'get_log' => $user->connection->get_log,
            'get_log_by_type' => $user->connection->get_log_by_type,
            'get_log_by_time' => $user->connection->get_log_by_time,
            'delete_log' => $user->connection->delete_log,
            'delete_log_by_type' => $user->connection->delete_log_by_type,
            'delete_log_by_time' => $user->connection->delete_log_by_time,
        ];

        if (array_key_exists($saveType['type'], $endpoints)) {
            return $endpoints[$saveType['type']];
        } else {
            return redirect("/logging/{$user->uuid}/create")->with('error', 'Type not found!');
        }
    }

    public function fetchEndpoint($saveType, $saveToken, $saveEndpoint)
    {
        $method = in_array($saveType['type'], ['delete_log', 'delete_log_by_type', 'delete_log_by_time']) ? 'delete' : 'post';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$saveToken,
            'Accept' => 'application/json',
        ])->$method($saveEndpoint, $saveType);

        $responseBody = json_decode($response->getBody(), true);

        if (isset($responseBody['data'])) {
            return $responseBody['data'];
        } elseif (isset($responseBody['error'])) {
            throw new \Exception($responseBody['error']);
        }
    }
}
