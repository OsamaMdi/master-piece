document.addEventListener('DOMContentLoaded', function () {
    const addProductForm = document.getElementById('addProductForm');
    const imageInput = document.getElementById('multiImageInput');
    const previewContainer = document.getElementById('imagePreviewContainer');

    if (addProductForm) {
        addProductForm.addEventListener('submit', function (e) {
            let hasError = false;

            const nameInput = addProductForm.querySelector('input[name="name"]');
            const descInput = addProductForm.querySelector('textarea[name="description"]');
            const priceInput = addProductForm.querySelector('input[name="price"]');
            const quantityInput = addProductForm.querySelector('input[name="quantity"]');
            const categorySelect = addProductForm.querySelector('select[name="category_id"]');

            addProductForm.querySelectorAll('.error-text').forEach(e => e.remove());

            const showError = (element, message) => {
                const error = document.createElement('small');
                error.classList.add('error-text');
                error.style.color = 'red';
                error.textContent = message;
                element.parentElement.appendChild(error);
                hasError = true;
            };

            if (!nameInput.value.trim()) {
                showError(nameInput, 'Name is required.');
            } else if (nameInput.value.trim().length < 4) {
                showError(nameInput, 'Name must be at least 4 characters.');
            }

            if (descInput.value.trim() && descInput.value.trim().length < 20) {
                showError(descInput, 'Description must be at least 20 characters if provided.');
            }

            const priceValue = parseFloat(priceInput.value);
            if (!priceInput.value || isNaN(priceValue) || priceValue <= 0) {
                showError(priceInput, 'Price must be greater than 0 JOD.');
            }

            const quantityValue = parseInt(quantityInput.value);
            if (!quantityInput.value || isNaN(quantityValue) || quantityValue < 1) {
                showError(quantityInput, 'Quantity must be at least 1.');
            }

            if (!categorySelect.value) {
                showError(categorySelect, 'Please select a category.');
            }

            if (!imageInput.files || imageInput.files.length === 0) {
                showError(imageInput, 'Please upload at least one product image.');
            }

            if (hasError) {
                e.preventDefault();
            }
        });
    }

    // Preview selected images
    if (imageInput && previewContainer) {
        imageInput.addEventListener('change', function () {
            previewContainer.innerHTML = ''; // Clear previous previews

            Array.from(this.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('preview-image');
                    img.style.width = '100px';
                    img.style.marginRight = '10px';
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });
    }
});
