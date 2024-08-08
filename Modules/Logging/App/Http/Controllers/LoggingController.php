<?php

namespace Modules\Logging\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel as ExportExcel;
use Modules\Logging\App\Http\Requests\LoginLogRequest;
use Modules\Logging\App\Http\Requests\RegisterLogRequest;
use Modules\Logging\App\Http\Requests\StoreLogRequest;
use Modules\Logging\App\Models\Logging;
use Modules\Logging\App\Services\LoggingService;
use Modules\Logging\Exports\ImportExcelLogging;
use Ramsey\Uuid\Uuid;

class LoggingController extends Controller
{
    protected $loggingService;

    public function __construct(LoggingService $loggingService)
    {
        $this->loggingService = $loggingService;
    }

    public function viewLoggingList($saveUuid)
    {
        if (! Uuid::isValid($saveUuid)) {
            return abort(404);
        }

        $validationUser = $this->loggingService->validationUser($saveUuid);

        return DB::transaction(function () use ($validationUser) {
            $loggings = $validationUser['user']->connection->loggings()->latest()->paginate(5);

            return view('logging::layouts.list', [
                'user' => $validationUser['userAuth'],
                'endpoint' => $validationUser['user']->connection['endpoint'],
                'loggings' => $loggings,
            ]);
        });
    }

    public function searchLogging($saveUuid)
    {
        if (! Uuid::isValid($saveUuid)) {
            return abort(404);
        }

        $validationUser = $this->loggingService->validationUser($saveUuid);

        $validationSearch = $this->loggingService->validationSearch();

        if (! is_array($validationSearch)) {
            return redirect('/logging/'.$saveUuid)->with('error', $validationSearch);
        }

        return DB::transaction(function () use ($validationUser, $validationSearch) {
            $loggings = $validationUser['user']->connection->loggings()
                ->where('type_env', $validationSearch['type'])
                ->whereBetween('created_at', [$validationSearch['time-start'], $validationSearch['time-end']])
                ->paginate(2);

            $loggings->appends(request()->query());

            return view('logging::layouts.list', [
                'user' => $validationUser['userAuth'],
                'endpoint' => $validationUser['user']->connection['endpoint'],
                'loggings' => $loggings,
            ]);
        });
    }

    public function viewCreate($saveUuid)
    {
        if (! Uuid::isValid($saveUuid)) {
            return abort(404);
        }

        $validationUser = $this->loggingService->validationUser($saveUuid);

        return view('logging::layouts.create', [
            'connection' => $validationUser['user']->connection,
        ]);
    }

    public function storeCreate(StoreLogRequest $request)
    {
        $jwtToken = $request->cookie('jwt_token', null);

        if (! $jwtToken) {
            return redirect('/logging/login')->with('error', 'You not logged in yet!');
        }

        $validateData = $request->validated();
        $endpoint = $this->loggingService->endpointSelection($validateData);

        try {
            $logData = $this->loggingService->fetchEndpoint($validateData, $jwtToken, $endpoint);
            $successMessage = $this->loggingService->handleLogActions($validateData, $logData);

            return redirect('/logging/'.Auth::user()->uuid)->with('success', $successMessage);
        } catch (\Exception $error) {
            return redirect('/logging/'.Auth::user()->uuid)->with('error', $error->getMessage());
        }
    }

    public function viewLogging($saveUuid)
    {
        if (! Uuid::isValid($saveUuid)) {
            return abort(404);
        }

        $result = Logging::where('uuid', $saveUuid)->first();

        if (! $result) {
            return abort(404);
        }

        if (Auth::user()->connection->uuid != $result->connection_uuid) {
            return abort(404);
        }

        $logLevel = ['info', 'emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'debug', 'other'];
        $logFiles = array_filter(array_intersect_key($result->toArray(), array_flip($logLevel)));

        $logDetails = [];
        $logCounts = array_fill_keys($logLevel, 0);

        foreach ($logFiles as $key => $file) {
            $absolutePath = storage_path('app/'.$file);

            if (! file_exists($absolutePath)) {
                abort(404, 'File not found.');
            }

            $import = new ImportExcelLogging();
            ExportExcel::import($import, $absolutePath);

            $logDetails[$key] = $import->allData;
            $logCounts[$key] = $import->rowCount;
        }

        return view('logging::layouts.show', [
            'result' => $result,
            'logDetails' => $logDetails,
            'logFiles' => $logFiles,
            'logCounts' => $logCounts,
        ]);
    }

    public function downloadLogs($saveUuid)
    {
        if (! Uuid::isValid($saveUuid)) {
            return abort(404);
        }

        $logs = Logging::where('uuid', $saveUuid)->first();

        if (! $logs) {
            return abort(404);
        }

        if (Auth::user()->connection->uuid != $logs->connection_uuid) {
            return abort(404);
        }

        $logLevel = ['info', 'emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'debug', 'other'];
        $logFiles = array_filter(array_intersect_key($logs->toArray(), array_flip($logLevel)));

        if (empty($logFiles)) {
            return abort(404);
        }

        $zipFileName = 'logs_environment_'.$logs->type_env.'_type_'.$logs->type_log.'_'.$logs->created_at->format('Y_M_d_H_i_s').'.zip';
        $zipFilePath = storage_path('app/public/'.$zipFileName);

        $zip = new \ZipArchive();

        if ($zip->open($zipFilePath, \ZipArchive::CREATE) !== true) {
            return abort(404);
        }

        $folderName = pathinfo($zipFileName, PATHINFO_FILENAME);
        $zip->addEmptyDir($folderName);

        foreach ($logFiles as $file) {
            $filePath = storage_path('app/'.$file);

            if (file_exists($filePath)) {
                $fileName = preg_replace('/_[a-f0-9-]{36}/i', '', basename($file));
                $zip->addFile($filePath, $folderName.'/'.$fileName);
            } else {
                return abort(404);
            }
        }

        $zip->close();

        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }

    public function downloadLog($saveUuid, $saveType)
    {
        if (! Uuid::isValid($saveUuid)) {
            return abort(404);
        }

        $logLevel = ['info', 'emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'debug', 'other'];

        if (! in_array($saveType, $logLevel)) {
            return abort(404);
        }

        $result = Logging::where('uuid', $saveUuid)->first();

        if (! $result) {
            return abort(404);
        }

        if (Auth::user()->connection->uuid != $result->connection_uuid) {
            return abort(404);
        }

        $logFiles = array_filter(array_intersect_key($result->toArray(), array_flip($logLevel)));

        $filePath = $logFiles[$saveType] ?? null;

        if (! $filePath) {
            return abort(404, 'File path not found.');
        }

        $absolutePath = storage_path('app/'.$filePath);

        if (! file_exists($absolutePath)) {
            return abort(404, 'File not found.');
        }

        $timestamp = date('Y-m-d_H-i-s');
        $customFileName = "Log_{$saveType}_{$timestamp}.xlsx";

        return response()->download($absolutePath, $customFileName);
    }

    public function viewLogin()
    {
        return view('logging::layouts.login');
    }

    public function storeLogin(LoginLogRequest $request)
    {
        $validateData = $request->validated();
        $user = Auth::user();

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$user->connection->token,
                'Accept' => 'application/json',
            ])->post($user->connection->login, $validateData);

            $response->throw();

            $responseBody = json_decode($response->getBody(), true);
            $jwtToken = $responseBody['data'];

            return redirect("/logging/$user->uuid/create")
                ->with('success', 'Success login account connection!')
                ->cookie('jwt_token', $jwtToken, 60, null, null, false, true);
        } catch (\Illuminate\Http\Client\RequestException $error) {
            $responseBody = $error->response->json();
            $errorMessage = $responseBody['error'] ?? $responseBody['message'] ?? $responseBody['errors'] ?? 'An error occurred';

            return redirect('/logging/login')->with('error', $errorMessage);
        } catch (\Exception $error) {
            return redirect('/logging/login')->with('error', $error->getMessage());
        }
    }

    public function viewRegister()
    {
        return view('logging::layouts.register');
    }

    public function storeRegister(RegisterLogRequest $request)
    {
        $validateData = $request->validated();
        $connection = Auth::user()->connection;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$connection->token,
                'Accept' => 'application/json',
            ])->post($connection->register, $validateData);

            $response->throw();

            return redirect('/logging/login')->with('success', 'Success register account connection! Login now');
        } catch (\Illuminate\Http\Client\RequestException $error) {
            $responseBody = $error->response->json();
            $errorMessage = $responseBody['error'] ?? $responseBody['message'] ?? $responseBody['errors'] ?? 'An error occurred';

            return redirect('/logging/register')->with('error', $errorMessage);
        } catch (\Exception $error) {
            return redirect('/logging/register')->with('error', $error->getMessage());
        }
    }

    public function deleteLogging($saveUuid)
    {
        if (! Uuid::isValid($saveUuid)) {
            return abort(404);
        }

        $result = Logging::where('uuid', $saveUuid)->first();

        if (! $result) {
            return abort(404);
        }

        $user = Auth::user();

        if ($user->connection->uuid != $result->connection_uuid) {
            return abort(404);
        }

        $logLevel = ['info', 'emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'debug', 'other'];
        $logFiles = array_filter(array_intersect_key($result->toArray(), array_flip($logLevel)));
        $secondaryPath = dirname(array_values($logFiles)[0] ?? null);

        $filteredPathParts = array_filter(explode('/', $secondaryPath), function ($typeLog) {
            return ! in_array($typeLog, ['local', 'testing', 'production', 'other']);
        });

        $primaryPath = implode('/', $filteredPathParts);

        if (Storage::exists($secondaryPath)) {
            Storage::deleteDirectory($secondaryPath);

            $countDir = count(Storage::allDirectories($primaryPath));

            if ($countDir === 0) {
                Storage::deleteDirectory($primaryPath);
            }
        }

        $result->delete();

        return redirect('/logging/'.$user->uuid)->with('success', 'Success deleted your log!');
    }
}
