<?php
include('includes/db_connect.php');

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password for security

    // Check if email already exists
    $check_query = "SELECT * FROM pharma_admin_users WHERE email='$email'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        $error_message = "Email already registered. Please use a different email.";
    } else {
        // Insert new admin user
        $insert_query = "INSERT INTO pharma_admin_users (email, password) VALUES ('$email', '$password')";
        
        if ($conn->query($insert_query) === TRUE) {
            $success_message = "Registration successful.";
        } else {
            $error_message = "Error: " . $insert_query . "<br>" . $conn->error;
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
            color: #333;
            position: relative;
            z-index: 1;
        }

        h2 {
            margin-bottom: 20px;
            font-weight: 500;
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
            z-index: 9999;
            left: 50%;
            bottom: 30px;
            font-size: 17px;
            opacity: 0;
            transform: translateX(-50%);
            transition: visibility 0s, opacity 0.5s ease-in-out;
        }

        .toast.show {
            visibility: visible;
            opacity: 1;
        }

        .toast.success {
            background-color: #28a745;
        }

        .toast.error {
            background-color: #dc3545;
        }

        .login-link-container {
            margin-top: 30px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: inline-block;
            animation: fadeIn 0.5s ease-in-out;
        }

        .login-link-container a {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
            font-size: 18px;
        }

        .login-link-container a:hover {
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .container {
                width: 90%;
                padding: 30px;
            }
            .login-link-container a {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- <h2>Admin Registration </h2> -->
    
    <div class="login-link-container">
        <a href="login.php">Login here</a>
    </div>
</div>

<?php if (!empty($success_message)) : ?>
    <div class="toast success show"><?= htmlspecialchars($success_message); ?></div>
<?php elseif (!empty($error_message)) : ?>
    <div class="toast error show"><?= htmlspecialchars($error_message); ?></div>
<?php endif; ?>

<script>
    // Automatically hide toast after 3 seconds
    document.addEventListener('DOMContentLoaded', function () {
        const toast = document.querySelector('.toast');
        if (toast) {
            setTimeout(function () {
                toast.classList.remove('show');
            }, 3000);
        }
    });
</script>

</body>
</html>
