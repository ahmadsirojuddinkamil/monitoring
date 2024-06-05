<?php

namespace Modules\Dashboard\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\User\App\Models\User;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function viewDashboard()
    {
        // Role::create(['name' => 'administrator']);
        // Role::create(['name' => 'vip']);

        // $user = User::latest()->first();
        // $user->assignRole('administrator');
        // $user->removeRole('vip');

        // dd($user->hasRole('administrator'));
        // dd($user->hasRole('vip'));

        return view('dashboard::layouts.index');
    }
}
