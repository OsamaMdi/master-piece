if (typeof window.userId !== 'undefined') {
    const countElement = document.getElementById('notification-count');
    const listElement = document.getElementById('notification-list');
    const wrapper = document.getElementById('notification-wrapper');
    const dropdown = document.getElementById('notification-dropdown');

    let autoHideTimeout;

    // If the list is empty on load, show a "No notifications" message
    if (listElement && listElement.children.length === 0) {
        const li = document.createElement('li');
        li.innerHTML = `<div class="text-center text-muted small py-2">No notifications yet</div>`;
        listElement.appendChild(li);
    }

    // Listen for real-time notifications via Echo
    window.Echo.private('user.' + window.userId)
        .listen('.notification.received', (e) => {
            // Update counter
            if (countElement) {
                const current = Number(countElement.innerText.trim()) || 0;
                countElement.innerText = current + 1;
                countElement.classList.remove('d-none');
            }

            // Remove "No notifications" message if it's the only item
            if (
                listElement &&
                listElement.children.length === 1 &&
                listElement.children[0].textContent.trim() === "No notifications yet"
            ) {
                listElement.innerHTML = "";
            }

            // Create and prepend the new notification
            if (listElement) {
                const li = document.createElement('li');
                li.classList.add('px-3', 'py-2', 'border-bottom');

                li.innerHTML = `
                    <a href="${e.url || '#'}" class="d-flex align-items-start text-decoration-none text-dark">
                        <div>
                            <div class="fw-semibold">${e.message}</div>
                            <small class="text-muted">${e.created_at || new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</small>
                        </div>
                    </a>
                `;
                listElement.prepend(li);
            }
        });

    // Toggle dropdown when clicking on the bell icon
    if (wrapper && dropdown) {
        wrapper.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('show');

            if (dropdown.classList.contains('show')) {
                // Send AJAX request to mark notifications as read
                fetch('/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                });

                // Reset counter visually
                if (countElement) {
                    countElement.innerText = '0';
                    countElement.classList.add('d-none');
                }
            }
        });
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!wrapper.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });
}
