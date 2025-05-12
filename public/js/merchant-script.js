document.addEventListener('DOMContentLoaded', function () {
    // === Sidebar Toggle ===
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const toggleSidebar = document.getElementById('toggleSidebar');

    if (toggleSidebar) {
        toggleSidebar.addEventListener('click', () => {
            if (window.innerWidth > 992) {
                sidebar.classList.toggle('hidden');
                mainContent.style.marginLeft = sidebar.classList.contains('hidden') ? '0' : '220px';
            } else {
                sidebar.classList.toggle('show');
            }
        });
    }

    // === Profile Dropdown ===
    const profileButton = document.getElementById('profileButton');
    const profileMenu = document.querySelector('.dropdown-menu');

    if (profileButton && profileMenu) {
        profileButton.addEventListener('click', function (e) {
            e.stopPropagation();
            profileMenu.classList.toggle('show');
        });

        window.addEventListener('click', function (e) {
            if (!profileButton.contains(e.target) && !profileMenu.contains(e.target)) {
                profileMenu.classList.remove('show');
            }
        });
    }

    // === Confirmation Modal (Replaced with SweetAlert) ===
    const deleteButtons = document.querySelectorAll('.delete-btn');
    let currentForm = null;

    deleteButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            currentForm = e.target.closest('form');

            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed && currentForm) {
                    currentForm.submit();
                }
            });
        });
    });

    const cancelDelete = document.getElementById('cancelDelete');
    const confirmDelete = document.getElementById('confirmDelete');

    if (cancelDelete && confirmDelete) {
        cancelDelete.addEventListener('click', () => {
            document.getElementById('confirmationModal')?.classList.add('hidden');
            currentForm = null;
        });

        confirmDelete.addEventListener('click', () => {
            if (currentForm) currentForm.submit();
            document.getElementById('confirmationModal')?.classList.add('hidden');
        });
    }
});

// ======== Product Show Page Scripts (FIXED No Animation, No Loading Issues) ========

var link = document.createElement('link');
link.rel = 'stylesheet';
link.href = '[https://unpkg.com/swiper/swiper-bundle.min.css](https://unpkg.com/swiper/swiper-bundle.min.css)';
document.head.appendChild(link);

// Load Swiper JS
var script = document.createElement('script');
script.src = '[https://unpkg.com/swiper/swiper-bundle.min.js](https://unpkg.com/swiper/swiper-bundle.min.js)';
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

var link = document.createElement('link');
link.rel = 'stylesheet';
link.href = '[https://unpkg.com/swiper/swiper-bundle.min.css](https://unpkg.com/swiper/swiper-bundle.min.css)';
document.head.appendChild(link);

var script = document.createElement('script');
script.src = '[https://unpkg.com/swiper/swiper-bundle.min.js](https://unpkg.com/swiper/swiper-bundle.min.js)';
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


/* function showCustomNotification(message, type = 'success') {
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
} */






