<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.partials.update-profile-information-form', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */

public function update(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'name' => 'required|string|min:2|max:50',
        'identity_country' => 'required|in:Jordan,Other',
        'city' => 'required|string|min:2|max:50',
        'address' => 'required|string|min:5|max:255',
        'phone' => ['required', 'regex:/^07[789]\d{7}$/'],
        'profile_picture' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
    ]);

    $user = $request->user();

    if ($request->hasFile('profile_picture')) {
        if ($user->profile_picture) {
            Storage::delete('public/' . $user->profile_picture);
        }

        $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
        $validated['profile_picture'] = $profilePicturePath;
    }

    $user->update($validated);

    return redirect()->route('profile')->with('status', 'profile-updated');
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
}
