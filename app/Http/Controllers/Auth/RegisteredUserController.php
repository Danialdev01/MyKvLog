<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,user_email'],
                'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            ]);

            $user = User::create([
                'user_email' => $validated['email'],
                'user_hash_password' => Hash::make($validated['password']),
                'user_type_login' => 'email',
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

            event(new Registered($user));

            Auth::login($user);

            return redirect(route('dashboard', absolute: false));
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('Register Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            $errorMessage = match(true) {
                str_contains($e->getMessage(), 'caching_sha2_password') || str_contains($e->getMessage(), '[2054]')
                    => 'Pangkalan data tidak serasi dengan pelayan aplikasi. Sila hubungi pentadbir sistem untuk tukar kaedah auth MySQL.',
                str_contains($e->getMessage(), 'SQLSTATE') => 'Ralat pangkalan data berlaku. Sila hubungi pentadbir sistem.',
                default => 'Pendaftaran gagal. Sila cuba lagi.'
            };

            return redirect()->route('home')->with([
                'register_error' => $errorMessage,
                'register_error_detail' => $e->getMessage() . ' (file: ' . basename($e->getFile()) . ':' . $e->getLine() . ')',
            ]);
        }
    }
}