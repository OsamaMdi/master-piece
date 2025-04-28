
document.addEventListener('DOMContentLoaded', function () {
    const disableButton = document.getElementById('openDisableProductModal');

    if (disableButton) {
        disableButton.addEventListener('click', async function () {
            const { value: formValues } = await Swal.fire({
                title: 'Disable Product',
                html: `
                    <input id="swal-from-date" type="date" class="swal2-input" placeholder="From Date">
                    <input id="swal-to-date" type="date" class="swal2-input" placeholder="To Date">
                    <textarea id="swal-reason" class="swal2-textarea" placeholder="Reason for disabling" style="margin-top:10px;"></textarea>
                `,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Save',
                preConfirm: () => {
                    const fromDate = document.getElementById('swal-from-date').value;
                    const toDate = document.getElementById('swal-to-date').value;
                    const reason = document.getElementById('swal-reason').value.trim();

                    if (!fromDate || !toDate || !reason) {
                        Swal.showValidationMessage('Please fill in all fields.');
                        return false;
                    }

                    if (new Date(fromDate) < new Date().setHours(0, 0, 0, 0)) {
                        Swal.showValidationMessage('From Date must be today or later.');
                        return false;
                    }

                    if (new Date(toDate) <= new Date(fromDate)) {
                        Swal.showValidationMessage('To Date must be after From Date.');
                        return false;
                    }

                    if (reason.length < 5) {
                        Swal.showValidationMessage('Reason must be at least 5 characters.');
                        return false;
                    }

                    return { from_date: fromDate, to_date: toDate, reason: reason };
                }
            });

            if (formValues) {
                try {
                    const response = await fetch(disableProductUrl, {
                        method: "PATCH",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify(formValues)
                    });

                    let result;

                    try {
                        result = await response.clone().json(); // نقرأ نسخة من الريسبونس كـ JSON
                    } catch (jsonError) {
                        result = null;
                    }

                    if (!response.ok) {
                        let errorMessage = 'Something went wrong!';

                        if (result && result.errors) {
                            errorMessage = Object.values(result.errors).flat().join('<br>');
                        } else if (result && result.message) {
                            errorMessage = result.message;
                        } else {
                            const errorText = await response.text();
                            errorMessage = errorText || errorMessage;
                        }

                        throw new Error(errorMessage);
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Done!',
                        text: result.message || 'Product disabled successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });

                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: error.message,
                    });
                }

            }
        });
    }
});
