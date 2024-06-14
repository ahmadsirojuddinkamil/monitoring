<?php

namespace Modules\Logging\App\Http\Controllers;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Logging\App\Http\Requests\RegisterLogRequest;
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
            $loggings = $validationUser['user']->connection->loggings()->latest()->paginate(10);

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

    public function viewLogin()
    {
        return view('logging::layouts.login');
    }

    public function viewRegister()
    {
        return view('logging::layouts.register');
    }

    public function storeRegister(RegisterLogRequest $request)
    {
        $validateData = $request->validated();

        $client = new Client();
        $connection = Auth::user()->connection;

        try {
            $client->post($connection->register, [
                'headers' => [
                    'Authorization' => 'Bearer '.$connection->token,
                    'Accept' => 'application/json',
                ],
                'json' => $validateData,
            ]);

            return redirect('/logging/login')->with('success', 'Success register account connection! Login now');
        } catch (\GuzzleHttp\Exception\ClientException $error) {
            $responseBody = json_decode($error->getResponse()->getBody(), true);
            $errorMessage = $responseBody['error'] ?? $responseBody['message'] ?? $responseBody['errors'] ?? 'An error occurred';

            return redirect('/logging/register')->with('error', $errorMessage);
        } catch (\Exception $error) {
            return redirect('/logging/register')->with('error', $error->getMessage());
        }
    }
}
