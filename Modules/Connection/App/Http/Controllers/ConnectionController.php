<?php

namespace Modules\Connection\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Connection\App\Http\Requests\StoreConnectionRequest;
use Modules\Connection\App\Http\Requests\UpdateConnectionRequest;
use Modules\Connection\App\Models\Connection;
use Modules\Connection\App\Services\ConnectionService;
use Modules\User\App\Models\User;
use Modules\User\App\Services\UserService;
use Ramsey\Uuid\Uuid;

class ConnectionController extends Controller
{
    protected $connectionService;

    protected $userService;

    public function __construct(ConnectionService $connectionService, UserService $userService)
    {
        $this->connectionService = $connectionService;
        $this->userService = $userService;
    }

    public function create()
    {
        $this->userService->checkUserNotHaveConnection();

        return view('connection::layouts.create');
    }

    public function store(StoreConnectionRequest $request)
    {
        $this->userService->checkUserNotHaveConnection();

        $validateData = $request->validated();

        $uuid = $this->userService->userAuth()->uuid;

        $validationDomain = $this->connectionService->validationDomain($validateData);

        if (! $validationDomain) {
            return redirect('/connection/'.$uuid)->with('error', 'Your endpoint is invalid!');
        }

        if (Connection::where('endpoint', 'LIKE', '%'.$validationDomain.'%')->exists()) {
            return redirect('/connection/'.$uuid)->with('error', 'Connection is already in use!');
        }

        try {
            Connection::createConnection($uuid, $validateData);

            return redirect('/connection/'.$uuid)->with('success', 'Success create connection');
        } catch (\Exception $error) {
            return redirect('/connection/'.$uuid)->with('error', 'An unexpected error occurred: '.$error->getMessage());
        }
    }

    public function viewMyConnection($saveUuidFromCall)
    {
        $userAuth = $this->userService->userAuth();

        if (! Uuid::isValid($saveUuidFromCall)) {
            return redirect('/connection/'.$userAuth->uuid)->with(['error' => 'Invalid connection data!']);
        }

        $user = User::with('connection')->where('uuid', $saveUuidFromCall)->first();

        if (! $user) {
            return abort(404);
        }

        if ($userAuth->uuid != $saveUuidFromCall) {
            return abort(404);
        }

        return view('connection::layouts.show', [
            'connection' => $user->connection,
            'user' => $userAuth,
        ]);
    }

    public function viewListConnection()
    {
        $connections = Connection::with('user')->latest()->get();

        return view('connection::layouts.list', [
            'connections' => $connections,
        ]);
    }

    public function viewOtherConnection($saveUuidFromCall)
    {
        $userAuth = $this->userService->userAuth();

        if (! Uuid::isValid($saveUuidFromCall)) {
            return redirect('/connection/list')->with(['error' => 'Invalid connection data!']);
        }

        $connection = Connection::where('uuid', $saveUuidFromCall)->first();

        if (! $connection) {
            return redirect('/connection/list')->with(['error' => 'Connection not found!']);
        }

        return view('connection::layouts.show', [
            'connection' => $connection,
            'user' => $userAuth,
        ]);
    }

    public function edit($saveUuidFromCall)
    {
        $this->userService->checkUserHaveConnection();
        $userAuth = $this->userService->userAuth();

        if (! Uuid::isValid($saveUuidFromCall)) {
            return redirect('/connection/'.$userAuth->uuid)->with(['error' => 'Invalid connection data!']);
        }

        $user = User::with('connection')->where('uuid', $saveUuidFromCall)->first();

        if (! $user) {
            return abort(404);
        }

        if ($userAuth->uuid != $saveUuidFromCall) {
            return abort(404);
        }

        return view('connection::layouts.edit', [
            'connection' => $user->connection,
        ]);
    }

    public function update(UpdateConnectionRequest $request, $saveUuidFromCall)
    {
        $this->userService->checkUserHaveConnection();

        $validateData = $request->validated();

        $userAuth = $this->userService->userAuth();

        if (! Uuid::isValid($saveUuidFromCall)) {
            return redirect('/connection/'.$userAuth->uuid)->with(['error' => 'Invalid connection data!']);
        }

        $connection = Connection::where('uuid', $saveUuidFromCall)->first();

        if (! $connection) {
            return abort(404);
        }

        if ($userAuth->uuid != $connection['user_uuid']) {
            return abort(404);
        }

        $validationDomain = $this->connectionService->validationDomain($validateData);

        if (! $validationDomain) {
            return redirect('/connection/'.$userAuth->uuid)->with('error', 'Your endpoint is invalid!');
        }

        try {
            Connection::updateConnection($connection->uuid, $validateData);

            return redirect('/connection/'.$userAuth->uuid)->with('success', 'Success update connection');
        } catch (\Exception $error) {
            return redirect('/connection/'.$userAuth->uuid)->with('error', 'An unexpected error occurred: '.$error->getMessage());
        }
    }

    public function delete($saveUuidFromCall)
    {
        if (! Uuid::isValid($saveUuidFromCall)) {
            return redirect('/connection/list')->with(['error' => 'Invalid connection data!']);
        }

        $connection = Connection::where('uuid', $saveUuidFromCall)->first();

        if (! $connection) {
            return redirect('/connection/list')->with(['error' => 'Connection not found!']);
        }

        try {
            $connection->delete();

            return redirect('/connection/list')->with(['success' => 'Success delete connection '.$connection->endpoint]);
        } catch (\Exception $error) {
            return redirect('/connection/list')->with('error', 'An unexpected error occurred: '.$error->getMessage());
        }
    }
}
