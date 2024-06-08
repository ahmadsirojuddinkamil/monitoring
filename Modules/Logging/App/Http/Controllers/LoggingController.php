<?php

namespace Modules\Logging\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\User\App\Models\User;
use Modules\User\App\Services\UserService;
use Ramsey\Uuid\Uuid;

class LoggingController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function viewMyLogging($saveUuidFromCall)
    {
        $userAuth = $this->userService->userAuth();

        if (! Uuid::isValid($saveUuidFromCall)) {
            return redirect('/logging/'.$userAuth->uuid)->with(['error' => 'Invalid logging data!']);
        }

        $user = User::with('connection')->where('uuid', $saveUuidFromCall)->first();

        if (! $user) {
            return abort(404);
        }

        $connection = $user->connection->load('loggings');

        if ($userAuth->uuid != $saveUuidFromCall) {
            return abort(404);
        }

        $loggings = $connection->loggings()->latest()->paginate(10);

        return view('logging::layouts.show', [
            'user' => $userAuth,
            'endpoint' => $user->connection['endpoint'],
            'loggings' => $loggings,
        ]);
    }
}
