<?php

namespace Modules\User\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Modules\User\App\Http\Requests\LoginUserRequest;
use Modules\User\App\Http\Requests\RegisterUserRequest;
use Modules\User\App\Http\Requests\UpdateProfileRequest;
use Modules\User\App\Http\Requests\VipUserRequest;
use Modules\User\App\Models\User;
use Modules\User\App\Services\UserService;
use Ramsey\Uuid\Uuid;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function viewRegister()
    {
        return view('user::layouts.register');
    }

    public function register(RegisterUserRequest $request)
    {
        $validateData = $request->validated();

        $salt = env('SALT_USER');
        $hashPassword = password_hash($salt.$validateData['password'].$salt, PASSWORD_ARGON2I);

        User::create([
            'uuid' => Uuid::uuid4(),
            'username' => $validateData['username'],
            'email' => $validateData['email'],
            'password' => $hashPassword,
            'status' => 'belum',
        ]);

        return redirect('/login')->with('success', 'Successfully create user, please login!');
    }

    public function viewLogin()
    {
        return view('user::layouts.login');
    }

    public function login(LoginUserRequest $request)
    {
        $validateData = $request->validated();

        $user = User::where('email', $validateData['email'])->first();

        if (! $user) {
            return redirect('/login')->with('error', 'Invalid email or password!');
        }

        $salt = env('SALT_USER');
        $passwordInput = $salt.$validateData['password'].$salt;

        if (! password_verify($passwordInput, $user->password)) {
            return redirect('/login')->with('error', 'Invalid email or password!');
        }

        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Successfully logged in!');
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/login')->with('success', 'Successfully logged out!');
    }

    public function viewProfile($saveUuidFromCall)
    {
        if (! preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/dashboard')->with(['error' => 'Invalid profile data!']);
        }

        $user = User::where('uuid', $saveUuidFromCall)->first();

        if (! $user) {
            return redirect('/dashboard')->with(['error' => 'User not found!']);
        }

        $userAuth = $this->userService->userAuth();

        if ($userAuth->uuid != $saveUuidFromCall) {
            return redirect('/dashboard')->with(['error' => 'Invalid profile data!']);
        }

        return view('user::layouts.dashboard.profile', [
            'user' => $user,
        ]);
    }

    public function profileUpdate(UpdateProfileRequest $request, $saveUuidFromCall)
    {
        $validateData = $request->validated();

        if (! preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/dashboard')->with(['error' => 'Invalid profile data!']);
        }

        $user = User::where('uuid', $saveUuidFromCall)->first();

        if (! $user) {
            return redirect('/dashboard')->with(['error' => 'User not found!']);
        }

        $userAuth = $this->userService->userAuth();

        if ($userAuth->uuid != $saveUuidFromCall) {
            return redirect('/dashboard')->with(['error' => 'Invalid profile data!']);
        }

        $passwordHasing = null;

        if (! empty($validateData['old_password']) && ! empty($validateData['new_password'])) {
            $salt = env('SALT_USER');
            $passwordInput = $salt.$validateData['old_password'].$salt;

            if (! password_verify($passwordInput, $user->password)) {
                return redirect("/profile/{$saveUuidFromCall}")->with('error_password', 'Wrong password!');
            }

            $passwordHasing = password_hash($salt.$validateData['new_password'].$salt, PASSWORD_ARGON2I);
        }

        if (! empty($validateData['new_profile'])) {
            $storeFile = $validateData['new_profile']->store('public/profile_user');
            $pathFile = 'storage/profile_user/'.basename($storeFile);

            if ($user->profile != 'assets/dashboard/img/icons/user.png') {
                Storage::delete(str_replace('storage/', 'public/', $user->profile));
            }
        } else {
            $pathFile = $validateData['old_profile'];
        }

        $updateData = [
            'username' => $validateData['username'],
            'email' => $validateData['email'],
            'profile' => $pathFile,
        ];

        if ($passwordHasing) {
            $updateData['password'] = $passwordHasing;
        }

        $user->update($updateData);

        return redirect("/profile/{$saveUuidFromCall}")->with('success', 'Success update your profile!');
    }

    public function viewUserList()
    {
        $users = User::latest()->get();

        return view('user::layouts.dashboard.user_list', [
            'users' => $users,
        ]);
    }

    public function viewEdit($saveUuidFromCall)
    {
        if (! preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/user/list')->with(['error' => 'Invalid user data!']);
        }

        $user = User::where('uuid', $saveUuidFromCall)->first();

        if (! $user) {
            return redirect('/user/list')->with(['error' => 'User not found!']);
        }

        $userAuth = $this->userService->userAuth();

        if ($userAuth->uuid == $saveUuidFromCall) {
            return redirect('/user/list')->with(['error' => 'Cant edit yourself!']);
        }

        return view('user::layouts.dashboard.edit', [
            'user' => $user,
        ]);
    }

    public function edit(VipUserRequest $request, $saveUuidFromCall)
    {
        $validateData = $request->validated();

        if (! preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/user/list')->with(['error' => 'Invalid user data!']);
        }

        $user = User::where('uuid', $saveUuidFromCall)->first();

        if (! $user) {
            return redirect('/user/list')->with(['error' => 'User not found!']);
        }

        $user->update([
            'status' => $validateData['status'],
        ]);

        if ($validateData['status'] == 'bayar') {
            $user->assignRole('vip');
        } else {
            $user->removeRole('vip');
        }

        return redirect('/user/list')->with(['success' => 'User success updated status!']);
    }

    public function delete($saveUuidFromCall)
    {
        if (! preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/user/list')->with(['error' => 'Invalid user data!']);
        }

        $user = User::where('uuid', $saveUuidFromCall)->first();

        if (! $user) {
            return redirect('/user/list')->with(['error' => 'User not found!']);
        }

        $userAuth = $this->userService->userAuth();

        if ($userAuth->uuid == $saveUuidFromCall) {
            return redirect('/user/list')->with(['error' => 'Cant erase yourself!']);
        }

        File::delete($user->profile);
        $user->delete();

        return redirect('/user/list')->with(['success' => 'User success deleted!']);
    }
}
