<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\IdentityHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
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
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|min:2|max:50',
            'identity_country' => 'nullable|in:Jordan,Other',
            'city' => 'nullable|string|min:2|max:50',
            'address' => 'required|string|min:5|max:255',

            // Email must be valid and unique (except current user) and from allowed domains
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
                function ($attribute, $value, $fail) {
                    $allowedDomains = ['gmail.com', 'hotmail.com', 'yahoo.com'];
                    $domain = substr(strrchr($value, "@"), 1);
                    if (!in_array($domain, $allowedDomains)) {
                        $fail('Only Gmail, Hotmail, or Yahoo email addresses are allowed.');
                    }
                },
            ],

            // Phone must be 9 digits and start with 77, 78, or 79
            'phone' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (
                        !preg_match('/^\+9627[789]\d{7}$/', $value) &&
                        !preg_match('/^7[789]\d{7}$/', $value)
                    ) {
                        $fail('Phone must start with +96277, +96278, +96279 or 77, 78, 79 and be 9 digits.');
                    }
                }
            ],


            // Handle profile picture removal
            'remove_profile_picture' => ['nullable', 'boolean'],

            'profile_picture' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'identity_image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        // Remove current profile picture if requested
        if ($request->boolean('remove_profile_picture') && $user->profile_picture) {
            Storage::delete('public/' . $user->profile_picture);
            $user->profile_picture = null;
        }

        // Upload new profile picture if provided
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::delete('public/' . $user->profile_picture);
            }
            $profilePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $profilePath;
        }

        // Handle identity image if uploaded
        if ($request->hasFile('identity_image')) {
            $identityPath = $request->file('identity_image')->store('identity_images', 'public');
            $fullPath = storage_path('app/public/' . $identityPath);

            $text = IdentityHelper::scanIdentity($fullPath);

            if (str_starts_with($text, "Error:")) {
                Storage::delete('public/' . $identityPath);
                return back()->withErrors(['identity_image' => 'Unable to process identity image.'])->withInput();
            }

            if (!Str::contains($text, ['Name', 'National ID', 'الرقم الوطني'])) {
                Storage::delete('public/' . $identityPath);
                return back()->withErrors(['identity_image' => 'Please upload a valid national ID.'])->withInput();
            }

            $data = IdentityHelper::extractDataFromText($text);
            $arabicCity = IdentityHelper::extractCity($text);

            $cityTranslations = [
                'عمان' => 'Amman',
                'اربد' => 'Irbid',
                'الزرقاء' => 'Zarqa',
                'مادبا' => 'Madaba',
                'العقبة' => 'Aqaba',
                'جرش' => 'Jerash',
                'المفرق' => 'Mafraq',
                'البلقاء' => 'Balqa',
                'الكرك' => 'Karak',
                'الطفيلة' => 'Tafilah',
                'معان' => 'Ma\'an',
            ];
            $englishCity = $cityTranslations[$arabicCity] ?? null;

            $user->identity_image = $identityPath;

            if (!empty($data['national_id'])) {
                $user->identity_number = $data['national_id'];
            }

            if ($englishCity) {
                $user->city = $englishCity;
                $user->identity_country = 'Jordan';
            } else {
                $user->identity_country = 'Other';
            }
        }

        // Format phone number to international format before saving
        $inputPhone = $validated['phone'];

        if (Str::startsWith($inputPhone, '+9627')) {
            $formattedPhone = $inputPhone; // Already formatted correctly
        } elseif (preg_match('/^07[789]\d{7}$/', $inputPhone)) {
            $formattedPhone = '+962' . substr($inputPhone, 1); // Convert to international
        } else {
            return back()->withErrors(['phone' => 'Invalid phone number format.'])->withInput();
        }

        // Update user fields
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $formattedPhone,
            'address' => $validated['address'],
            'identity_country' => $user->identity_country,
            'city' => $user->city,
        ]);

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
