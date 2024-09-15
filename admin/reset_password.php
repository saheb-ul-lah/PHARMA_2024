<?php
session_start();
include('includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['otp'])) {
        // Handle OTP verification
        $otp = $_POST['otp'];
        $email = $_SESSION['email'];
        $session_otp = $_SESSION['otp'];

        if ($otp == $session_otp) {
            $_SESSION['otp_verified'] = true;
            echo "<script>alert('OTP verified successfully. Please enter your new password.');</script>";
        } else {
            echo "<script>alert('Invalid OTP. Please try again.');</script>";
        }
    } elseif (isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        $email = $_SESSION['email'];

        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE pharma_admin_users SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashed_password, $email);

            if ($stmt->execute()) {
                echo "<script>alert('Password has been reset successfully. Redirecting to login...');window.location.href='/pharma_2024/admin/login.php';</script>";
                unset($_SESSION['otp_verified']);
            } else {
                echo "<script>alert('Failed to update password. Please try again.');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Passwords do not match. Please try again.');</script>";
        }
    }
} else {
    // Check if OTP has been verified to show the reset password form
    if (isset($_SESSION['otp_verified']) && $_SESSION['otp_verified']) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Reset Password</title>
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
                <h2>Reset Password</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="new_password">New Password:</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password:</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                </form>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "<script>alert('Invalid request. Please try again.');</script>";
    }
}
$conn->close();
