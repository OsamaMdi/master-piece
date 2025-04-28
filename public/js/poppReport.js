document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.openReportModalBtn').forEach(function(button) {
        button.addEventListener('click', async function () {
            const reportableType = this.getAttribute('data-reportable-type');
            const reportableId = this.getAttribute('data-reportable-id');
            const targetType = this.getAttribute('data-target-type');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const sendReportUrl = this.getAttribute('data-report-url');

            const { value: formValues } = await Swal.fire({
                title: 'Submit a Report',
                html:
                    '<input id="subject" class="swal2-input" placeholder="Subject (optional)">' +
                    '<textarea id="message" class="swal2-textarea" placeholder="Describe the issue..." style="margin-top:10px;"></textarea>',
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Send Report',
                preConfirm: () => {
                    const message = document.getElementById('message').value.trim();
                    if (message.length < 10) {
                        Swal.showValidationMessage('Message must be at least 10 characters.');
                        return false;
                    }
                    return {
                        subject: document.getElementById('subject').value,
                        message: message
                    };
                }
            });

            if (formValues) {
                try {
                    const response = await fetch(sendReportUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            ...formValues,
                            reportable_type: reportableType,
                            reportable_id: reportableId,
                            target_type: targetType
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        Swal.fire('Success!', result.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error!', result.message, 'error');
                    }
                } catch (error) {
                    Swal.fire('Error!', 'Unexpected error occurred.', 'error');
                }
            }
        });
    });
});
