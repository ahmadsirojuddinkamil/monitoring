<?php

namespace Modules\Logging\App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel as ExportExcel;
use Modules\Connection\App\Models\Connection;
use Modules\Logging\App\Models\Logging;
use Modules\Logging\Exports\ExportExcelLogging;
use Modules\User\App\Models\User;
use Ramsey\Uuid\Uuid;

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

        $payload = [
            'type_env' => $saveType['type_env'] ?? null,
            'time_start' => $saveType['time_start'] ?? null,
            'time_end' => $saveType['time_end'] ?? null,
        ];

        $filteredPayload = array_filter($payload, fn ($value) => ! is_null($value));

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$saveToken,
            'Accept' => 'application/json',
        ])->$method($saveEndpoint, $filteredPayload ?: $saveType);

        $responseBody = json_decode($response->getBody(), true);

        if (isset($responseBody['data'])) {
            return $responseBody['data'];
        } elseif (isset($responseBody['error'])) {
            throw new \Exception($responseBody['error']);
        }
    }

    public function generateExportGetLog($directories, $types, $primaryDir, $ownerLog, $logData)
    {
        foreach ($directories as $dir) {
            $listPathGeneral = array_fill_keys($types, null);

            if ($dir == 'other') {
                $uuid = Uuid::uuid4()->toString();
                $pathOther = "$primaryDir/other/other_{$uuid}.xlsx";
                ExportExcel::store(new ExportExcelLogging($logData[$dir] ?? []), $pathOther);

                Logging::createLogging($dir, $pathOther, $listPathGeneral, $ownerLog);
            } else {
                Storage::makeDirectory("$primaryDir/$dir");

                foreach ($types as $type) {
                    if (isset($logData[$dir][$type])) {
                        $uuid = Uuid::uuid4()->toString();
                        $pathGeneral = "$primaryDir/$dir/{$dir}_{$type}_{$uuid}.xlsx";
                        ExportExcel::store(new ExportExcelLogging($logData[$dir][$type]), $pathGeneral);
                        $listPathGeneral[$type] = $pathGeneral;
                    }
                }

                Logging::createLogging($dir, null, $listPathGeneral, $ownerLog);
            }
        }
    }

    public function generateExportGetLogByType($validateData, $logData, $primaryDir, $ownerLog, $types)
    {
        try {
            $typeEnv = $validateData['type_env'];
            $keyNames = array_keys($logData[$typeEnv]);
            $directoryCreated = Storage::makeDirectory("$primaryDir/$typeEnv");

            if ($directoryCreated) {
                $listPathGeneral = array_fill_keys($types, null);

                foreach ($keyNames as $typeName) {
                    $uuid = Uuid::uuid4()->toString();
                    $pathGeneral = "$primaryDir/$typeEnv/{$typeName}_{$uuid}.xlsx";
                    ExportExcel::store(new ExportExcelLogging($logData[$typeEnv][$typeName]), $pathGeneral);
                    $listPathGeneral[$typeName] = $pathGeneral;
                }

                Logging::createLogging($typeEnv, null, $listPathGeneral, $ownerLog);
            } else {
                throw new \Exception();
            }
        } catch (\Exception $error) {
            return redirect('logging/'.Auth::user()->uuid.'/create')->with('error', 'Failed to create directory or export logs.');
        }
    }
}
