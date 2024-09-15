<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include('includes/db_connect.php');

if (isset($_GET['id'])) {
    $content_id = intval($_GET['id']);  // Sanitize the content ID

    // Check if content exists
    $check_query = "SELECT * FROM pharma_content WHERE id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("i", $content_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Content exists, proceed to delete
        $delete_query = "DELETE FROM pharma_content WHERE id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $content_id);
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Content deleted successfully!";
        } else {
            $_SESSION['error_message'] = "Failed to delete content. Please try again.";
        }
    } else {
        $_SESSION['error_message'] = "Content not found.";
    }

    $stmt->close();
    $conn->close();
} else {
    $_SESSION['error_message'] = "Invalid request.";
}

// Redirect back to the view content page
header("Location: view_content.php");
exit();
?>
