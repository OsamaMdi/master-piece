// 📁 public/js/chat.js (نهائي مع جميع التعديلات)

// ✅ تعريف دالة تحديث حالة القراءة
window.updateReadStatus = function () {
    fetch('/chat/mark-as-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken
        },
        body: JSON.stringify({ chat_id: window.chatId })
    })
    .then(res => res.json())
    .then(data => {
        if (Array.isArray(data.updated_ids)) {
            data.updated_ids.forEach(id => {
                const msg = document.querySelector(`.message-sent[data-id="${id}"]`);
                if (msg) {
                    const readStatus = msg.querySelector('.seen-status');
                    if (readStatus) {
                        readStatus.textContent = '✔️✔️';
                        readStatus.style.color = 'green';
                        msg.classList.add('read');
                        msg.dataset.read = '1';
                    }
                }
            });
        }
    });
};

// ✅ تشغيل بعد تحميل الصفحة

document.addEventListener('DOMContentLoaded', () => {
    const chatBox = document.getElementById('chatBox');
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('chatMessageInputField');
    const sendButton = chatForm.querySelector('.send-button');
    const imageInput = document.getElementById('imageInput');

    if (!chatBox || !chatForm || typeof window.Echo === 'undefined') return;

    chatBox.scrollTop = chatBox.scrollHeight;
    sendButton.disabled = messageInput.value.trim() === '';

    messageInput.addEventListener('input', () => {
        sendButton.disabled = messageInput.value.trim() === '';
    });

    // ✅ إرسال الرسالة النصية
    chatForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        const formData = new FormData(chatForm);
        formData.append('_token', window.csrfToken);

        try {
            const response = await fetch(window.chatSendUrl, {
                method: 'POST',
                body: formData
            });

            if (!response.ok) return;

            const result = await response.json();
            const msgDiv = document.createElement('div');
            msgDiv.classList.add('message', 'message-sent');
            msgDiv.setAttribute('data-id', result.id);
            msgDiv.setAttribute('data-read', '0');
            msgDiv.setAttribute('data-sender-id', window.userId);
            msgDiv.setAttribute('data-sender-type', window.userType || '');
            msgDiv.innerHTML = `
                <div class="message-bubble bubble-sent">
                    ${result.image_url ? `<img src="${result.image_url}" class="message-image" />` : ''}
                    ${result.message ? `<div class="message-text">${result.message}</div>` : ''}
                    <div class="message-time">${result.time}</div>
                    <small class="text-muted d-block mt-1 seen-status">🕓</small>
                </div>
            `;
            chatBox.appendChild(msgDiv);
            chatBox.scrollTop = chatBox.scrollHeight;
            chatForm.reset();
            sendButton.disabled = true;
        } catch (err) {
            console.error('❌ Message sending failed:', err);
        }
    });

    // ✅ إرسال صورة
    imageInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            alert('Only image files are allowed.');
            this.value = '';
            return;
        }

        const formData = new FormData(chatForm);
        formData.append('_token', window.csrfToken);

        fetch(window.chatSendUrl, {
            method: 'POST',
            body: formData
        }).then(async response => {
            if (response.ok) {
                const result = await response.json();
                const msgDiv = document.createElement('div');
                msgDiv.classList.add('message', 'message-sent');
                msgDiv.setAttribute('data-id', result.id);
                msgDiv.setAttribute('data-read', '0');
                msgDiv.setAttribute('data-sender-id', window.userId);
                msgDiv.setAttribute('data-sender-type', window.userType || '');
                msgDiv.innerHTML = `
                    <div class="message-bubble bubble-sent">
                        ${result.image_url ? `<img src="${result.image_url}" class="message-image" />` : ''}
                        <div class="message-time">${result.time}</div>
                        <small class="text-muted d-block mt-1 seen-status">🕓</small>
                    </div>
                `;
                chatBox.appendChild(msgDiv);
                chatBox.scrollTop = chatBox.scrollHeight;
                chatForm.reset();
                sendButton.disabled = true;
            }
        });
    });

    // ✅ استلام الرسالة من الطرف الآخر
    window.Echo.private(window.chatChannel)
        .listen('.MessageSent', (data) => {
            if (parseInt(data.receiver_id) !== parseInt(window.userId)) return;

            const msgDiv = document.createElement('div');
            msgDiv.classList.add('message', 'message-received');
            msgDiv.setAttribute('data-id', data.message.id);
            msgDiv.setAttribute('data-read', '0');

            msgDiv.innerHTML = `
                <img src="${data.sender_image || '/img/default-user.png'}" class="message-avatar" alt="Sender">
                <div class="message-bubble bubble-received">
                    ${data.message.image_url ? `<img src="${data.message.image_url}" class="message-image" />` : ''}
                    ${data.message.message ? `<div class="message-text">${data.message.message}</div>` : ''}
                    <div class="message-time">
                        ${data.message.time}
                    </div>
                </div>
            `;
            chatBox.appendChild(msgDiv);
            chatBox.scrollTop = chatBox.scrollHeight;
        })

        // ✅ تم التوصيل
        .listen('.MessageDeliveredStatusUpdated', (e) => {
            if (parseInt(window.userId) !== parseInt(e.sender_id)) return;

            const msgEl = document.querySelector(`.message-sent[data-id="${e.message_id}"]`);
            if (msgEl) {
                const readStatus = msgEl.querySelector('.seen-status');
                if (readStatus && !msgEl.classList.contains('read')) {
                    readStatus.textContent = '✔️';
                    readStatus.style.color = '#999';
                }
            }
        })

        // ✅ تمت القراءة
        .listen('.MessagesMarkedAsRead', (e) => {
            console.log("📥 Broadcast Received: MessagesMarkedAsRead", e);

            if (parseInt(window.userId) !== parseInt(e.senderId)) return;

            e.messageIds.forEach(id => {
                const msg = document.querySelector(`.message-sent[data-id="${id}"]`);
                if (msg) {
                    const readStatus = msg.querySelector('.seen-status');
                    if (readStatus) {
                        readStatus.textContent = '✔️✔️';
                        readStatus.style.color = 'green';
                        msg.classList.add('read');
                        msg.dataset.read = '1';
                    }
                }
            });
        });

    // ✅ استدعاء التحديث عند تركيز النافذة أو الوصول لأسفل الشات
    window.addEventListener('focus', window.updateReadStatus);
    chatBox.addEventListener('scroll', function () {
        if (chatBox.scrollTop + chatBox.clientHeight >= chatBox.scrollHeight - 10) {
            window.updateReadStatus();
        }
    });
});


setInterval(() => {
    fetch('/chat/mark-delivered', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken
        },
        body: JSON.stringify({ auto: true }) // أي قيمة لتمييز أنه فحص دوري
    });
}, 30000);


