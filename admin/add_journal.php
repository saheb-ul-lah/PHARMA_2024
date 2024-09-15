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
    <h2>Add Volume</h2>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        include('includes/db_connect.php');

        $journal_name = $_POST['journal_name'];
        $issn = $_POST['issn'];
        $editor_name = $_POST['editor_name'];
        $date = $_POST['date'];
        $volume_thumbnail_file = $_FILES['volume_thumbnail_file'];
        $volume_thumbnail_url = $_POST['volume_thumbnail_url'];

        // Handle file upload
        $volume_thumbnail = '';
        if ($volume_thumbnail_file['error'] == UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/volume_thumbnails/';
            $upload_file = $upload_dir . basename($volume_thumbnail_file['name']);
            if (move_uploaded_file($volume_thumbnail_file['tmp_name'], $upload_file)) {
                $volume_thumbnail = $upload_file;
            } else {
                echo "<h2 style='color: red;font-size:16px;padding:5px 10px; border-radius:10px; margin:5px 10px;'>Failed to upload thumbnail.</h2>";
            }
        } elseif (!empty($volume_thumbnail_url)) {
            $volume_thumbnail = $volume_thumbnail_url;
        }

        // Count existing rows to determine the next ID
        $sql_count = "SELECT COUNT(*) FROM pharma_journals";
        $result_count = $conn->query($sql_count);
        $row_count = $result_count->fetch_row();
        $next_id = $row_count[0] + 1;

        // Check for duplicates
        $sql_check = "SELECT COUNT(*) FROM pharma_journals WHERE journal_name = '$journal_name' AND issn = '$issn'";
        $result_check = $conn->query($sql_check);
        $row_check = $result_check->fetch_row();

        if ($row_check[0] == 0) {
            // Insert data with the manually set ID
            $sql = "INSERT INTO pharma_journals (id, journal_name, issn, editor_name, date, volume_thumbnail) 
                VALUES ('$next_id', '$journal_name', '$issn', '$editor_name', '$date', '$volume_thumbnail')";

            if ($conn->query($sql) === TRUE) {
                echo "<h2 style='color: green; font-size:16px;padding:5px 10px; border-radius:10px; margin:5px 10px;'>Journal added successfully!</h2>";
                // Redirect to avoid resubmission (optional)
                // header("Location: add_journal.php?success=1");
                exit();
            } else {
                echo "<h2 style='color: red; font-size:16px;padding:5px 10px; border-radius:10px; margin:5px 10px;'>Error: " . $conn->error . "</h2>";
            }
        } else {
            echo "<h2 style='color: red; font-size:16px;padding:5px 10px; border-radius:10px; margin:5px 10px;'>Journal already exists.</h2>";
        }

        $conn->close();
    }
    ?>

    <form action="add_journal.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="journal_name">Volume Name:</label>
            <input type="text" class="form-control" id="journal_name" name="journal_name" required>
        </div>
        <div class="form-group">
            <label for="issn">ISSN:</label>
            <input type="text" class="form-control" id="issn" name="issn" required>
        </div>
        <div class="form-group">
            <label for="editor_name">Editor Name:</label>
            <input type="text" class="form-control" id="editor_name" name="editor_name" required>
        </div>
        <div class="form-group">
            <label for="date">Date:</label>
            <input type="date" class="form-control" id="date" name="date" required>
        </div>
        <div class="form-group">
            <label for="volume_thumbnail">Thumbnail:</label>
            <input type="file" class="form-control" id="volume_thumbnail_file" name="volume_thumbnail_file">
            <small class="form-text text-muted">OR</small>
            <input type="text" class="form-control" id="volume_thumbnail_url" name="volume_thumbnail_url" placeholder="Enter URL of the thumbnail image">
        </div>
        <button type="submit" class="btn btn-primary">Add Volume</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');

    body {
        background-color: #f8f9fa;
        color: #333;
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        overflow-y: scroll;
    }

    .container {
        max-width: 800px;
        padding: 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-control {
        border-radius: 5px;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    .btn-primary {
        width: 100%;
        padding: 10px;
        background-color: #007bff;
        border: none;
        border-radius: 5px;
        color: white;
        transition: background-color 0.3s, transform 0.2s;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        transform: scale(1.05);
    }
</style>
