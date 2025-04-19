<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\IdentityHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $users = $query->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['nullable', 'regex:/^([0-9]{8,10})$/'],
            'status' => ['required', 'in:active,blocked,under_review'],
            'user_type' => ['required', 'in:user,merchant,admin,delivery'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'identity_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'identity_number' => ['nullable', 'string', 'min:8', 'max:20'],
            'city' => ['nullable', 'string', 'max:100']
        ]);

        $profilePicturePath = $request->hasFile('profile_picture')
            ? $request->file('profile_picture')->store('profile_pictures', 'public')
            : null;

        $identityImagePath = $request->hasFile('identity_image')
            ? $request->file('identity_image')->store('identity_images', 'public')
            : null;

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'profile_picture' => $profilePicturePath,
            'identity_image' => $identityImagePath,
            'user_type' => $validated['user_type'],
            'status' => $validated['status'],
            'identity_number' => $validated['identity_number'] ?? null,
            'city' => $validated['city'] ?? null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
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
            $user->identity_image = $request->file('identity_image')->store('identity_images', 'public');
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'user_type' => $validated['user_type'],
            'status' => $validated['status'],
            'identity_number' => $validated['identity_number'] ?? null,
            'city' => $validated['city'] ?? null,
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
        Storage::delete($path);

        return response()->json([
            'success' => true,
            'name' => $data['name'] ?? null,
            'national_id' => $data['national_id'] ?? null,
            'birth_date' => $data['birth_date'] ?? null,
        ]);
    }
}
