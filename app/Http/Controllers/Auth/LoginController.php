<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\RolesAccess;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function redirectTo()
    {
        $roleId = Auth::user()->role;

        $access = RolesAccess::from('roles_access as ra')
            ->select('m.module_url')
            ->leftJoin('modules as m', 'm.id', '=', 'ra.module_id')
            ->where('ra.role_id', $roleId)
            ->where('ra.can_read', 1)
            ->orderBy('m.module_order', 'asc')
            ->first();

        if ($access) {
            return $access->module_url;
        }

        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return '/login';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
