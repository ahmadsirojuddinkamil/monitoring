<?php

namespace Modules\Logging\App\Http\Controllers;

use Modules\Logging\App\Services\LoggingService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
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

        return DB::transaction(function () use ($saveUuidFromCall) {
            $validationUser = $this->loggingService->validationUser($saveUuidFromCall);

            $loggings = $validationUser['user']->connection->loggings()->latest()->paginate(10);

            return view('logging::layouts.show', [
                'user' => $validationUser['userAuth'],
                'endpoint' => $validationUser['user']->connection['endpoint'],
                'loggings' => $loggings,
            ]);
        });
    }

    public function searchLogging($saveUuidFromCall)
    {
        if (!Uuid::isValid($saveUuidFromCall)) {
            return abort(404);
        }

        $validationUser = $this->loggingService->validationUser($saveUuidFromCall);

        $validationSearch = $this->loggingService->validationSearch();

        if (!is_array($validationSearch)) {
            return redirect('/logging/' . $saveUuidFromCall)->with('error', $validationSearch);
        }

        return DB::transaction(function () use ($validationUser, $validationSearch) {
            $loggings = $validationUser['user']->connection->loggings()
                        ->where('type', $validationSearch['type'])
                        ->whereBetween('created_at', [$validationSearch['time-start'], $validationSearch['time-end']])
                        ->paginate(2);

            $loggings->appends(request()->query());

            return view('logging::layouts.show', [
                'user' => $validationUser['userAuth'],
                'endpoint' => $validationUser['user']->connection['endpoint'],
                'loggings' => $loggings,
            ]);
        });
    }
}
