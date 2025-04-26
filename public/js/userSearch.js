document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('searchFormInput');
    const resultBox = document.getElementById('liveSearchResults');
    const isAuthenticated = document.getElementById('isUserAuthenticated')?.dataset.auth === 'true';

    input.addEventListener('input', function () {
        const query = this.value.trim();
        if (query.length === 0) {
            resultBox.style.display = 'none';
            resultBox.innerHTML = '';
            return;
        }

        fetch(`/live-search?query=${query}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                resultBox.innerHTML = '';
                if (data.length === 0) {
                    resultBox.innerHTML = `<li class="list-group-item text-muted">No results found</li>`;
                } else {
                    data.forEach(item => {
                        let url = item.url;
                        if (item.type === 'product' && !isAuthenticated) {
                            url = '/login';
                        }

                        // ✅ fallback to logof.png if no image available
                        const imageSrc = item.image || '/img/logof.png';

                        resultBox.innerHTML += `
                            <a href="${url}" class="list-group-item list-group-item-action d-flex align-items-center">
                                <img src="${imageSrc}" alt="img" style="
                                    width: 45px;
                                    height: 45px;
                                    object-fit: contain;
                                    background-color: #fff;
                                    padding: 5px;
                                    border-radius: 10px;
                                    border: 1px solid #eee;
                                    margin-right: 10px;
                                ">
                                <div>
                                    <div><strong>${item.name}</strong></div>
                                    <div class="text-muted small">${item.type === 'product' ? '🛠️ Tool' : '📂 Category'}</div>
                                </div>
                            </a>`;
                    });
                }
                resultBox.style.display = 'block';
            })
            .catch(error => {
                console.error("❌ Search error:", error);
                resultBox.innerHTML = `<li class="list-group-item text-danger">An error occurred while loading results</li>`;
                resultBox.style.display = 'block';
            });
    });

    // Close search result box when clicking outside
    document.addEventListener('click', function (e) {
        if (!input.contains(e.target) && !resultBox.contains(e.target)) {
            resultBox.style.display = 'none';
        }
    });
});





document.addEventListener('DOMContentLoaded', function () {
    const profileButton = document.getElementById('profileButton');
    const profileMenu = document.getElementById('profileMenu');

    if (!profileButton || !profileMenu) return;

    profileButton.addEventListener('click', function (e) {
        e.stopPropagation();
        profileMenu.classList.toggle('hidden');
    });

    document.addEventListener('click', function (e) {
        if (!profileButton.contains(e.target) && !profileMenu.contains(e.target)) {
            profileMenu.classList.add('hidden');
        }
    });
});
