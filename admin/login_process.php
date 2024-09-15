<?php
session_start();
include('includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email exists
    $stmt = $conn->prepare("SELECT * FROM pharma_admin_users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Password is correct, set session variables
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_email'] = $row['email'];
            header("Location: index.php"); // Redirect to dashboard or admin panel page
            exit();
        } else {
            $error = "Incorrect password. <a href='login.php'>Try again</a>.";
        }
    } else {
        $error = "Email not registered. <a href='register.php'>Register here</a>.";
    }

    $stmt->close();
    $conn->close();
}
?>
