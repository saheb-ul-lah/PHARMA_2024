<?php
include('includes/db_connect.php');

$id = $_GET['id'];
$action = $_GET['action'];

$status = ($action == 'activate') ? 'active' : 'inactive';

$sql = "UPDATE pharma_journals SET status='$status' WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    header('Location: view_journals.php');
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>
