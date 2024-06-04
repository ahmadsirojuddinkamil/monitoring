<?php

namespace Modules\User\App\Services;

use Illuminate\Support\Facades\Auth;

class UserService
{
    protected $dataUserAuth;

    public function userAuth()
    {
        if ($this->dataUserAuth === null) {
            $this->dataUserAuth = Auth::user();
        }

        return $this->dataUserAuth;
    }

    public function checkUserNotHaveConnection()
    {
        $connection = Auth::user()->connection;

        if ($connection) {
            return abort(404);
        }
    }

    public function checkUserHaveConnection()
    {
        $connection = Auth::user()->connection;

        if (!$connection) {
            return abort(404);
        }
    }
}
