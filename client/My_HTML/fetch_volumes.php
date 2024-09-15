<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "journal_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch all data from the journals table
$sql = "SELECT * FROM pharma_journals ORDER BY journal_name DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $volumes = array();
    while ($row = $result->fetch_assoc()) {
        $volumes[] = $row; // Add the entire row to the volumes array
    }
    echo json_encode($volumes); // Return all table data as JSON array
} else {
    echo json_encode(array()); // Return an empty array if no data found
}

$conn->close();
?>
