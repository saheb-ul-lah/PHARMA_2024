<!-- fetch_journal_id.php -->
<!-- http://localhost/DUJOP2024/admin/uploads/ -->
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

// Retrieve journal_name from request
$journal_name = isset($_GET['journal_name']) ? $conn->real_escape_string($_GET['journal_name']) : '';

if ($journal_name !== '') {
    // Fetch journal_id for the given journal_name
    $sql = "SELECT id FROM pharma_journals WHERE journal_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $journal_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $journal_id = $result->num_rows > 0 ? $result->fetch_assoc()['id'] : null;
    $stmt->close();
} else {
    $journal_id = null;
}

$conn->close();

// Return journal_id as JSON
header('Content-Type: application/json');
echo json_encode(array('journal_id' => $journal_id));
?>
