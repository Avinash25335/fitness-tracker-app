<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'min:2', 'max:100', 'regex:/^[a-zA-Z\s]+$/'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'age'      => ['required', 'integer', 'min:10', 'max:120'],
            'gender'   => ['required', 'string', 'in:male,female,other'],
            'height'   => ['required', 'numeric', 'min:50', 'max:300'],
            'weight'   => ['required', 'numeric', 'min:20', 'max:500'],
            'goal'     => ['nullable', 'in:weight_loss,muscle_gain,maintenance'],
        ], [
            'name.required'     => 'Full name is required.',
            'name.min'          => 'Name must be at least 2 characters.',
            'name.max'          => 'Name must not exceed 100 characters.',
            'name.regex'        => 'Name can only contain letters and spaces.',
            'email.required'    => 'Email address is required.',
            'email.email'       => 'Please enter a valid email address.',
            'email.unique'      => 'This email is already registered. Try logging in.',
            'password.required' => 'Password is required.',
            'password.min'      => 'Password must be at least 8 characters long.',
            'password.confirmed' => 'Passwords do not match. Please try again.',
            'age.required'      => 'Age is required.',
            'age.integer'       => 'Age must be a whole number.',
            'age.min'           => 'Age must be at least 10 years.',
            'age.max'           => 'Age must be 120 years or less.',
            'gender.required'   => 'Please select your gender.',
            'gender.in'         => 'Please select a valid gender option.',
            'height.required'   => 'Height is required.',
            'height.numeric'    => 'Height must be a number.',
            'height.min'        => 'Height must be at least 50 cm.',
            'height.max'        => 'Height must be 300 cm or less.',
            'weight.required'   => 'Weight is required.',
            'weight.numeric'    => 'Weight must be a number.',
            'weight.min'        => 'Weight must be at least 20 kg.',
            'weight.max'        => 'Weight must be 500 kg or less.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',
            'age'      => $request->age,
            'gender'   => $request->gender,
        ]);

        $heightMeters = $request->height / 100;
        $bmi = round($request->weight / ($heightMeters * $heightMeters), 2);

        $user->profile()->create([
            'height' => $request->height,
            'weight' => $request->weight,
            'bmi'    => $bmi,
            'goal'   => $request->goal, // ← now saved from registration form
        ]);

        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Welcome to FitCore, ' . $user->name . '! 🎉');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // 🔑 Password Reset Methods
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]
        );

        // Since we use MAIL_MAILER=log, this will go to storage/logs/laravel.log
        Mail::send('emails.password-reset', ['token' => $token, 'email' => $request->email], function($message) use($request){
            $message->to($request->email);
            $message->subject('Reset Password Notification');
        });

        return back()->with('status', 'We have e-mailed your password reset link!');
    }

    public function showResetPassword($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required'
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$reset || !Hash::check($request->token, $reset->token)) {
            return back()->withErrors(['email' => 'Invalid token!']);
        }

        User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_reset_tokens')->where(['email'=> $request->email])->delete();

    }

    // 🔐 Email Verification Methods
    public function showVerifyEmail()
    {
        return view('auth.verify-email');
    }

    public function verifyEmail(\Illuminate\Foundation\Auth\EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect('/dashboard')->with('success', 'Email verified successfully! 🎖️');
    }

    public function resendVerification(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'Verification link sent!');
    }
}
