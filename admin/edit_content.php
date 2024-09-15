<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include('includes/db_connect.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$error_message = "";

if ($id) {
    // Fetch content data
    $stmt = $conn->prepare("SELECT * FROM pharma_content WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $content = $result->fetch_assoc();
    $stmt->close();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Handle form submission
        $volume_id = $_POST['volume_id'];
        $issue_no = $_POST['issue_no'];
        $category_name = $_POST['category_name'];
        $title = $_POST['title'];
        $abstract = $_POST['abstract'];
        $authors = $_POST['authors'];
        $pdf_upload = $content['pdf_file_path']; // Default to the existing PDF file
        $pdf_url = $content['pdf_url']; // Default to the existing URL

        $remove_pdf = isset($_POST['remove_pdf']); // Check if the "Remove PDF" checkbox is selected

        // Ensure only one of the two fields is provided
        if (!empty($_FILES['pdf_upload']['name']) && !empty($_POST['pdf_url'])) {
            $error_message = "Please provide either a PDF file or a PDF URL, not both.";
        } elseif (empty($_FILES['pdf_upload']['name']) && empty($_POST['pdf_url']) && !$remove_pdf && empty($content['pdf_file_path']) && empty($content['pdf_url'])) {
            $error_message = "Please provide either a PDF file or a PDF URL.";
        } else {
            // Handle PDF upload
            if (!empty($_FILES['pdf_upload']['name'])) {
                $file = $_FILES['pdf_upload']['tmp_name'];
                $filename = time() . "_" . $_FILES['pdf_upload']['name'];
                $destination = 'uploads/content_pdfs/' . $filename;

                if (move_uploaded_file($file, $destination)) {
                    $pdf_upload = $destination;
                    $pdf_url = ''; // Clear PDF URL since a file was uploaded
                } else {
                    $error_message = "Failed to upload PDF file.";
                }
            }

            // Handle PDF URL
            if (!empty($_POST['pdf_url'])) {
                $pdf_url = $_POST['pdf_url'];
                $pdf_upload = ''; // Clear PDF upload since a URL was provided
            }

            // Handle PDF removal
            if ($remove_pdf) {
                $pdf_upload = ''; // Clear the PDF file path
            }

            // If there's no error, update the record
            if (empty($error_message)) {
                $stmt = $conn->prepare("UPDATE pharma_content SET volume_id = ?, issue_number = ?, category_name = ?, title = ?, abstract = ?, authors = ?, pdf_file_path = ?, pdf_url = ? WHERE id = ?");
                $stmt->bind_param('iissssssi', $volume_id, $issue_no, $category_name, $title, $abstract, $authors, $pdf_upload, $pdf_url, $id);
                $stmt->execute();
                $stmt->close();

                header("Location: view_content.php");
                exit();
            }
        }
    }
} else {
    $error_message = "Invalid ID.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Content</title>
    <link rel="stylesheet" href="./assets/css/edit_content.css">
    <script>
        function toggleInputs() {
            const pdfUpload = document.getElementById('pdf_upload');
            const pdfUrl = document.getElementById('pdf_url');
            const removePdf = document.getElementById('remove_pdf');

            pdfUpload.disabled = pdfUrl.value.trim() !== '' || removePdf.checked;
            pdfUrl.disabled = pdfUpload.value.trim() !== '' || removePdf.checked;
        }

        window.onload = function() {
            document.getElementById('pdf_upload').onchange = toggleInputs;
            document.getElementById('pdf_url').oninput = toggleInputs;
            document.getElementById('remove_pdf').onchange = toggleInputs;
        };
    </script>
</head>
<body>
<div class="container">
    <h2>Edit Content</h2>
    <?php if (!empty($error_message)): ?>
        <div style="color: red; margin-bottom: 15px;">
            <?= htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>
    <form action="edit_content.php?id=<?= $id; ?>" method="POST" enctype="multipart/form-data">
        <!-- Form fields -->
        <div class="form-group">
            <label for="volume_id">Volume ID:</label>
            <input type="number" class="form-control" id="volume_id" name="volume_id" value="<?= htmlspecialchars($content['volume_id']); ?>" required>
        </div>
        <div class="form-group">
            <label for="issue_no">Issue Number:</label>
            <input type="number" class="form-control" id="issue_no" name="issue_no" value="<?= htmlspecialchars($content['issue_number']); ?>" required>
        </div>
        <div class="form-group">
            <label for="category_name">Category Name:</label>
            <input type="text" class="form-control" id="category_name" name="category_name" value="<?= htmlspecialchars($content['category_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($content['title']); ?>" required>
        </div>
        <div class="form-group">
            <label for="abstract">Abstract:</label>
            <input type="text" class="form-control" id="abstract" name="abstract" value="<?= htmlspecialchars($content['abstract']); ?>" required>
        </div>
        <div class="form-group">
            <label for="authors">Authors:</label>
            <input type="text" class="form-control" id="authors" name="authors" value="<?= htmlspecialchars($content['authors']); ?>" required>
        </div>
        <div style="border: 2px solid lightblue;border-radius:10px;padding:5px 10px;" class="form-group">
            <label for="pdf_upload">Upload PDF:</label>
            <input type="file" class="form-control" id="pdf_upload" name="pdf_upload">
            <small>Current: <?= !empty($content['pdf_file_path']) ? '<a href="' . htmlspecialchars($content['pdf_file_path']) . '" target="_blank">View PDF</a>' : 'No PDF uploaded'; ?></small>
            <?php if (!empty($content['pdf_file_path'])): ?>
                <div class="form-group">
                    <label for="remove_pdf">
                        <input type="checkbox" id="remove_pdf" name="remove_pdf" value="1"> Remove existing PDF
                    </label>
                </div>
            <?php endif; ?>
        </div>
        <h2>OR</h2>
        <div style="border: 2px solid lightblue;border-radius:10px;padding:5px 10px;" class="form-group">
            <label for="pdf_url">PDF URL:</label>
            <input type="url" class="form-control" id="pdf_url" name="pdf_url" value="<?= htmlspecialchars($content['pdf_url']); ?>">
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>
</body>
</html>
