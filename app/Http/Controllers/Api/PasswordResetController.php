<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class PasswordResetController extends Controller
{
    public function send_reset_pass_email(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;

        // check user exist or not
        $user = User::where('email', $email)->first();

        if (empty($user)) {
            return response([
                'message' => 'email does not exist',
                'status'  => 'failed'
            ]);
        }

        // generate token
        $token = Str::random(60);

        // saving data in password reset
        PasswordReset::create([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        // sending email with password reset view
        Mail::send('email/reset', ['token' => $token], function (Message $message) use ($email) {
            $message->subject('Reset your password');
            $message->to($email);
        });

        return response([
            'message' => 'email send successfully',
            'status'  => 'success',
        ]);
    }

    public function reset_pass(Request $request, $token){
        $formated = Carbon::now()->subMinutes(1)->toDateTimeString();
        PasswordReset::where('created_at', '<=', $formated)->delete();

        $password_reset = PasswordReset::where('token', $token)->first();

        if (empty($password_reset)) {
            return response([
                'message' => "token is expired or invalid",
                'status'  => 'failed'
            ], 400);
        }

        return response([
            'token' => $token,
            'status' => 200,
        ]);

        return view('auth.passwords.reset', compact('token'));
    }

    public function reset(Request $request, $token)
    {
        $request->validate([
            'password' => 'required|confirmed'
        ]);

        // $token = $request->token;
        $password_reset = PasswordReset::where('token', $token)->first();

        if (empty($password_reset)) {
            return response([
                'message' => "token is expired or invalid",
                'status'  => 'failed'
            ], 400);
        }

        $user = User::where('email', $password_reset->email)->first();

        $user->password = Hash::make($request->password);
        $user->save();

        // deleting token after resting password
        PasswordReset::where('email', $user->email)->delete();

        return  response([
            'message' => 'password changed successfully',
            'status'  => '200'
        ], 200);
    }
}
