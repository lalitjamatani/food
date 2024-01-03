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
            'email' => 'required',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response([
                'message' => $validator->errors()->first(),
                'status' => '400',
            ]);
            // throw new \Exception($validator->errors()->first());
        } else {
            $user = User::where('email', $request->email)->first();
            if ($user == null) {
                return response([
                    'message' => 'These credentials do not match our records.',
                    'status' => '400',
                ]);
                // throw new \Exception("These credentials do not match our records.");
            }
            if (!empty($request->role) && $request->role == '1' && $user->hasRole('users')) {
                $token = $user->createToken($request->email)->plainTextToken;
                return response([
                    'token' => $token,
                    'message' => 'User Login Successfully.',
                    'status' => '200',
                    'user' => $user
                ]);
                // return redirect()->intended('home');
            } elseif (empty($request->role) && $request->role == '0' && $user->hasRole('admin')) {
                $token = $user->createToken($request->email)->plainTextToken;
                return response([
                    'token' => $token,
                    'message' => 'Admin Login Successfully.',
                    'status' => '200',
                    'user' => $user
                ]);
                // return redirect()->intended('home');
            } else {
                return response([
                    'message' => 'You are not allowed to login from here.',
                    'status' => '400',
                ]);
                // throw new \Exception("You are not allowed to login from here.");
            }
            return $validator;
        }
        return $validator;
    }

    public function logout()
    {
        Auth::logout();
        // auth()->user()->tokens()->delete();
        return response([
            'message' => 'Logout Successfully.',
            'status' => 'success',
        ], 200);
        // if (auth()->user()->hasRole('admin')) {
        //     Auth::logout();
        //     return redirect('/admin_login');
        // }else{
        //     Auth::logout();
        //     return redirect('/login');
        // }
    }

}
