<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
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
            margin-top: 15px;
            transition: transform 0.2s ease;
        }

        .btn-primary:hover {
            transform: scale(1.05);
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="assets/images/logo.png" alt="Logo" width="150">
        </div>
        <h2>Verify OTP</h2>
        <form id="otpForm" method="POST" action="reset_password.php">
            <div class="form-group">
                <label for="otp">Enter the OTP sent to your email:</label>
                <input type="text" class="form-control" id="otp" name="otp" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Verify OTP</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        document.getElementById('otpForm').onsubmit = function(e) {
            e.preventDefault(); // Prevent the form from submitting the default way

            var form = $(this);

            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                success: function() {
                    alert('OTP verified successfully. Please enter your new password.');
                    window.location.href = 'reset_password.php'; // Redirect to the reset password form
                },
                error: function() {
                    alert('Error verifying OTP. Please try again.');
                }
            });
        };
    </script>
</body>
</html>
