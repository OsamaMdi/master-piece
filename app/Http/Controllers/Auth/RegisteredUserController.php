<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;

class RegisteredUserController extends Controller
{

    public function create(): View
    {
        return view('auth.register');
    }


    public function store(Request $request): RedirectResponse
{
    $city = $request->input('city');
    $jordanianCities = [
        "Amman", "Irbid", "Zarqa", "Aqaba", "Jerash", "Madaba",
        "Balqa", "Mafraq", "Ajloun", "Karak", "Tafilah", "Ma'an"
    ];

    $request->merge([
        'identity_country' => in_array($city, $jordanianCities) ? 'Jordan' : 'Other',
    ]);

    $validated = $request->validate([
        'name' => ['required', 'string', 'min:2', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users',
            'regex:/@(gmail\.com|hotmail\.com|yahoo\.com)$/i'],
        'phone' => ['required', 'regex:/^7(7|8|9)[0-9]{7}$/'],
        'identity_country' => ['required', 'in:Jordan,Other'],
        'identity_number' => ['required', 'string', 'min:8', 'max:20'],
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
        'phone' => '+962' . $validated['phone'],
        'address' => $request->address ?? null,
        'city' => $validated['city'],
        'status' => $status,
        'user_type' => $validated['user_type'],
    ]);

    if ($user->user_type === 'merchant') {
        $admins = User::where('user_type', 'admin')->get();
        foreach ($admins as $admin) {
            NotificationService::send(
                $admin->id,
                'A new merchant registration request was submitted by ' . $user->name,
                'merchant_registration',
                url('/admin/users/' . $user->id),
                'important',
                $user->id
            );
        }
    }

    event(new Registered($user));

    return redirect()->route('login')->with('success', 'Account created successfully. Please login.');
}


}
