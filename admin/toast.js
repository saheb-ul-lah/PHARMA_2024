document.getElementById('resetPasswordForm').onsubmit = function(e) {
    e.preventDefault(); // Prevent the form from submitting the default way

    var form = $(this);

    $.ajax({
        type: form.attr('method'),
        url: form.attr('action'),
        data: form.serialize(),
        success: function(response) {
            try {
                var data = JSON.parse(response);
                showToast(data.message, data.status);
                if (data.status === 'success') {
                    setTimeout(function() {
                        window.location.href = 'login.php'; // Redirect to login page
                    }, 3000); // Wait 3 seconds to show the toast
                }
            } catch (e) {
                showToast('Error: Invalid response from server.', 'error');
            }
        },
        error: function() {
            showToast('Error updating password. Please try again.', 'error');
        }
    });
};

function showToast(message, status) {
    const toast = document.getElementById('toast');
    const toastIcon = document.getElementById('toast-icon');
    const toastMessage = document.getElementById('toast-message');
    
    toastMessage.textContent = message;
    if (status === 'success') {
        toastIcon.innerHTML = '&#10004;'; // Checkmark icon
        toast.style.backgroundColor = '#28a745'; // Green background
    } else {
        toastIcon.innerHTML = '&#10060;'; // Cross icon
        toast.style.backgroundColor = '#dc3545'; // Red background
    }
    
    toast.className = 'toast show';
    setTimeout(() => { toast.className = toast.className.replace('show', ''); }, 3000);
}
