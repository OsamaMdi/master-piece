<x-app-layout>
    <div class="profile-container">

        <!-- Page Title -->
        <div class="text-center mb-10">
            <div class="text-4xl font-bold text-gray-800">
                <span class="text-indigo-600">My</span> Profile
            </div>
        </div>

        <!-- Profile Card -->
        <div class="profile-card">

            <!-- Left: User Image and Email -->
            <div class="profile-left">
                <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : asset('img/default-user.png') }}"
                     alt="Profile Picture" class="profile-avatar">
                <p class="profile-email">{{ auth()->user()->email }}</p>
            </div>

            <!-- Right: Info -->
            <div class="profile-right">
                <h2 class="profile-name">{{ auth()->user()->name }}</h2>

                <div class="profile-info">
                    <!-- City -->
                    <div class="info-item">
                        <label class="info-label">
                            <i class="fas fa-city text-blue-500 mr-2"></i> City
                        </label>
                        <span class="info-value">{{ auth()->user()->city ?? 'Not Provided' }}</span>
                    </div>

                    <!-- Country -->
                    <div class="info-item">
                        <label class="info-label">
                            <i class="fas fa-flag text-blue-500 mr-2"></i> Country
                        </label>
                        <span class="info-value">{{ auth()->user()->identity_country ?? 'Not Provided' }}</span>
                    </div>

                    <!-- Identity Number -->
                    <div class="info-item">
                        <label class="info-label">
                            <i class="fas fa-id-card text-blue-500 mr-2"></i> Identity Number
                        </label>
                        <span class="info-value">{{ auth()->user()->identity_number ?? 'Not Provided' }}</span>
                    </div>

                    <!-- Phone -->
                    <div class="info-item">
                        <label class="info-label">
                            <i class="fas fa-phone text-blue-500 mr-2"></i> Phone
                        </label>
                        <span class="info-value">{{ auth()->user()->phone ?? 'Not Provided' }}</span>
                    </div>

                    <!-- Address -->
                    <div class="info-item">
                        <label class="info-label">
                            <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i> Address
                        </label>
                        <span class="info-value">{{ auth()->user()->address ?? 'Not Provided' }}</span>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="profile-buttons">
                    <a href="{{ route('profile.edit') }}" class="primary-btn flex items-center justify-center gap-2">
                        <i class="fas fa-user-edit"></i> Edit Profile
                    </a>
                    <a href="{{ route('profile.password.edit') }}" class="primary-btn flex items-center justify-center gap-2">
                        <i class="fas fa-key"></i> Change Password
                    </a>
                </div>

                <!-- Delete Account Button -->
                <div class="mt-6">
                    <form method="POST" action="{{ route('profile.destroy') }}" id="delete-account-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmDelete()" class="danger-btn flex items-center justify-center gap-2 w-full">
                            <i class="fas fa-trash-alt"></i> Delete Account
                        </button>
                    </form>
                </div>

                <!-- Identity Image -->
                <div class="identity-section mt-12">
                    <p class="text-lg font-semibold mb-3 text-gray-700">Identity Image</p>

                    @if (auth()->user()->identity_image)
                        <img src="{{ asset('storage/' . auth()->user()->identity_image) }}"
                             alt="Identity Image"
                             class="identity-image mx-auto">
                    @else
                        <div class="no-identity text-gray-400 text-center mt-4">
                            No Identity Image Uploaded
                        </div>
                    @endif
                </div>

            </div>

        </div>

    </div>
</x-app-layout>
