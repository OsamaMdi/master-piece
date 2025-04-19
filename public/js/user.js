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


//                 creat page


document.addEventListener('DOMContentLoaded', function () {
    const identityInput = document.querySelector('input[name="identity_image"]');

    if (identityInput && !identityInput.dataset.listenerAttached) {
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

                const data = await res.json();

                if (data.success) {
                    const form = document.getElementById('createUserForm');
                    const nameInput = form.querySelector('input[name="name"]');
                    const idInput = form.querySelector('input[name="identity_number"]');
                    const cityInput = form.querySelector('input[name="city"]');

                    if (data.name && nameInput && !nameInput.value.trim()) {
                        nameInput.value = data.name;
                    }
                    if (data.national_id && idInput) {
                        idInput.value = data.national_id;
                    }
                    if (data.city && cityInput) {
                        cityInput.value = data.city;
                    }

                    Swal.fire('Success', 'Identity data extracted successfully.', 'success');
                } else {
                    Swal.fire('Invalid ID', data.message || 'This is not a valid identity card.', 'error');
                    this.value = '';
                }
            } catch (err) {
                console.error(err);
                Swal.fire('Upload Failed', 'Could not verify the image. Please try again.', 'error');
                this.value = '';
            }
        });
    }
});



//    ===========================================


//      edit page



    document.addEventListener('DOMContentLoaded', function () {
        const identityInput = document.querySelector('#identity_image');
        identityInput?.addEventListener('change', async function (e) {
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

                const data = await res.json();

                if (data.success) {
                    const form = document.getElementById('editUserForm');
                    const idInput = form.querySelector('input[name="identity_number"]');
                    const cityInput = form.querySelector('input[name="city"]');

                    if (data.national_id && idInput && !idInput.value) idInput.value = data.national_id;
                    if (data.city && cityInput && !cityInput.value) cityInput.value = data.city;

                    Swal.fire('Success', 'Identity data extracted successfully.', 'success');
                } else {
                    Swal.fire('Invalid ID', data.message || 'This is not a valid identity card.', 'error');
                    this.value = '';
                }
            } catch (err) {
                console.error(err);
                Swal.fire('Upload Failed', 'Could not verify the image. Please try again.', 'error');
                this.value = '';
            }
        });
    });



    //  ==============================================
