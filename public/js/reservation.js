document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("rentalForm");
    if (!form) {
        console.error("❌ rentalForm not found.");
        return;
    }

    const startDateInput = document.getElementById("start_date");
    const endDateInput = document.getElementById("end_date");
    const priceMessage = document.getElementById("price-message");
    const dailyPrice = parseFloat(document.getElementById("daily_price")?.value || 0);
    const termsCheck = document.getElementById("termsCheck");

    const reserveBtn = document.getElementById("reserveBtn");
    const cardForm = document.getElementById("cardForm");
    const cardModalEl = document.getElementById("cardModal");
    const cardModal = cardModalEl ? new bootstrap.Modal(cardModalEl) : null;

    const readTermsLink = document.getElementById("readTermsLink");
    const termsModalEl = document.getElementById("termsModal");
    const termsModal = termsModalEl ? new bootstrap.Modal(termsModalEl) : null;

    const formatDate = (date) => {
        const yyyy = date.getFullYear();
        const mm = String(date.getMonth() + 1).padStart(2, '0');
        const dd = String(date.getDate()).padStart(2, '0');
        return `${yyyy}-${mm}-${dd}`;
    };

    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const tomorrow = new Date(today);
    tomorrow.setDate(today.getDate() + 1);
    const tomorrowStr = formatDate(tomorrow);
    const productId = startDateInput?.dataset.productId;

    function showFieldError(fieldId, message) {
        const field = document.getElementById(fieldId);
        const container = field.closest('.form-group') || field.parentElement;

        field.classList.add('is-invalid');
        if (!container.querySelector('.text-danger')) {
            const error = document.createElement('div');
            error.className = 'text-danger mt-1';
            error.textContent = message;
            container.appendChild(error);
        }
    }

    function clearFieldError(fieldId) {
        const field = document.getElementById(fieldId);
        const container = field.closest('.form-group') || field.parentElement;

        field.classList.remove('is-invalid');
        container.querySelectorAll('.text-danger').forEach(el => el.remove());
    }

    readTermsLink?.addEventListener("click", function (e) {
        e.preventDefault();
        termsModal?.show();
    });

    [termsModalEl, cardModalEl].forEach(modalEl => {
        modalEl?.addEventListener("hidden.bs.modal", function () {
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        });
    });

    fetch(`/reservations/unavailable-dates/${productId}`)
        .then(res => res.json())
        .then(data => {
            let disabledDates = [];

            (data.disabled_dates || []).forEach(dateStr => {
                disabledDates.push(dateStr);
                const date = new Date(dateStr);
                date.setDate(date.getDate() + 1);
                disabledDates.push(formatDate(date));
            });

            flatpickr(startDateInput, {
                minDate: tomorrowStr,
                disable: disabledDates,
                dateFormat: "Y-m-d",
                static: true,
                appendTo: document.body,
                onChange: function (_, dateStr) {
                    if (endDateInput._flatpickr) {
                        endDateInput._flatpickr.set("minDate", dateStr);
                    }
                    updateTotalPrice();
                }
            });

            flatpickr(endDateInput, {
                minDate: tomorrowStr,
                disable: disabledDates,
                dateFormat: "Y-m-d",
                static: true,
                appendTo: document.body,
                onChange: updateTotalPrice
            });

            [startDateInput, endDateInput].forEach(input => {
                input.addEventListener("blur", function () {
                    const selectedDate = new Date(this.value);
                    const formatted = formatDate(selectedDate);

                    if (disabledDates.includes(formatted)) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Unavailable Date',
                            text: 'This date or the day after it is already fully booked.',
                        });
                        this.value = "";
                        updateTotalPrice();
                    }
                });
            });
        })
        .catch(err => {
            console.error("Failed to fetch reservation data:", err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Could not load reservation data. Please try again later.'
            });
        });

    function updateTotalPrice() {
        if (!startDateInput.value || !endDateInput.value) {
            priceMessage.textContent = "";
            return;
        }

        const start = new Date(startDateInput.value);
        const end = new Date(endDateInput.value);
        const diff = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;

        if (diff > 0) {
            const total = diff * dailyPrice;
            priceMessage.textContent = `Total Price: ${total.toFixed(2)} JOD (${diff} days)`;
        } else {
            priceMessage.textContent = "";
        }
    }

    reserveBtn?.addEventListener("click", function () {
        clearFieldError("start_date");
        clearFieldError("end_date");
        clearFieldError("termsCheck");

        const start = startDateInput.value;
        const end = endDateInput.value;
        let valid = true;

        if (!start) {
            showFieldError("start_date", "Start date is required.");
            valid = false;
        }
        if (!end) {
            showFieldError("end_date", "End date is required.");
            valid = false;
        }
        if (!termsCheck?.checked) {
            showFieldError("termsCheck", "You must agree to the terms.");
            valid = false;
        }

        if (valid) {
            cardModal?.show();
        }
    });

    cardForm?.addEventListener("submit", function (e) {
        e.preventDefault();

        // امسح الأخطاء القديمة أولاً
        ["cardName", "cardNumber", "expiry", "cvv", "popupPaymentOption"].forEach(clearFieldError);

        // اجلب القيم من الحقول
        const name = document.getElementById("cardName")?.value.trim();
        const number = document.getElementById("cardNumber")?.value.trim();
        const expiry = document.getElementById("expiry")?.value.trim();
        const cvv = document.getElementById("cvv")?.value.trim();
        const paymentOption = document.getElementById("popupPaymentOption")?.value;

        let valid = true;

        // تحقق من أن الحقول ليست فارغة
        if (!name) {
            showFieldError("cardName", "Name on card is required.");
            valid = false;
        }
        if (!number) {
            showFieldError("cardNumber", "Card number is required.");
            valid = false;
        }
        if (!expiry) {
            showFieldError("expiry", "Expiration date is required.");
            valid = false;
        }
        if (!cvv) {
            showFieldError("cvv", "CVV is required.");
            valid = false;
        }
        if (!paymentOption) {
            showFieldError("popupPaymentOption", "Please choose a payment option.");
            valid = false;
        }

        // ✅ تحقق إضافي لصحة معلومات البطاقة فقط لو الحقول مش فاضية
        if (number && !/^(4[0-9]{15}|5[1-5][0-9]{14})$/.test(number.replace(/\s+/g, ''))) {
            showFieldError("cardNumber", "Invalid Visa or MasterCard number format.");
            valid = false;
        }

        if (expiry && !isValidExpiry(expiry)) {
            showFieldError("expiry", "Invalid or expired expiry date (MM/YY).");
            valid = false;
        }

        if (cvv && !/^[0-9]{3,4}$/.test(cvv)) {
            showFieldError("cvv", "Invalid CVV (should be 3 or 4 digits).");
            valid = false;
        }

        if (!valid) return; // لو في أخطاء لا تكمل

        // إنشاء hidden fields لحفظ بيانات الدفع
        const hiddenPayment = document.createElement("input");
        hiddenPayment.type = "hidden";
        hiddenPayment.name = "payment_type";
        hiddenPayment.value = paymentOption;

        const hiddenAmount = document.createElement("input");
        hiddenAmount.type = "hidden";
        hiddenAmount.name = "amount_paid";

        const start = new Date(startDateInput.value);
        const end = new Date(endDateInput.value);
        const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;
        const total = days * dailyPrice;

        hiddenAmount.value = paymentOption === "10"
            ? (total * 0.10).toFixed(2)
            : total.toFixed(2);

        form.appendChild(hiddenPayment);
        form.appendChild(hiddenAmount);

        // إغلاق مودال البطاقة وإرسال النموذج
        cardModal?.hide();
        setTimeout(() => {
            console.log("✅ Submitting form now...");
            form.submit();
        }, 300);
    });
    function isValidExpiry(expiry) {
        const [month, year] = expiry.split('/');
        if (!month || !year) return false;

        const expMonth = parseInt(month.trim(), 10);
        const expYear = parseInt('20' + year.trim(), 10);

        if (isNaN(expMonth) || isNaN(expYear)) return false;
        if (expMonth < 1 || expMonth > 12) return false;

        const now = new Date();
        const expiryDate = new Date(expYear, expMonth - 1, 1);

        return expiryDate >= new Date(now.getFullYear(), now.getMonth(), 1);
    }

});

    // ✅ Toggle delivery location input visibility and validate if needed
    const deliveryCheckbox = document.getElementById("deliveryCheckbox");
    const locationInputWrapper = document.getElementById("locationInputWrapper");
    const deliveryLocation = document.getElementById("deliveryLocation");
    const locationError = document.getElementById("locationError");

    deliveryCheckbox?.addEventListener("change", function () {
        if (this.checked) {
            locationInputWrapper.classList.remove("d-none");
        } else {
            locationInputWrapper.classList.add("d-none");
            deliveryLocation.value = "";
            locationError.classList.add("d-none");
            deliveryLocation.classList.remove("is-invalid");
        }
    });

   reserveBtn?.addEventListener("click", function () {
    if (deliveryCheckbox?.checked) {
        const locationValue = deliveryLocation?.value.trim().toLowerCase();

        if (!locationValue) {
            locationError.textContent = "Location is required when delivery is selected.";
            locationError.classList.remove("d-none");
            deliveryLocation.classList.add("is-invalid");
            deliveryLocation.focus();
            return;
        } else if (!locationValue.includes("عمان") && !locationValue.includes("amman")) {
            locationError.textContent = "Delivery is currently available only in Amman.";
            locationError.classList.remove("d-none");
            deliveryLocation.classList.add("is-invalid");
            deliveryLocation.focus();
            return;
        } else {
            locationError.classList.add("d-none");
            deliveryLocation.classList.remove("is-invalid");
        }
    }
});

