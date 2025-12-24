<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
