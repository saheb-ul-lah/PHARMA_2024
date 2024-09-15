<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Include database connection file
    include('includes/db_connect.php');

    // Sanitize and validate input
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query to check if user with given email exists
    $sql = "SELECT id, email, password FROM pharma_admin_users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // User found, verify password
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Password is correct, set session variables
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_email'] = $user['email'];

            // Show success message
            echo "<script>showToast('Login successful!');</script>";
            // Redirect to admin panel or dashboard
            header("Location: index.php");
            exit();
        } else {
            // Password incorrect
            $error = "Invalid email or password.";
        }
    } else {
        // User not found
        $error = "Invalid email or password.";
    }

    // Close database connection
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
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

        .btn-primary, .btn-secondary {
            margin-bottom: 15px;
            transition: transform 0.2s ease;
        }

        .btn-primary:hover, .btn-secondary:hover {
            transform: scale(1.05);
        }

        .register-link {
            display: inline-block;
            margin-top: 20px;
            font-size: 14px;
        }

        .toast {
            visibility: hidden;
            min-width: 250px;
            margin-left: -125px;
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
        }

        .toast.show {
            visibility: visible;
            opacity: 1;
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
        <h2>Admin Login</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
        <!-- <a href="register.php" class="btn btn-secondary btn-block">Register as New Admin</a> -->
        <p class="register-link">Not registered yet? <a href="register.php">Register as New Admin</a></p>
    </div>

    <div id="toast" class="toast">Notification</div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script>
        function showToast(message) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = 'toast show';
            setTimeout(() => { toast.className = toast.className.replace('show', ''); }, 3000);
        }
    </script>
</body>
</html>
