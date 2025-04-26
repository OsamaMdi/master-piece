document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('adminSearchInput');
    const resultBox = document.getElementById('adminSearchResults');

    if (!input || !resultBox) return;

    input.addEventListener('input', function () {
        const query = this.value.trim();

        if (query.length === 0) {
            resultBox.style.display = 'none';
            resultBox.innerHTML = '';
            return;
        }

        fetch(`/admin/search?query=${encodeURIComponent(query)}`)
            .then(response => {
                if (!response.ok) throw new Error('Search failed');
                return response.json();
            })
            .then(data => {
                resultBox.innerHTML = '';

                if (data.length === 0) {
                    resultBox.innerHTML = `<li class="text-muted px-4 py-3">No results found</li>`;
                } else {
                    data.forEach(item => {
                        const img = item.image || '/img/logof.png';
                        const name = item.name || 'Unnamed';
                        let status = '';
                        let badgeClass = '';
                        let typeLabel = '';
                        let role = '';

                        // 📌 USER TYPE
                        if (item.type === 'user') {
                            status = item.status;
                            role = item.role && item.role !== 'null' ? item.role : '';
                            badgeClass = item.status === 'active' ? 'active' : '';

                            if (item.user_type === 'admin') {
                                typeLabel = '👑 Admin';
                            } else if (item.user_type === 'merchant') {
                                typeLabel = '🛒 Merchant';
                            } else if (item.user_type === 'user') {
                                typeLabel = '👤 Client';
                            } else {
                                typeLabel = '';
                            }
                        }

                        // 📌 PRODUCT TYPE
                        else if (item.type === 'product') {
                            status = `Status: ${item.status}`;
                            badgeClass = item.status === 'available' ? 'active' : '';
                            typeLabel = '🛠️ Tool';
                        }

                        // 📌 RESERVATION TYPE
                        else if (item.type === 'reservation') {
                            status = `${item.user} - ${item.status}`;
                            typeLabel = '📄 Reservation';
                        }

                        // ✅ HTML STRUCTURE
                        resultBox.innerHTML += `
                        <li>
                            <a href="${item.url}" class="d-flex align-items-center text-decoration-none text-dark">
                                <img src="${img}" alt="${name}">
                                <div class="item-info">
                                    <span class="name">${name}</span>
                                    ${item.type === 'user' && role ? `<span class="search-role-badge">${role}</span>` : ''}
                                    ${typeLabel ? `<span class="search-type-badge">${typeLabel}</span>` : ''}
                                    <span class="status ${badgeClass}">${status}</span>
                                </div>
                            </a>
                        </li>`;
                    });
                }

                resultBox.style.display = 'block';
            })
            .catch(error => {
                console.error("Search error:", error);
                resultBox.innerHTML = `<li class="text-danger px-4 py-3">Failed to load results</li>`;
                resultBox.style.display = 'block';
            });
    });

    // إخفاء النتائج عند الضغط خارج البحث
    document.addEventListener('click', function (e) {
        if (!input.contains(e.target) && !resultBox.contains(e.target)) {
            resultBox.style.display = 'none';
        }
    });
});
