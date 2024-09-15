<?php
session_start();
include('includes/db_connect.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password == $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $email = $_SESSION['email'];

        // Prepare the SQL statement
        $stmt = $conn->prepare("UPDATE pharma_admin_users SET password=? WHERE email=?");

        // Check if the prepare statement was successful
        if ($stmt === false) {
            die('Prepare failed: ' . $conn->error);
        }

        // Bind parameters
        $stmt->bind_param('ss', $hashed_password, $email);

        // Execute the statement
        if ($stmt->execute()) {
            // Clear session data
            session_unset();
            session_destroy();

            echo "<p>Password has been reset successfully. <a href='login.php'>Login here</a>.</p>";
        } else {
            echo "<p>Error updating password: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p>Passwords do not match. <a href='reset_password.php'>Try again</a>.</p>";
    }
}

$conn->close();
?>
