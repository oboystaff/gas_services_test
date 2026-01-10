<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SendOTPRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\OTP;
use App\Jobs\OTP\SendPasswordChangeOTPSMS;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $result = filter_var($request->validated('username'), FILTER_VALIDATE_EMAIL);

        if (empty($result)) {
            if (!auth()->attempt([
                'phone' => $request->validated('username'),
                'password' => $request->validated('password'),
                'status' => 'Active'
            ])) {
                throw ValidationException::withMessages([
                    'username' => 'Your provided credentials could not be verified.'
                ]);
            }
        }

        if (!empty($result)) {
            if (!auth()->attempt([
                'email' => $request->validated('username'),
                'password' => $request->validated('password'),
                'status' => 'Active'
            ])) {
                throw ValidationException::withMessages([
                    'username' => 'Your provided credentials could not be verified.'
                ]);
            }
        }

        session()->regenerate();

        // if ($request->user()->changed_password_at == null) {
        //     return redirect()->route('auth.change-password');
        // }

        return redirect()->route('dashboard.operational');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('auth.index');
    }

    public function sendOTP()
    {
        return view('auth.send-otp');
    }

    public function sendUserOTP(SendOTPRequest $request)
    {
        $user = User::where('phone', $request->phone)->first();

        if (empty($user)) {
            return redirect()->route('auth.sendOTP')->with('error', 'Phone number does not exist for any of the account!');
        }

        if ($user) {
            dispatch(new SendPasswordChangeOTPSMS($user));
        }

        return redirect()->route('auth.changePassword')->with('status', 'OTP for password reset sent successfully');
    }

    public function changePassword()
    {
        return view('auth.change-password');
    }

    public function changerUserPassword(ChangePasswordRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $otp = OTP::where('code', $data['code'])->first();

        if ($otp) {
            $user = User::where('id', $otp->user_id)->first();
            $user->update($data);
            OTP::where('user_id', $user->id)->delete();
        }

        return redirect()->route('auth.index')->with('status', 'Password changed successfully');
    }
}
