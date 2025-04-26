<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{

    public function create(): View
    {
        return view('auth.register');
    }


    public function store(Request $request): RedirectResponse
    {

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'regex:/^[0-9]{8,10}$/'],
            'identity_country' => ['required', 'in:Jordan,Other'],
            'identity_number' => ['required', 'string', 'min:8', 'max:20'],  //, 'unique:users'
            'city' => ['required', 'string', 'max:100'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'profile_picture' => ['nullable', 'image'],
            'identity_image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'user_type' => ['required', 'in:user,merchant'],
        ]);

        $profilePicturePath = $request->hasFile('profile_picture')
            ? $request->file('profile_picture')->store('profile_pictures', 'public')
            : null;

        $identityImagePath = $request->hasFile('identity_image')
            ? $request->file('identity_image')->store('identity_images', 'public')
            : null;

            $status = $validated['user_type'] === 'merchant' ? 'under_review' : 'active';

        $user = User::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . Str::random(5),
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'identity_number' => $validated['identity_number'],
            'identity_image' => $identityImagePath,
            'profile_picture' => $profilePicturePath,
            'identity_country' => $validated['identity_country'],
            'phone' => $validated['phone'],
            'address' => $request->address ?? null,
            'city' => $validated['city'],
            'status' => $status,
            'user_type' => $validated['user_type'],
        ]);


        event(new Registered($user));

        return redirect()->route('login')->with('success', 'Account created successfully. Please login.');
    }
}
