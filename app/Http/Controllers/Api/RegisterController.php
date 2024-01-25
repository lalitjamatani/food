<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $return = array("status" => 400, 'IsSuccess' => false, "message" => $validator->errors()->first(), "data" => (object)array());
            return $return;
            die;
        } else {
            $data = $request->all();
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            if (!empty($data['role']) && $data['role'] == '1') {
                $role = Role::where('name', 'users')->first();
                if (empty($role)) {
                    $role = new Role();
                    $role->name = 'users';
                    $role->save();
                }
                $user->assignRole('users');
            } else {
                $role = Role::where('name', 'admin')->first();
                if (empty($role)) {
                    $role = new Role();
                    $role->name = 'admin';
                    $role->save();
                }
                $user->assignRole('admin');
            }

            $token = $user->createToken($request->email)->plainTextToken;

            return response([
                'token' => $token,
                'message' => 'User Registered Successfully',
                'status' => '200',
                'user' => $user
            ]);
        }
    }

    public function login(Request $request){
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
            if ($user->hasRole('users')) {
                $token = $user->createToken($request->email)->plainTextToken;
                return response([
                    'token' => $token,
                    'message' => 'User Login Successfully.',
                    'status' => '200',
                    'user' => $user
                ]);
                // return redirect()->intended('home');
            } elseif ($user->hasRole('admin')) {
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
        }
    }

    public function logout(Request $request){
        $user = Auth::guard('sanctum')->user();
        if(!empty($user)){
            $user->tokens()->delete();
        }
        // Auth::logout();
        return response([
            'message' => 'Logout Successfully.',
            'status' => 'success',
        ], 200);
    }
}
