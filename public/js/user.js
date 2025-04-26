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

    if (!form) {
        console.error('No form (#editUserForm or #createUserForm) found.');
        return;
    }

    const identityInput = form.querySelector('input[name="identity_image"]');

    if (!identityInput) {
        console.error('Identity image input not found inside the form.');
        return;
    }


    if (!identityInput.dataset.listenerAttached) {
        identityInput.dataset.listenerAttached = 'true';

        identityInput.addEventListener('change', async function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const allowed = ['image/jpeg', 'image/png', 'image/webp'];
            if (!allowed.includes(file.type)) {
                Swal.fire('Invalid File Type', 'Only JPG, PNG, or WEBP images are allowed.', 'error');
                this.value = '';
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                Swal.fire('File Too Large', 'The image must be smaller than 5MB.', 'error');
                this.value = '';
                return;
            }

            const formData = new FormData();
            formData.append('identity_image', file);

            const uploadUrl = document.querySelector('meta[name="upload-id-route"]').content;
            const csrf = document.querySelector('meta[name="csrf-token"]').content;

            try {
                const res = await fetch(uploadUrl, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf },
                    body: formData
                });

                if (!res.ok) {
                    const errorText = await res.text();
                    throw new Error(errorText || 'Server returned an error.');
                }

                const data = await res.json();

                if (data.success) {
                    const nameInput = form.querySelector('input[name="name"]');
                    const idInput = form.querySelector('input[name="identity_number"]');
                    const cityInput = form.querySelector('input[name="city"]');

                    if (nameInput && data.name && !nameInput.value.trim()) {
                        nameInput.value = data.name;
                    }
                    if (idInput && data.national_id && !idInput.value) {
                        idInput.value = data.national_id;
                    }
                    if (cityInput && data.city && !cityInput.value) {
                        cityInput.value = data.city;
                    }

                    Swal.fire('Success', 'Identity data extracted successfully.', 'success');
                } else {
                    Swal.fire('Invalid ID', data.message || 'This is not a valid identity card.', 'error');
                    this.value = '';
                }
            } catch (err) {
                console.error('==== Error Details ====');
                console.error('Full Error:', err);

                if (err instanceof Response) {
                    err.text().then(errorMessage => {
                        console.error('Error Response Text:', errorMessage);
                    });
                }

                Swal.fire('Upload Failed', err.message || 'Could not verify the image. Please try again.', 'error');
                this.value = '';
            }
        });
    }
});



              /*  {{-- Script For Block User --}} */

    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status');
        const form = statusSelect.closest('form');

        statusSelect.addEventListener('change', function(e) {
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

                        return { duration: duration, reason: reason };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // أنشئ عناصر إضافية لترسل مع الفورم
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

                        // ابعت الفورم بعد اضافة البيانات
                        form.submit();
                    } else {
                        // إذا لغى البلوك رجعه لActive أو رجعه على حاله
                        statusSelect.value = "{{ old('status', $user->status) }}";
                    }
                });
            }
        });
    });





    //  ==============================================
