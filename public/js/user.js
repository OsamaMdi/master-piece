//                 index page

function confirmDelete(event) {
    event.preventDefault();
    Swal.fire({
        title: 'Are you sure?',
        text: "This user will be deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
        if (result.isConfirmed) {
            event.target.submit();
        }
    });
    return false;
}

// ==========================================


//                 creat And Edite page

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('editUserForm') || document.getElementById('createUserForm');
    if (!form) return;

    const identityInput = form.querySelector('input[name="identity_image"]');
    if (!identityInput) return;

    let identityCheckPromise = null;

    identityInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const allowed = ['image/jpeg', 'image/png', 'image/webp'];
        if (!allowed.includes(file.type)) {
            identityInput.value = '';
            form.dataset.flashMessage = 'Only JPG, PNG, or WEBP images are allowed.';
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            identityInput.value = '';
            form.dataset.flashMessage = 'The image must be smaller than 5MB.';
            return;
        }

        identityCheckPromise = handleIdentityCheck(file, form);
    });

    form.addEventListener('submit', async function (e) {
        const file = identityInput.files[0];

        if (file && identityCheckPromise) {
            e.preventDefault();

            try {
                await identityCheckPromise;
                form.submit(); // ✅ بعد نجاح التحقق
            } catch (err) {
                identityInput.value = '';
                form.dataset.flashMessage = err.message || 'The uploaded file is not a valid ID.';
            }
        }
    });

    async function handleIdentityCheck(file, form) {
        const formData = new FormData();
        formData.append('identity_image', file);

        const uploadUrl = document.querySelector('meta[name="upload-id-route"]').content;
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        const res = await fetch(uploadUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf },
            body: formData
        });

        if (!res.ok) throw new Error(await res.text());

        const data = await res.json();

        if (!data.success) throw new Error(data.message || 'This is not a valid identity card.');

        const nameInput = form.querySelector('input[name="name"]');
        const idInput = form.querySelector('input[name="identity_number"]');
        const cityInput = form.querySelector('input[name="city"]');

        if (nameInput && data.name && !nameInput.value.trim()) nameInput.value = data.name;
        if (idInput && data.national_id && !idInput.value) idInput.value = data.national_id;
        if (cityInput && data.city && !cityInput.value) cityInput.value = data.city;

        form.dataset.flashMessage = 'Identity data extracted successfully.';
    }
});

              /*  {{-- Script For Block User --}} */

              document.addEventListener('DOMContentLoaded', function () {
                const statusSelect = document.getElementById('status');
                if (!statusSelect) return; // 🛡️ أمان من الأخطاء

                const form = statusSelect.closest('form');
                if (!form) return; // 🛡️ تأكد أن الفورم موجود كمان

                statusSelect.addEventListener('change', function (e) {
                    if (this.value === 'blocked') {
                        e.preventDefault();

                        Swal.fire({
                            title: 'Block User',
                            html: `
                                <div style="text-align: left;">
                                    <label style="display: block; margin-bottom: 8px;">Select Block Duration:</label>
                                    <select id="duration" class="swal2-input" style="width: 100%;">
                                        <option value="1">1 Day</option>
                                        <option value="2">2 Days</option>
                                        <option value="7">1 Week</option>
                                        <option value="permanent">Permanent</option>
                                    </select>

                                    <label style="display: block; margin: 15px 0 8px;">Block Reason:</label>
                                    <textarea id="reason" class="swal2-textarea" placeholder="Enter reason..." rows="3" style="width: 100%;"></textarea>
                                </div>
                            `,
                            focusConfirm: false,
                            showCancelButton: true,
                            confirmButtonText: 'Confirm Block',
                            cancelButtonText: 'Cancel',
                            preConfirm: () => {
                                const duration = document.getElementById('duration').value;
                                const reason = document.getElementById('reason').value.trim();

                                if (!reason) {
                                    Swal.showValidationMessage('Block reason is required');
                                }

                                return { duration, reason };
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const durationInput = document.createElement('input');
                                durationInput.type = 'hidden';
                                durationInput.name = 'duration';
                                durationInput.value = result.value.duration;
                                form.appendChild(durationInput);

                                const reasonInput = document.createElement('input');
                                reasonInput.type = 'hidden';
                                reasonInput.name = 'reason';
                                reasonInput.value = result.value.reason;
                                form.appendChild(reasonInput);

                                form.submit();
                            } else {
                                // ✅ رجع الحالة القديمة فقط إذا أنت في صفحة edit
                                if (typeof oldStatus !== 'undefined') {
                                    statusSelect.value = oldStatus;
                                }
                            }
                        });
                    }
                });
            });

    //  ==============================================
