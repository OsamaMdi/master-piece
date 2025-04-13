document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const toggleSidebar = document.getElementById('toggleSidebar');
    const profileButton = document.getElementById('profileButton');
    const profileMenu = document.getElementById('profileMenu');
    const modal = document.getElementById('confirmationModal');
    const deleteButtons = document.querySelectorAll('.delete-btn');
    let currentForm = null;

    // Sidebar management
    toggleSidebar.addEventListener('click', () => {
        if (window.innerWidth > 992) {
            sidebar.classList.toggle('hidden');
            if (sidebar.classList.contains('hidden')) {
                mainContent.style.transition = 'margin-left 0.3s ease';
                mainContent.style.marginLeft = '0';
            } else {
                mainContent.style.transition = 'margin-left 0.3s ease';
                mainContent.style.marginLeft = '220px';
            }
        } else {
            sidebar.classList.toggle('show');
        }
    });

    // Profile dropdown
    profileButton.addEventListener('click', function () {
        profileMenu.classList.toggle('hidden');
    });

    window.addEventListener('click', function(e) {
        if (!profileButton.contains(e.target) && !profileMenu.contains(e.target)) {
            profileMenu.classList.add('hidden');
        }
    });

    // Delete confirmation modal
    deleteButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            currentForm = e.target.closest('form');
            modal.classList.remove('hidden');
        });
    });

    document.getElementById('cancelDelete').addEventListener('click', () => {
        modal.classList.add('hidden');
        currentForm = null;
    });

    document.getElementById('confirmDelete').addEventListener('click', () => {
        if (currentForm) {
            currentForm.submit();
        }
        modal.classList.add('hidden');
    });

    // Mobile touch interaction with sidebar
    let touchStartX = 0;
    const touchThreshold = 50;

    document.addEventListener('touchstart', (e) => {
        touchStartX = e.touches[0].clientX;
    });

    document.addEventListener('touchend', (e) => {
        const touchEndX = e.changedTouches[0].clientX;
        const diff = touchStartX - touchEndX;

        if (Math.abs(diff) > touchThreshold) {
            const shouldHide = diff > 0;
            sidebar.classList.toggle('show', !shouldHide);
        }
    });

    // Close modal by pressing Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            modal.classList.add('hidden');
        }
    });

    // Add Product and Upload Image Modals
    const addProductModal = document.getElementById('addProductModal');
    const uploadImageModal = document.getElementById('uploadImageModal');
    const addProductForm = document.getElementById('addProductForm');
    const uploadImageForm = document.getElementById('uploadImageForm');
    const finishUploading = document.getElementById('finishUploading');

    // Open Add Product Modal
    const openAddProductModalBtn = document.getElementById('openAddProductModal');
    if (openAddProductModalBtn) {
        openAddProductModalBtn.addEventListener('click', function() {
            addProductModal.classList.remove('hidden');
        });
    }

    // Close Add Product Modal when clicking Cancel
    const cancelAddProductBtn = document.getElementById('cancelAddProduct');
    if (cancelAddProductBtn) {
        cancelAddProductBtn.addEventListener('click', function() {
            addProductModal.classList.add('hidden');
        });
    }

    // After adding a product -> open upload image modal if necessary
    const showUploadModalMeta = document.querySelector('meta[name="show-upload-modal"]');
    const newProductIdMeta = document.querySelector('meta[name="new-product-id"]');

    if (showUploadModalMeta && newProductIdMeta) {
        const showUploadModal = showUploadModalMeta.getAttribute('content');
        const newProductId = newProductIdMeta.getAttribute('content');

        if (showUploadModal === 'true') {
            uploadImageModal.classList.remove('hidden');
            document.getElementById('uploadedProductId').value = newProductId;
        }
    }

    // Finish Uploading Image button with check
    finishUploading.addEventListener('click', function() {
        const uploadedProductId = document.getElementById('uploadedProductId').value;

        fetch(`/merchant/products/${uploadedProductId}/images/count`)
            .then(response => response.json())
            .then(data => {
                if (data.count > 0) {
                    showCustomNotification('✅ Image uploaded successfully!', 'success');

                    setTimeout(() => {
                        window.location.href = "/merchant/products";
                    }, 1500); // نعطيه ثانية ونص عشان يشوف المسج قبل ما نوديه
                } else {
                    showNotification('❌ You must upload at least one image before finishing!', 'error');
                }
            })
            .catch(error => {
                console.error('Error checking images:', error);
                showNotification('An error occurred. Please try again.', 'error');
            });
    });


    // Notification Popup (success or error message)
    window.showNotification = function(message, type = 'success', showActions = false) {
        const popup = document.getElementById('notificationPopup');
        const messageBox = document.getElementById('notificationMessage');
        const actions = document.getElementById('notificationActions');

        messageBox.textContent = message;

        if (type === 'success') {
            popup.style.background = '#d4edda';
            popup.style.color = '#155724';
        } else if (type === 'error') {
            popup.style.background = '#f8d7da';
            popup.style.color = '#721c24';
        }

        if (showActions) {
            actions.classList.remove('hidden');
        } else {
            actions.classList.add('hidden');
        }

        popup.classList.remove('hidden');
        popup.classList.add('show');

        // When a popup is showing, hide actions
        if (showActions) {
            setTimeout(() => {
                popup.classList.remove('show');
                popup.classList.add('hidden');
            }, 5000);
        }
    };

    // Custom Notification Popup with Progress Bar
    function showCustomNotification(message, type = 'success') {
        const popup = document.getElementById('customNotification');
        const icon = document.getElementById('notificationIcon');
        const messageBox = document.getElementById('customNotificationMessage');
        const progressBar = document.getElementById('customProgressBar');

        const duration = 7000; // 10 seconds

        messageBox.textContent = message;

        // Remove all previous notification types (success, error, warning)
        popup.classList.remove('success', 'error', 'warning');

        let soundSrc = '';

        // Set icon and sound based on type
        if (type === 'success') {
            popup.classList.add('success');
            icon.innerHTML = '✔️';
            soundSrc = '/sounds/success.mp3';
        } else if (type === 'error') {
            popup.classList.add('error');
            icon.innerHTML = '❌';
            soundSrc = '/sounds/error.mp3';
        } else if (type === 'warning') {
            popup.classList.add('warning');
            icon.innerHTML = '⚠️';
            soundSrc = '/sounds/warning.mp3';
        }

        // Play notification sound if it exists
        if (soundSrc) {
            const audio = new Audio(soundSrc);
            audio.play();
        }

        // Remove previous animation states
        icon.classList.remove('bounce');
        popup.classList.remove('hidden');
        popup.classList.add('show');

        // Apply bounce animation
        void icon.offsetWidth; // Trigger reflow
        icon.classList.add('bounce'); // Add bounce animation

        // Reset and animate progress bar
        progressBar.style.transition = 'none';  // Remove any transition during reset
        progressBar.style.width = '0%';  // Start from 0%
        void progressBar.offsetWidth; // Trigger reflow
        progressBar.style.transition = `width ${duration}ms linear`;  // Apply smooth transition
        progressBar.style.width = '100%';  // Animate to 100%

        // Hide notification after duration
        setTimeout(() => {
            popup.classList.remove('show');
            popup.classList.add('hidden');
            progressBar.style.width = '0%';  // Reset the progress bar
        }, duration);
    }

    // Close custom notification manually
    const closeCustomNotificationBtn = document.getElementById('closeCustomNotification');
    if (closeCustomNotificationBtn) {
        closeCustomNotificationBtn.addEventListener('click', function() {
            const popup = document.getElementById('customNotification');
            popup.classList.remove('show');
            popup.classList.add('hidden');
        });
    }
});
// ======== Product Show Page Scripts (FIXED No Animation, No Loading Issues) ========

var link = document.createElement('link');
link.rel = 'stylesheet';
link.href = 'https://unpkg.com/swiper/swiper-bundle.min.css';
document.head.appendChild(link);

// Load Swiper JS
var script = document.createElement('script');
script.src = 'https://unpkg.com/swiper/swiper-bundle.min.js';
script.onload = function () {
    var swiper = new Swiper(".mySwiper", {
        navigation: false,
        pagination: false,
        loop: false,
        allowTouchMove: false,
        slidesPerView: 1,
        speed: 0,
        preloadImages: true,
        lazy: false,
    });
};
document.body.appendChild(script);

// Open Edit Product Modal
document.getElementById('openEditProductModal').addEventListener('click', function() {
    document.getElementById('editProductModal').classList.remove('hidden');
});

// Close Edit Product Modal
document.getElementById('cancelEditProduct').addEventListener('click', function() {
    document.getElementById('editProductModal').classList.add('hidden');
});

// Open Edit Images Modal
document.getElementById('openEditImagesModal').addEventListener('click', function() {
    document.getElementById('editImagesModal').classList.remove('hidden');
});

// Close Edit Images Modal
document.getElementById('cancelEditImages').addEventListener('click', function() {
    document.getElementById('editImagesModal').classList.add('hidden');
});

// ======== Edit Product Form Submit (AJAX) ========
const editProductForm = document.getElementById('editProductForm');
if (editProductForm) {
    editProductForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitBtn = editProductForm.querySelector('button[type="submit"]');

        // Disable button + loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Saving...';

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.message) {
                document.getElementById('editProductModal').classList.add('hidden');
                showCustomNotification('✅ ' + data.message, 'success');
                setTimeout(() => window.location.reload(), 2000);
            }
        })
        .catch(error => {
            if (error.errors) {
                let errorMessages = '';
                for (const [field, messages] of Object.entries(error.errors)) {
                    errorMessages += `${messages.join('<br>')}<br>`;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Errors',
                    html: errorMessages,
                    confirmButtonColor: '#d33'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '❌ Error!',
                    text: error.message || 'Something went wrong while updating.',
                    confirmButtonColor: '#d33'
                });
            }
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Save Changes';
        });
    });
}

// ======== Edit Images Form Submit (AJAX) ========
const editImagesForm = document.getElementById('editImagesForm');
if (editImagesForm) {
    editImagesForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitBtn = editImagesForm.querySelector('button[type="submit"]');

        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Saving...';

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.message) {
                document.getElementById('editImagesModal').classList.add('hidden');
                showCustomNotification('✅ ' + data.message, 'success');
                setTimeout(() => window.location.reload(), 2000);
            }
        })
        .catch(error => {
            if (error.errors) {
                let errorMessages = '';
                for (const [field, messages] of Object.entries(error.errors)) {
                    errorMessages += `${messages.join('<br>')}<br>`;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Errors',
                    html: errorMessages,
                    confirmButtonColor: '#d33'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '❌ Error!',
                    text: error.message || 'Something went wrong while updating images.',
                    confirmButtonColor: '#d33'
                });
            }
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Save Changes';
        });
    });
}



// Image validation and preview with SweetAlert2
// SweetAlert2
const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
const maxSize = 2 * 1024 * 1024;

function showErrorAlert(message) {
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: message,
        confirmButtonColor: '#d33',
    });
}

function handleImageChange(fileInput, imageElement) {
    const file = fileInput.files[0];

    if (file) {
        if (!allowedTypes.includes(file.type)) {
            showErrorAlert('Only JPG, JPEG, PNG, and GIF files are allowed.');
            fileInput.value = '';
            return;
        }

        if (file.size > maxSize) {
            showErrorAlert('Image size should not exceed 2MB.');
            fileInput.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = (event) => {
            imageElement.src = event.target.result;
            imageElement.style.pointerEvents = 'auto'; // Reactivate after preview
        };
        reader.readAsDataURL(file);
    }
}

// Attach click and preview handlers
document.querySelectorAll('.editable-image').forEach(image => {
    const fileInput = image.nextElementSibling;

    fileInput.addEventListener('change', function () {
        handleImageChange(this, image);
    });

    image.addEventListener('click', function () {
        console.log('Clicked to change image');
        image.style.pointerEvents = 'none'; // Temporarily disable clicking to prevent double open
        fileInput.click();
        setTimeout(() => {
            image.style.pointerEvents = 'auto'; // Reactivate after a short delay
        }, 500);
    });
});


var link = document.createElement('link');
link.rel = 'stylesheet';
link.href = 'https://unpkg.com/swiper/swiper-bundle.min.css';
document.head.appendChild(link);

var script = document.createElement('script');
script.src = 'https://unpkg.com/swiper/swiper-bundle.min.js';
script.onload = function () {
    var swiper = new Swiper(".mySwiper", {
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: false,
        loop: true,
        allowTouchMove: true,
        slidesPerView: 1,
        speed: 500,
        preloadImages: true,
        lazy: true,
    });
};
document.body.appendChild(script);


function showCustomNotification(message, type = 'success') {
    const popup = document.getElementById('customNotification');
    const icon = document.getElementById('notificationIcon');
    const messageBox = document.getElementById('customNotificationMessage');
    const progressBar = document.getElementById('customProgressBar');

    const duration = 7000;
    messageBox.textContent = message;

    popup.classList.remove('success', 'error', 'warning');
    let soundSrc = '';

    if (type === 'success') {
        popup.classList.add('success');
        icon.innerHTML = '✔️';
        soundSrc = '/sounds/success.mp3';
    } else if (type === 'error') {
        popup.classList.add('error');
        icon.innerHTML = '❌';
        soundSrc = '/sounds/error.mp3';
    }

    if (soundSrc) {
        const audio = new Audio(soundSrc);
        audio.play().catch(error => console.error('Error playing sound:', error));
    }

    popup.classList.remove('hidden');
    popup.classList.add('show');

    progressBar.style.transition = 'none';
    progressBar.style.width = '0%';
    void progressBar.offsetWidth;
    progressBar.style.transition = `width ${duration}ms linear`;
    progressBar.style.width = '100%';

    setTimeout(() => {
        popup.classList.remove('show');
        popup.classList.add('hidden');
        progressBar.style.width = '0%';
    }, duration);
}
