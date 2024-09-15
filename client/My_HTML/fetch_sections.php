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
    die(json_encode(array('error' => 'Database connection failed: ' . $conn->connect_error)));
}

// Fetch sections based on journal_id
if (isset($_GET['journal_id'])) {
    $journalId = $_GET['journal_id'];
    
    // Query to fetch sections including PDF path with unique section names
    $query = "
        SELECT ps.id, ps.section_name, ps.start_page, ps.end_page, ps.profile_name, ps.profile_image, j.pdf 
        FROM pharma_sections ps
        INNER JOIN pharma_journals j ON ps.journal_id = j.id
        WHERE ps.journal_id = ?
        GROUP BY ps.section_name ORDER BY ps.id
    ";
    
    // Prepare statement
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $journalId); // Assuming journal_id is an integer
        $stmt->execute();
        $stmt->bind_result($sectionId, $sectionName, $startPage, $endPage, $author, $profileImage, $pdfPath);
        
        $sections = array();
        while ($stmt->fetch()) {
            $sections[] = array(
                'id' => $sectionId,
                'name' => $sectionName,
                'author' => $author,
                'startPage' => $startPage,
                'endPage' => $endPage,
                'pdf_path' => "../admin/uploads/" . $pdfPath, // Adjust the path as per your file structure
                'profile_image' => $profileImage,
                'journal_id' => $journalId // Include journal_id in the response
            );
        }
        
        // Output JSON encoded array of sections
        echo json_encode($sections);
        
        // Close statement
        $stmt->close();
    } else {
        echo json_encode(array('error' => 'Failed to prepare SQL statement.'));
    }
} else {
    // Handle case where journal_id parameter is not provided
    echo json_encode(array('error' => 'Journal ID parameter missing'));
}

// Close database connection
$conn->close();
?>
