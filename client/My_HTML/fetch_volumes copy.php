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

// Query to fetch distinct journal names (volumes) from the journals table
$sql = "SELECT DISTINCT journal_name,id,pdf FROM journals ORDER BY journal_name DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $volumes = array();
    while ($row = $result->fetch_assoc()) {
        $volumes[] = $row['journal_name'];
    }
    echo json_encode($volumes); // Return volumes as JSON array
} else {
    echo json_encode(array()); // Return an empty array if no volumes found
}

$conn->close();
?>






































<!-- Latest -->

<!-- fetch_sections.php -->
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

// Fetch sections based on journal_id
if (isset($_GET['journal_id'])) {
    $journalId = $_GET['journal_id'];
    
    // Query to fetch sections including PDF path
    $query = "SELECT ps.id, ps.section_name, ps.start_page, ps.end_page, ps.profile_name, ps.profile_image, j.pdf 
              FROM pdf_sections ps
              INNER JOIN journals j ON ps.journal_id = j.id
              WHERE ps.journal_id = ?";
    
    // Prepare statement
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $journalId); // Assuming journal_id is an integer
    
    // Execute statement
    $stmt->execute();
    
    // Bind result variables
    $stmt->bind_result($sectionId, $sectionName, $startPage, $endPage, $author, $profileImage, $pdfPath);
    
    $sections = array();

    // Fetch rows and store in array
    while ($stmt->fetch()) {
        $section = array(
            'id' => $sectionId,
            'name' => $sectionName,
            'author' => $author,
            'startPage' => $startPage,
            'endPage' => $endPage,
            'pdf_path' => "../admin/uploads/" . $pdfPath, // Adjust the path as per your file structure
            'profile_image' => $profileImage
        );
        $sections[] = $section;
    }
    
    // Close statement
    $stmt->close();
    
    // Output JSON encoded array of sections
    header('Content-Type: application/json');
    echo json_encode($sections);
} else {
    // Handle case where journal_id parameter is not provided
    echo json_encode(array('error' => 'Journal ID parameter missing'));
}

// Close database connection
$conn->close();
?>
