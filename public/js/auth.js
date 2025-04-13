document.addEventListener('DOMContentLoaded', function () {

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

    // Login Form
    setupPasswordToggle('toggleLoginPassword', 'toggleLoginPasswordIcon', 'login_password');

    // Register Form
    setupPasswordToggle('toggleRegisterPassword', 'toggleRegisterPasswordIcon', 'register_password');
    setupPasswordToggle('toggleRegisterPasswordConfirm', 'toggleRegisterPasswordConfirmIcon', 'password_confirmation');

    // ===== Upload Identity Image =====
    const identityImage = document.getElementById('identity_image');
    if (identityImage) {
        identityImage.addEventListener('change', async function (event) {
            const file = event.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('identity_image', file);

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const uploadIdAjaxRoute = document.querySelector('meta[name="upload-id-route"]').getAttribute('content');

            const submitBtn = document.querySelector('.x-primary-button');
            const originalBtnText = submitBtn.innerHTML;

            try {
                submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Uploading...';
                submitBtn.disabled = true;

                const response = await fetch(uploadIdAjaxRoute, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    document.getElementById('identity_number').value = data.national_id || '';
                    document.getElementById('city').value = data.city || '';
                    document.getElementById('name').value = data.name || '';
                    if (document.getElementById('identity_country').value === '') {
                        document.getElementById('identity_country').value = 'Jordan';
                    }
                } else {
                    Swal.fire('Error', data.message || 'An error occurred while reading the ID.', 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'An unexpected error occurred.', 'error');
            } finally {
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
            }
        });
    }
});
