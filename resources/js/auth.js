/* document.addEventListener('DOMContentLoaded', function () {

    // ===== Toggle Password Visibility =====
    const setupPasswordToggle = (toggleId, iconId, inputId) => {
        const toggle = document.getElementById(toggleId);
        const icon = document.getElementById(iconId);
        const input = document.getElementById(inputId);

        if (toggle && icon && input) {
            toggle.addEventListener('click', () => {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
                toggle.setAttribute('aria-label', type === 'password' ? 'Show password' : 'Hide password');
            });
        }
    };

    // ===== Initialize Password Toggling (Login & Register Forms) =====
    setupPasswordToggle('toggleLoginPassword', 'toggleLoginPasswordIcon', 'login_password');
    setupPasswordToggle('toggleRegisterPassword', 'toggleRegisterPasswordIcon', 'register_password');
    setupPasswordToggle('toggleRegisterPasswordConfirm', 'toggleRegisterPasswordConfirmIcon', 'password_confirmation');

    // ===== Upload Identity Image =====
    const identityImage = document.getElementById('identity_image');

    if (identityImage && !identityImage.dataset.listenerAttached) {
        identityImage.dataset.listenerAttached = 'true'; // Ensure listener added once

        identityImage.addEventListener('change', async function (event) {
            console.log('Identity image changed!');

            if (!event.target.files || event.target.files.length === 0) {
                console.log('No file selected, skipping upload.');
                return;
            }

            const file = event.target.files[0];

            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                Swal.fire('Error', 'Please upload a valid image file (JPG, PNG, GIF, WEBP).', 'error');
                this.value = '';
                return;
            }

            // Validate file size (max 5MB)
            const maxSize = 5 * 1024 * 1024;
            if (file.size > maxSize) {
                Swal.fire('Error', 'The file size exceeds 5MB.', 'error');
                this.value = '';
                return;
            }

            const formData = new FormData();
            formData.append('identity_image', file);

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const uploadRoute = document.querySelector('meta[name="upload-id-route"]').content;

            const submitBtn = document.querySelector('.primary-btn');
            const loader = document.getElementById('identity-upload-loader');
            const originalBtnText = submitBtn ? submitBtn.innerHTML : '';

            try {
                // Show small loader
                if (loader) loader.classList.remove('hidden');

                // Optionally disable submit button if needed
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Uploading...';
                    submitBtn.disabled = true;
                }

                // Upload the file
                const response = await fetch(uploadRoute, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });

                const data = await response.json();
                console.log('Server Response:', data);

                if (data.success) {
                    const nameInput = document.getElementById('name');
                    const idNumberInput = document.getElementById('identity_number');
                    const cityInput = document.getElementById('city');

                    if (nameInput && !nameInput.value.trim()) {
                        nameInput.value = data.name || '';
                        nameInput.dispatchEvent(new Event('input'));
                    }
                    if (idNumberInput && !idNumberInput.value.trim()) {
                        idNumberInput.value = data.national_id || '';
                    }
                    if (cityInput && !cityInput.value.trim()) {
                        cityInput.value = data.city || 'Amman'; // Default if city not provided
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Upload Successful!',
                        text: 'Identity data extracted successfully.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Error', data.message || 'An error occurred while reading the ID.', 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'An unexpected error occurred.', 'error');
                console.error('Upload Error:', error);
            } finally {
                // Hide small loader
                if (loader) loader.classList.add('hidden');

                // Restore submit button
                if (submitBtn) {
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;
                }
            }
        });
    }

});
 */
