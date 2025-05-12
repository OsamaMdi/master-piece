<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Review;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\WebsiteReview;
use App\Helpers\IdentityHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserBlockedNotification;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $query = User::query()
            ->where('id', '!=', auth()->id());


        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }


        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }


        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);
        }


        $query->orderBy('created_at', 'desc');

        $users = $query->paginate(20);

        return view('admin.users.index', compact('users'));
    }


    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'status' => ['required', 'in:active,blocked,under_review'],
            'user_type' => ['required', 'in:user,merchant,admin,delivery'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'identity_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'identity_number' => ['nullable', 'string', 'min:8', 'max:20'],
            'identity_country' => ['nullable', 'in:Jordan,Other'],
            'city' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'product_limit' => ['nullable', 'integer', 'min:1'],
        ]);

        $profilePicturePath = $request->hasFile('profile_picture')
            ? $request->file('profile_picture')->store('profile_pictures', 'public')
            : null;

        $identityImagePath = null;
        $identityData = [];
        $detectedCity = null;

        if ($request->hasFile('identity_image')) {
            $identityImagePath = $request->file('identity_image')->store('identity_images', 'public');
            $fullPath = storage_path('app/public/' . $identityImagePath);

            $identityText = \App\Helpers\IdentityHelper::scanIdentity($fullPath);

            if (!Str::contains($identityText, ['الرقم الوطني', 'Name', 'National', 'الجنسية'])) {
                Storage::disk('public')->delete($identityImagePath);
                return back()->withErrors(['identity_image' => 'Please upload a valid national ID image.'])->withInput();
            }

            $identityData = \App\Helpers\IdentityHelper::extractDataFromText($identityText);
            $detectedCity = \App\Helpers\IdentityHelper::extractCity($identityText);
        }

        User::create([
            'name' => $validated['name'] ?? $identityData['name'] ?? 'Unnamed User',
            'slug' => Str::slug($validated['name'] ?? $identityData['name'] ?? 'user') . '-' . Str::random(5),
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'status' => $validated['status'],
            'user_type' => $validated['user_type'],
            'profile_picture' => $profilePicturePath,
            'identity_image' => $identityImagePath,
            'identity_number' => $validated['identity_number'] ?? $identityData['national_id'] ?? null,
            'identity_country' => $validated['identity_country'] ?? 'Jordan',
            'city' => $validated['city'] ?? $detectedCity ?? null,
            'address' => $validated['address'] ?? null,
            'product_limit' => $request->input('product_limit', 10),
            'phone' => $validated['phone'] ?? '0000000000',
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }


    public function show(User $user)
{
    $filter = request('filter');

    $productReviews = Review::with('product')
        ->where('user_id', $user->id)
        ->latest()
        ->paginate(10, ['*'], 'product_page');

    $websiteReviews = WebsiteReview::where('user_id', $user->id)
        ->latest()
        ->paginate(10, ['*'], 'website_page');

    $totalReviewsCount = $productReviews->total() + $websiteReviews->total();

    return view('admin.users.show', compact('user', 'productReviews', 'websiteReviews', 'totalReviewsCount', 'filter'));
}


    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'regex:/^([0-9]{8,10})$/'],
            'status' => ['required', 'in:active,blocked,under_review'],
            'user_type' => ['required', 'in:user,merchant,admin,delivery'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'identity_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'identity_number' => ['nullable', 'string', 'min:8', 'max:20'],
            'city' => ['nullable', 'string', 'max:100']
        ]);

        if ($request->hasFile('profile_picture')) {
            $user->profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        if ($request->hasFile('identity_image')) {
            $identityImagePath = $request->file('identity_image')->store('identity_images', 'public');
            $fullPath = storage_path('app/public/' . $identityImagePath);

            $identityText = \App\Helpers\IdentityHelper::scanIdentity($fullPath);

            if (!Str::contains($identityText, ['الرقم الوطني', 'Name', 'National', 'الجنسية'])) {
                Storage::disk('public')->delete($identityImagePath);
                return back()->withErrors(['identity_image' => 'Please upload a valid national ID image.'])->withInput();
            }

            $identityData = \App\Helpers\IdentityHelper::extractDataFromText($identityText);
            $detectedCity = \App\Helpers\IdentityHelper::extractCity($identityText);

            $user->identity_image = $identityImagePath;

            if (!empty($identityData['national_id'])) {
                $user->identity_number = $identityData['national_id'];
            }

            if ($detectedCity) {
                $user->city = $detectedCity;
            }
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'user_type' => $validated['user_type'],
            'status' => $validated['status'],
            'identity_number' => $user->identity_number,
            'city' => $user->city,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function uploadIdentity(Request $request)
    {
        $request->validate([
            'identity_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120']
        ]);

        $image = $request->file('identity_image');
        $path = $image->store('temp_id_images');
        $fullPath = storage_path("app/{$path}");

        $text = IdentityHelper::scanIdentity($fullPath);

        if (str_starts_with($text, "Error:")) {
            return response()->json(['success' => false, 'message' => $text]);
        }

        if (!Str::contains($text, ['Name', 'National ID'])) {
            return response()->json(['success' => false, 'message' => 'The uploaded image does not appear to be a valid identity card.']);
        }

        $data = IdentityHelper::extractDataFromText($text);


        $city = IdentityHelper::extractCity($text);
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
        $cityEn = $cityTranslations[$city] ?? null;

        Storage::delete($path);

        return response()->json([
            'success' => true,
            'name' => $data['name'] ?? null,
            'national_id' => $data['national_id'] ?? null,
            'birth_date' => $data['birth_date'] ?? null,
            'city' => $cityEn,
        ]);
    }


    public function block(Request $request, User $user)
    {
        $request->validate([
            'duration' => 'required',
            'reason' => 'required|string|max:255',
        ]);

        $user->status = 'blocked';
        $user->block_reason = $request->reason;

        if ($request->duration === 'permanent') {
            $user->blocked_until = null;
        } else {
            $user->blocked_until = now()->addDays((int) $request->duration);
        }

        $user->save();

        // إرسال إيميل للمستخدم
        $durationText = $request->duration === 'permanent'
            ? 'Permanent'
            : ((int) $request->duration === 1 ? '1 day' : ((int) $request->duration === 7 ? '1 week' : $request->duration . ' days'));

        Mail::to($user->email)->send(new UserBlockedNotification(
            $user,
            $request->reason,
            $durationText
        ));

        return redirect()->back()->with('status', 'User has been blocked successfully.');
    }

    public function unblock(User $user)
    {
        $user->status = 'active';
        $user->block_reason = null;
        $user->blocked_until = null;
        $user->save();

        return redirect()->back()->with('status', 'User has been unblocked successfully.');
    }

}
