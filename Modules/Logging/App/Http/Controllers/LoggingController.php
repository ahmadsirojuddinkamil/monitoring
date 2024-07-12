<?php

namespace Modules\Logging\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\Logging\App\Http\Requests\LoginLogRequest;
use Modules\Logging\App\Http\Requests\RegisterLogRequest;
use Modules\Logging\App\Http\Requests\StoreLogRequest;
use Modules\Logging\App\Services\LoggingService;
use Ramsey\Uuid\Uuid;

class LoggingController extends Controller
{
    protected $loggingService;

    public function __construct(LoggingService $loggingService)
    {
        $this->loggingService = $loggingService;
    }

    public function viewMyLogging($saveUuidFromCall)
    {
        if (! Uuid::isValid($saveUuidFromCall)) {
            return abort(404);
        }

        $validationUser = $this->loggingService->validationUser($saveUuidFromCall);

        return DB::transaction(function () use ($validationUser) {
            $loggings = $validationUser['user']->connection->loggings()->latest()->paginate(5);

            return view('logging::layouts.list', [
                'user' => $validationUser['userAuth'],
                'endpoint' => $validationUser['user']->connection['endpoint'],
                'loggings' => $loggings,
            ]);
        });
    }

    public function searchLogging($saveUuidFromCall)
    {
        if (! Uuid::isValid($saveUuidFromCall)) {
            return abort(404);
        }

        $validationUser = $this->loggingService->validationUser($saveUuidFromCall);

        $validationSearch = $this->loggingService->validationSearch();

        if (! is_array($validationSearch)) {
            return redirect('/logging/'.$saveUuidFromCall)->with('error', $validationSearch);
        }

        return DB::transaction(function () use ($validationUser, $validationSearch) {
            $loggings = $validationUser['user']->connection->loggings()
                ->where('type', $validationSearch['type'])
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
            $uuidDirectory = Uuid::uuid4()->toString();
            $primaryDir = "public/{$validateData['type']}/".$uuidDirectory;
            Storage::makeDirectory($primaryDir);

            if (app()->environment('testing')) {
                Log::info("directory testing, $uuidDirectory");
            }

            $directories = ['local', 'testing', 'production', 'other'];
            $types = ['info', 'emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'debug'];

            $logData = $this->loggingService->fetchEndpoint($validateData, $jwtToken, $endpoint);
            $ownerLog = Auth::user()->connection->uuid;

            if ($validateData['type_env'] == null && $validateData['time_start'] == null && $validateData['time_end'] == null) {
                $this->loggingService->generateExportGetLog($directories, $types, $primaryDir, $ownerLog, $logData);
            } elseif ($validateData['type_env'] && $validateData['time_start'] == null && $validateData['time_end'] == null) {
                $this->loggingService->generateExportGetLogByType($validateData, $logData, $primaryDir);
            }

            return redirect('/logging/'.Auth::user()->uuid)->with('success', 'Successfully perform log operations');
        } catch (\Illuminate\Http\Client\RequestException $error) {
            $responseBody = $error->response->json();
            $errorMessage = $responseBody['error'] ?? $responseBody['message'] ?? $responseBody['errors'] ?? 'An error occurred';

            return redirect('/logging/'.Auth::user()->uuid)->with('error', $errorMessage);
        } catch (\Exception $error) {
            return redirect('/logging/'.Auth::user()->uuid)->with('error', $error->getMessage());
        }
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
}
