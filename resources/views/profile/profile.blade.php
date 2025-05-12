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
            <div class="profile-left text-center">
                <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : asset('img/default-user.png') }}"
                     alt="Profile Picture" class="profile-avatar cursor-pointer" onclick="showImageModal(this.src)">
                <p class="profile-email mt-2">{{ auth()->user()->email }}</p>
            </div>

            <!-- Right: Info -->
            <div class="profile-right">
                <h2 class="profile-name">{{ auth()->user()->name }}</h2>

                <div class="profile-info grid grid-cols-2 gap-4">
                    <!-- City -->
                    <div class="info-item h-32">
                        <label class="info-label">
                            <i class="fas fa-city text-blue-500 mr-2"></i> City
                        </label>
                        <span class="info-value">{{ auth()->user()->city ?? 'Not Provided' }}</span>
                    </div>

                    <!-- Country -->
                    <div class="info-item h-32">
                        <label class="info-label">
                            <i class="fas fa-flag text-blue-500 mr-2"></i> Country
                        </label>
                        <span class="info-value">{{ auth()->user()->identity_country ?? 'Not Provided' }}</span>
                    </div>

                    <!-- Identity Number -->
                    <div class="info-item h-32">
                        <label class="info-label">
                            <i class="fas fa-id-card text-blue-500 mr-2"></i> Identity Number
                        </label>
                        <span class="info-value">{{ auth()->user()->identity_number ?? 'Not Provided' }}</span>
                    </div>

                    <!-- Phone -->
                    <div class="info-item h-32">
                        <label class="info-label">
                            <i class="fas fa-phone text-blue-500 mr-2"></i> Phone
                        </label>
                        <span class="info-value">{{ auth()->user()->phone ?? 'Not Provided' }}</span>
                    </div>

                    <!-- Address -->
                    <div class="info-item h-32">
                        <label class="info-label">
                            <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i> Address
                        </label>
                        <span class="info-value">{{ auth()->user()->address ?? 'Not Provided' }}</span>
                    </div>

                    <!-- Identity Image Card -->
                    <div class="info-item h-32 overflow-hidden">
                        <label class="info-label">
                            <i class="fas fa-image text-blue-500 mr-2"></i> Identity Image
                        </label>
                        @if(auth()->user()->identity_image)
                            <img src="{{ asset('storage/' . auth()->user()->identity_image) }}" class="w-full h-full object-cover rounded cursor-pointer" onclick="showImageModal(this.src)" alt="Identity Preview">
                        @else
                            <div class="text-gray-400 text-sm">No Identity Image</div>
                        @endif
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
            </div>
        </div>

        <!-- Image Modal -->
        <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50 hidden">
            <img id="modalImage" src="" class="max-w-md w-full rounded-lg shadow-xl border-4 border-white">
        </div>
    </div>

    <script>
        function showImageModal(src) {
            if (!src) return;
            const modal = document.getElementById('imageModal');
            const image = document.getElementById('modalImage');
            image.src = src;
            modal.classList.remove('hidden');
            modal.addEventListener('click', () => {
                modal.classList.add('hidden');
                image.src = '';
            });
        }
    </script>
</x-app-layout>
