                function showToast(message, type) {
                    const toastHTML = `
                        <div class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="d-flex">
                                <div class="toast-body">
                                    ${message}
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>`;
                    $("#toast-container").append(toastHTML);
                    const toastElement = $("#toast-container .toast").last();
                    const toast = new bootstrap.Toast(toastElement);
                    toast.show();

                    if (type === 'success') {
                        setTimeout(() => {
                            window.location.href = "/login";
                        }, 3000);
                    }
                }

                $(document).ready(function() {
                    const response = ' . json_encode($response) . ';
                    if (response.status === "success") {
                        showToast(response.message, "success");
                    } else {
                        showToast(response.message, "danger");
                    }
                });
