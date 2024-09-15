<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Retrieve admin information if needed
$admin_id = $_SESSION['admin_id'];
$admin_email = $_SESSION['admin_email'];
?>
<?php include('includes/header.php'); ?>
<div class="container">
    <h2>Upload Volume PDF</h2>
    <form action="upload_journal.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="journal">Select Volume:</label>
            <select class="form-control" id="journal" name="journal_id" required>
                <?php
                include('includes/db_connect.php');
                $result = $conn->query("SELECT id, journal_name FROM pharma_journals WHERE pdf IS NULL");

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['journal_name']) . "</option>";
                    }
                } else {
                    echo "<option value=''>No journals available</option>";
                }
                $conn->close();
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="pdf">Upload PDF:</label>
            <input type="file" class="form-control-file" id="pdf" name="pdf" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload Volume</button>
    </form>
</div>

<?php
include('includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $journal_id = $_POST['journal_id'];

    // File upload handling
    $file = $_FILES['pdf'];
    $filename = $file['name'];
    $tmp_name = $file['tmp_name'];

    // Ensure file was uploaded successfully
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo "<div class='alert error'>❌ Error uploading file. Please try again.</div>";
        exit();
    }

    // Get current date and time for renaming file
    $target_dir = "uploads/volume_pdfs/";
    $target_file = basename($filename); // Use original file name without date prefix

    // Move uploaded file to target directory with renamed filename
    if (move_uploaded_file($tmp_name, $target_dir . $target_file)) {
        // Update database with new PDF file and date uploaded
        $sql = "UPDATE pharma_journals SET pdf='$target_file', date_uploaded=NOW() WHERE id='$journal_id'";

        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert success'>✅ Journal uploaded successfully!</div>";
        } else {
            echo "<div class='alert error'>❌ Error updating record: " . $conn->error . "</div>";
        }
    } else {
        echo "<div class='alert error'>❌ Sorry, there was an error uploading your file.</div>";
    }

    $conn->close();
}
?>

<?php include('includes/footer.php'); ?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');
    body {
        background-color: #f8f9fa;
        color: #333;
        font-family: 'Poppins', sans-serif;
    }

    .container {
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .btn-primary {
        width: 100%;
        padding: 10px;
        background-color: #007bff;
        border: none;
        border-radius: 5px;
        color: white;
        transition: background-color 0.3s;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .alert {
        padding: 15px;
        margin: 20px 0;
        border-radius: 8px;
        color: #fff;
        font-weight: bold;
        text-align: center;
        animation: fadeIn 1s ease-in-out;
        backdrop-filter: blur(5px);
    }

    .alert.success {
        background: rgba(40, 167, 69, 0.8);
    }

    .alert.error {
        background: rgba(220, 53, 69, 0.8);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
</style>
