<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        Log::info('Google SSO Redirect called');
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        Log::info('Google SSO Callback started - URL: ' . request()->fullUrl());

        try {
            if (Auth::check()) {
                Log::info('Google SSO - User already logged in');
                return redirect()->route('dashboard')->with([
                    'google_already_logged_in' => true,
                    'google_error_detail' => 'User ID: ' . Auth::id() . ' (sudah login) - Sesi: ' . session()->getId(),
                ]);
            }

            $googleUser = Socialite::driver('google')->user();
            Log::info('Google user retrieved: ' . print_r($googleUser, true));

            if (!$googleUser || !$googleUser->getEmail()) {
                throw new \Exception('Invalid Google user data');
            }

            $email = $googleUser->getEmail();
            $name = $googleUser->getName() ?? explode('@', $email)[0];

            $user = User::where('user_email', $email)->first();

            if ($user && $user->user_type_login !== 'google') {
                Log::info('Google SSO - Email exists with different login type');
                return redirect()->route('home')->with([
                    'google_email_exists' => true,
                    'google_error_detail' => 'Email wujud dengan jenis login: ' . $user->user_type_login . ' (user_id: ' . $user->user_id . ')',
                ]);
            }

            if (!$user) {
                Log::info('Google SSO - Creating new user for: ' . $email);
                $user = User::create([
                    'user_email' => $email,
                    'user_hash_password' => bcrypt(Str::random(40)),
                    'user_type_login' => 'google',
                    'user_ai_usage' => 0,
                    'user_status' => 'active',
                    'user_created_at' => now(),
                ]);

                $user->defaults()->create([
                    'default_internship_period' => 90,
                    'default_department' => 'Latihan Industri',
                    'default_company' => '',
                    'default_job_scope' => '',
                    'default_status' => 'active',
                    'default_created_at' => now(),
                ]);
            }

            Auth::login($user);
            Log::info('Google SSO - User logged in: ' . $user->user_email . ', Session ID: ' . session()->getId());

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            Log::error('Google SSO Error: ' . $e->getMessage());

            $errorMessage = match(true) {
                str_contains($e->getMessage(), 'invalid_grant') => 'Sesi Google sudah tamat. Sila cuba lagi.',
                str_contains($e->getMessage(), 'Malformed') => 'Kod Google tidak sah. Sila cuba lagi.',
                default => 'Gagal log masuk dengan Google. Sila cuba lagi.'
            };

            return redirect()->route('home')->with([
                'google_error' => $errorMessage,
                'google_error_detail' => $e->getMessage(),
            ]);
        }
    }
}