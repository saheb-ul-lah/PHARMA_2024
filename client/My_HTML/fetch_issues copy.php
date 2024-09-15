<?php
header('Content-Type: application/json');

// Database connection
include('includes/db_connect.php');

// Prepare SQL query to fetch all data from pharma_content table
$sql = "SELECT * FROM pharma_content";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(['error' => 'Failed to prepare the SQL statement']);
    exit();
}

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $content = [];

    while ($row = $result->fetch_assoc()) {
        $content[] = $row; // Add each row to the content array
    }

    // Check if any data was found
    if (empty($content)) {
        echo json_encode(['error' => 'No content found.']);
    } else {
        echo json_encode($content); // Return all content data as JSON array
    }
} else {
    echo json_encode(['error' => 'Error executing query: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>