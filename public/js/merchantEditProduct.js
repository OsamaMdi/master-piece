// public/js/merchantEditProduct.js

// ============ Edit Product AJAX ============
const editProductForm = document.getElementById('editProductForm');
if (editProductForm) {
    editProductForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const submitBtn = editProductForm.querySelector('button[type="submit"]');

        submitBtn.disabled = true;
        submitBtn.textContent = 'Saving...';

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.message) {
                sessionStorage.setItem('success', data.message);
                window.location.reload();
            }
        })
        .catch(() => {
            sessionStorage.setItem('error', 'Update failed. Please check your input.');
            window.location.reload();
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Update Product';
        });
    });
}

// ============ Edit Images Form ============
const editImagesForm = document.getElementById('editImagesForm');
if (editImagesForm) {
    editImagesForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const currentImages = document.querySelectorAll('.image-wrapper').length;
        if (currentImages < 1) {
            sessionStorage.setItem('error', 'At least one image is required.');
            window.location.reload();
            return;
        }

        const submitBtn = editImagesForm.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Saving...';

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.message) {
                sessionStorage.setItem('success', data.message);
                window.location.reload();
            }
        })
        .catch(() => {
            sessionStorage.setItem('error', 'Could not update images.');
            window.location.reload();
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Save Changes';
        });
    });
}

// ============ Image Preview + Delete ============
document.querySelectorAll('.editable-image').forEach(img => {
    const fileInput = img.nextElementSibling;
    const wrapper = img.closest('.image-wrapper');
    const imageId = wrapper.dataset.imageId;

    if (!wrapper.querySelector('.delete-image')) {
        const delBtn = document.createElement('span');
        delBtn.textContent = '✕';
        delBtn.className = 'delete-image';
        delBtn.style.cssText = 'position:absolute;top:4px;right:4px;background:red;color:white;border-radius:50%;padding:0 6px;cursor:pointer;';
        delBtn.dataset.imageId = imageId;

        delBtn.addEventListener('click', () => {
            if (document.querySelectorAll('.image-wrapper').length <= 1) {
                sessionStorage.setItem('error', 'You must keep at least one image.');
                window.location.reload();
                return;
            }

            const container = document.getElementById('deletedImagesContainer');
            if (!container.querySelector(`input[value="${imageId}"]`)) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_images[]';
                input.value = imageId;
                container.appendChild(input);
            }

            wrapper.remove();
        });

        wrapper.style.position = 'relative';
        wrapper.appendChild(delBtn);
    }

    fileInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => img.src = e.target.result;
            reader.readAsDataURL(file);
        }
    });

    img.addEventListener('click', () => fileInput.click());
});
