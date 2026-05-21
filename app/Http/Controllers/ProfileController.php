<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updateDefaults(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'default_internship_period' => 'required|integer|min:1',
            'default_department' => 'required|string|max:255',
            'default_location' => 'nullable|string|max:255',
            'default_company' => 'nullable|string|max:255',
            'default_job_scope' => 'nullable|string|max:1000',
        ]);

        $defaults = $user->defaults;

        if ($defaults) {
            $defaults->update([
                'default_internship_period' => $validated['default_internship_period'],
                'default_department' => $validated['default_department'],
                'default_location' => $validated['default_location'] ?? '',
                'default_company' => $validated['default_company'] ?? '',
                'default_job_scope' => $validated['default_job_scope'] ?? '',
                'default_updated_at' => now(),
            ]);
        } else {
            $user->defaults()->create([
                'default_internship_period' => $validated['default_internship_period'],
                'default_department' => $validated['default_department'],
                'default_location' => $validated['default_location'] ?? '',
                'default_company' => $validated['default_company'] ?? '',
                'default_job_scope' => $validated['default_job_scope'] ?? '',
                'default_status' => 'active',
                'default_created_at' => now(),
            ]);
        }

        return response()->json(['success' => true]);
    }
}
