<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');
        
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #f3f4f6, #e2e8f0);
            font-family: 'Poppins', sans-serif;
        }

        .container {
            width: 100%;
            max-width: 400px;
            text-align: center;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            animation: fadeIn 0.5s ease;
        }

        .logo {
            margin-bottom: 20px;
        }

        .form-group {
            text-align: left;
        }

        .btn-primary {
            margin-bottom: 15px;
            transition: transform 0.2s ease;
        }

        .btn-primary:hover {
            transform: scale(1.05);
        }

        .loading-spinner {
            display: none;
            margin: 15px auto;
            width: 50px;
            height: 50px;
            border: 5px solid rgba(0,0,0,0.1);
            border-radius: 50%;
            border-top-color: #3498db;
            animation: spin 1s infinite linear;
        }

        .toast {
            visibility: hidden;
            min-width: 250px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            padding: 16px;
            position: fixed;
            z-index: 1;
            right: 20px;
            top: 20px;
            font-size: 17px;
            transition: visibility 0s, opacity 0.5s linear;
            opacity: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toast.show {
            visibility: visible;
            opacity: 1;
        }

        .toast-icon {
            margin-right: 10px;
            font-size: 20px;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="assets/images/logo.png" alt="Logo" width="150">
        </div>
        <h2>Forgot Password</h2>
        <form id="otpForm" action="send_otp.php" method="POST">
            <div class="form-group">
                <label for="email">Enter your email address:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Send OTP</button>
        </form>
        <div class="loading-spinner"></div>
    </div>

    <div id="toast" class="toast">
        <span id="toast-icon" class="toast-icon"></span>
        <span id="toast-message"></span>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        document.getElementById('otpForm').onsubmit = function(e) {
            e.preventDefault(); // Prevent the form from submitting the default way

            var form = $(this);
            var spinner = document.querySelector('.loading-spinner');
            spinner.style.display = 'block'; // Show spinner

            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                success: function(response) {
                    spinner.style.display = 'none'; // Hide spinner
                    var data = JSON.parse(response);
                    showToast(data.message, data.status);
                    if (data.status === 'success') {
                        setTimeout(function() {
                            window.location.href = 'verify_otp.php';
                        }, 3000);
                    }
                },
                error: function() {
                    spinner.style.display = 'none'; // Hide spinner
                    showToast('Error sending OTP. Please try again.', 'error');
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
    </script>
</body>
</html>
