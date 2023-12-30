<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use Validator;
use Auth;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function redirectTo()
    {
        if (auth()->user()->hasRole('admin')) {
            return '/home';
        }
        return '/home';
    }

    protected function validateLogin($request)
    {
        // dd($this->username());
        $validator = Validator::make($request->all(), [
            $this->username() => 'required',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        } else {
            $user = User::where('email', $request->email)->first();
            if ($user == null) {
                throw new \Exception("These credentials do not match our records.");
            }
            if (!empty($request->role) && $request->role == '1' && $user->hasRole('users')) {
                return redirect()->intended('home');
            } elseif (empty($request->role) && $request->role == '0' && $user->hasRole('admin')) {
                return redirect()->intended('home');
            } else {
                throw new \Exception("You are not allowed to login from here.");
            }
            return $validator;
        }
        return $validator;
    }

    public function logout()
    {
        if (auth()->user()->hasRole('admin')) {
            Auth::logout();
            return redirect('/admin_login');
        }else{
            Auth::logout();
            return redirect('/login');
        }
    }

}
