<?php

namespace Modules\User\App\Services;

use Modules\User\App\Models\User;
use Ramsey\Uuid\Uuid;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function generateRole()
    {
        Role::create(['name' => 'administrator']);
        Role::create(['name' => 'vip']);
    }

    public function assignRoleAdministrator()
    {
        $user = User::first();
        $user->assignRole('administrator');
    }

    public function assignRoleVip()
    {
        $user = User::first();
        $user->assignRole('vip');
    }

    public function generateUser()
    {
        $user = User::create([
            'uuid' => Uuid::uuid4(),
            'username' => 'client',
            'email' => 'client@gmail.com',
            'password' => '12345678',
            'status' => 'belum',
        ]);

        return $user;
    }
}
