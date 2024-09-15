<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include('includes/header.php');
include('includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $volume_id = $_POST['volume_id'];
    $issue_no = $_POST['issue_no'];
    $categories = $_POST['categories'];

    // Iterate through categories
    // Insert title data into the database with manual ID assignment
    foreach ($categories as $categoryNumber => $categoryData) {
        $category_name = $categoryData['category_name'];

        // Iterate through titles within each category
        foreach ($categoryData['titles'] as $titleNumber => $titleData) {
            $title = $titleData['title'];
            $abstract = $titleData['abstract'];
            $authors = $titleData['authors'];
            $pdf_upload = '';
            $pdf_url = '';

            // Handle PDF upload
            if (
                isset($_FILES['categories']['name'][$categoryNumber]['titles'][$titleNumber]['pdf_upload']) &&
                $_FILES['categories']['name'][$categoryNumber]['titles'][$titleNumber]['pdf_upload'] != ''
            ) {

                $file = $_FILES['categories']['tmp_name'][$categoryNumber]['titles'][$titleNumber]['pdf_upload'];
                $filename = time() . "_" . $_FILES['categories']['name'][$categoryNumber]['titles'][$titleNumber]['pdf_upload'];
                $destination = 'uploads/content_pdfs/' . $filename;

                if (move_uploaded_file($file, $destination)) {
                    $pdf_upload = $destination;
                } else {
                    echo "Failed to upload PDF file.";
                }
            }

            // Handle PDF URL
            if (isset($titleData['pdf_url']) && !empty($titleData['pdf_url'])) {
                $pdf_url = $titleData['pdf_url'];
            }

            // Manually assign ID
            $id_query = "SELECT COUNT(*) as total FROM pharma_content";
            $result = $conn->query($id_query);
            $row = $result->fetch_assoc();
            $id = $row['total'] + 1;

            // Insert the data into the database with manual ID
            $stmt = $conn->prepare("INSERT INTO pharma_content (id, volume_id, issue_number, category_name, title, abstract, authors, pdf_file_path, pdf_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiissssss", $id, $volume_id, $issue_no, $category_name, $title, $abstract, $authors, $pdf_upload, $pdf_url);
            $stmt->execute();
        }
    }


    echo "<div class='alert alert-success'>Content added successfully!</div>";
}

// Fetch volumes
$volumes_query = "SELECT id, journal_name FROM pharma_journals";
$volumes_result = $conn->query($volumes_query);
?>

<div class="container">
    <h2>Add volume contents</h2>
    <form action="add_content.php" method="POST" enctype="multipart/form-data">
        <!-- Volume Selection -->
        <div class="form-group">
            <label for="volume_id">Select Volume:</label>
            <select class="form-control" id="volume_id" name="volume_id" required>
                <?php while ($volume = $volumes_result->fetch_assoc()) : ?>
                    <option value="<?= $volume['id']; ?>"><?= $volume['journal_name']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <?php
        // This is the maximum issue number provided
        $max_issue_no = 10;
        ?>
        <!-- Issue Number Selection -->
        <div class="form-group">
            <label for="issue_no">Select Issue Number:</label>
            <select class="form-control" id="issue_no" name="issue_no" required>
                <?php for ($i = 1; $i <= $max_issue_no; $i++) : ?>
                    <option value="<?= $i; ?>">Issue <?= $i; ?></option>
                <?php endfor; ?>
            </select>
        </div>


        <!-- Categories and Titles -->
        <div id="categories-container">
            <div class="category-container" data-category-number="1" style="border: 2px solid #007bff;">
                <div class="category-header" style="background-color: #e9ecef;">
                    <label for="category_1">Category 1:</label>
                    <input type="text" class="form-control" id="category_1" name="categories[1][category_name]" required>
                </div>
                <div class="titles-container">
                    <div class="title-container" data-title-number="1" style="border: 2px solid #007bff;">
                        <label for="title_1_1">Title 1.1:</label>
                        <input type="text" class="form-control" id="title_1_1" name="categories[1][titles][1][title]" required>
                        <hr>
                        <label for="abstract_1_1">Abstract 1.1:</label>
                        <input type="text" class="form-control" id="abstract_1_1" name="categories[1][titles][1][abstract]" required>
                        <hr>
                        <label for="authors_1_1">Authors 1.1:</label>
                        <input type="text" class="form-control" id="authors_1_1" name="categories[1][titles][1][authors]" required>
                        <hr>
                        <!-- PDF Upload or URL Input -->
                        <div class="form-group">
                            <label for="pdf_upload_1_1">&nbsp;Upload PDF for Title 1.1:</label>
                            <input type="file" class="form-control" id="pdf_upload_1_1" name="categories[1][titles][1][pdf_upload]">
                            <br>
                            <span style="background-color: lightgrey; color:#333;padding:5px 10px; border-radius:5px;">OR <br></span>
                            <br>
                            <label for="pdf_url_1_1">&nbsp;Paste PDF URL:</label>
                            <input type="url" class="form-control" id="pdf_url_1_1" name="categories[1][titles][1][pdf_url]">
                        </div>

                        <button type="button" class="btn btn-danger btn-sm remove-title" onclick="removeTitle(this)">Remove Title</button>
                    </div>
                </div>
                <button type="button" class="btn btn-success btn-sm add-title" onclick="addTitle(this, 1)">Add Title</button>
                <button type="button" class="btn btn-danger btn-sm remove-category" onclick="removeCategory(this)">Remove Category</button>
            </div>
        </div>

        <button type="button" class="btn btn-primary" onclick="addCategory()">Add Category</button>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>


<!-- Include the external CSS and JS files -->
<link rel="stylesheet" href="./assets/css/add_content.css">
<script src="./assets/js/add_content.js"></script>

<!-- <?php include('includes/footer.php'); ?> -->

