<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include('includes/db_connect.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($id && in_array($action, ['activate', 'deactivate'])) {
    $status = ($action == 'activate') ? 'active' : 'inactive';

    $stmt = $conn->prepare("UPDATE pharma_content SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $status, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: view_content.php");
} else {
    echo "Invalid request.";
}

$conn->close();
?>
