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
    <h2>Add Content</h2>
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

<script>
    let categoryCount = 1;
    let titleCount = {
        1: 1
    };

    function addCategory() {
        categoryCount++;
        titleCount[categoryCount] = 1;

        const categoryColor = (categoryCount % 2 === 0) ? '#28a745' : '#007bff'; // Alternating colors

        const categoryContainer = document.createElement('div');
        categoryContainer.classList.add('category-container');
        categoryContainer.dataset.categoryNumber = categoryCount;
        categoryContainer.style.border = `2px solid ${categoryColor}`;
        categoryContainer.innerHTML = `
            <div class="category-header" style="background-color: #e9ecef;">
                <label for="category_${categoryCount}">Category ${categoryCount}:</label>
                <input type="text" class="form-control" id="category_${categoryCount}" name="categories[${categoryCount}][category_name]" required>
            </div>
            <div class="titles-container">
                <div class="title-container" data-title-number="1" style="border: 2px solid ${categoryColor};">
                    <label for="title_${categoryCount}_1">Title ${categoryCount}.1:</label>
                    <input type="text" class="form-control" id="title_${categoryCount}_1" name="categories[${categoryCount}][titles][1][title]" required>
                    <label for="abstract_${categoryCount}_1">Abstract ${categoryCount}.1:</label>
                    <input type="text" class="form-control" id="abstract_${categoryCount}_1" name="categories[${categoryCount}][titles][1][abstract]" required>
                    <label for="authors_${categoryCount}_1">Authors ${categoryCount}.1:</label>
                    <input type="text" class="form-control" id="authors_${categoryCount}_1" name="categories[${categoryCount}][titles][1][authors]" required>

                    <!-- PDF Upload or URL Input -->
                    <div class="form-group">
                        <label for="pdf_upload_${categoryCount}_1">Upload PDF for Title ${categoryCount}.1:</label>
                        <input type="file" class="form-control" id="pdf_upload_${categoryCount}_1" name="categories[${categoryCount}][titles][1][pdf_upload]">
                        <label for="pdf_url_${categoryCount}_1">Or Paste PDF URL:</label>
                        <input type="url" class="form-control" id="pdf_url_${categoryCount}_1" name="categories[${categoryCount}][titles][1][pdf_url]">
                    </div>

                    <button type="button" class="btn btn-danger btn-sm remove-title" onclick="removeTitle(this)">Remove Title</button>
                </div>
            </div>
            <button type="button" class="btn btn-success btn-sm add-title" onclick="addTitle(this, ${categoryCount})">Add Title</button>
            <button type="button" class="btn btn-danger btn-sm remove-category" onclick="removeCategory(this)">Remove Category</button>
        `;

        document.getElementById('categories-container').appendChild(categoryContainer);
    }

    function addTitle(button, categoryNumber) {
        titleCount[categoryNumber]++;
        const titleColor = (categoryNumber % 2 === 0) ? '#28a745' : '#007bff'; // Alternating colors

        const titleContainer = document.createElement('div');
        titleContainer.classList.add('title-container');
        titleContainer.dataset.titleNumber = titleCount[categoryNumber];
        titleContainer.style.border = `2px solid ${titleColor}`;
        titleContainer.innerHTML = `
            <label for="title_${categoryNumber}_${titleCount[categoryNumber]}">Title ${categoryNumber}.${titleCount[categoryNumber]}:</label>
            <input type="text" class="form-control" id="title_${categoryNumber}_${titleCount[categoryNumber]}" name="categories[${categoryNumber}][titles][${titleCount[categoryNumber]}][title]" required>
            <label for="abstract_${categoryNumber}_${titleCount[categoryNumber]}">Abstract ${categoryNumber}.${titleCount[categoryNumber]}:</label>
            <input type="text" class="form-control" id="abstract_${categoryNumber}_${titleCount[categoryNumber]}" name="categories[${categoryNumber}][titles][${titleCount[categoryNumber]}][abstract]" required>
            <label for="authors_${categoryNumber}_${titleCount[categoryNumber]}">Authors ${categoryNumber}.${titleCount[categoryNumber]}:</label>
            <input type="text" class="form-control" id="authors_${categoryNumber}_${titleCount[categoryNumber]}" name="categories[${categoryNumber}][titles][${titleCount[categoryNumber]}][authors]" required>

            <!-- PDF Upload or URL Input -->
            <div class="form-group">
                <label for="pdf_upload_${categoryNumber}_${titleCount[categoryNumber]}">Upload PDF for Title ${categoryNumber}.${titleCount[categoryNumber]}:</label>
                <input type="file" class="form-control" id="pdf_upload_${categoryNumber}_${titleCount[categoryNumber]}" name="categories[${categoryNumber}][titles][${titleCount[categoryNumber]}][pdf_upload]">
                <label for="pdf_url_${categoryNumber}_${titleCount[categoryNumber]}">Or Paste PDF URL:</label>
                <input type="url" class="form-control" id="pdf_url_${categoryNumber}_${titleCount[categoryNumber]}" name="categories[${categoryNumber}][titles][${titleCount[categoryNumber]}][pdf_url]">
            </div>

            <button type="button" class="btn btn-danger btn-sm remove-title" onclick="removeTitle(this)">Remove Title</button>
        `;

        button.parentElement.querySelector('.titles-container').appendChild(titleContainer);
    }

    function removeCategory(button) {
        button.parentElement.remove();
        resetCategoryNumbers();
    }

    function removeTitle(button) {
        const titleContainer = button.parentElement;
        const categoryContainer = titleContainer.parentElement.parentElement;
        titleContainer.remove();

        resetTitleNumbers(categoryContainer);
    }

    function resetCategoryNumbers() {
        categoryCount = 0;
        const categories = document.querySelectorAll('.category-container');

        categories.forEach((category, index) => {
            categoryCount++;
            const categoryColor = (categoryCount % 2 === 0) ? '#28a745' : '#007bff'; // Alternating colors
            category.dataset.categoryNumber = categoryCount;
            category.style.border = `2px solid ${categoryColor}`;

            category.querySelector('.category-header label').setAttribute('for', `category_${categoryCount}`);
            category.querySelector('.category-header label').innerText = `Category ${categoryCount}:`;
            category.querySelector('.category-header input').setAttribute('id', `category_${categoryCount}`);
            category.querySelector('.category-header input').setAttribute('name', `categories[${categoryCount}][category_name]`);

            resetTitleNumbers(category);
        });
    }

    function resetTitleNumbers(categoryContainer) {
        const categoryNumber = categoryContainer.dataset.categoryNumber;
        titleCount[categoryNumber] = 0;
        const titles = categoryContainer.querySelectorAll('.title-container');

        titles.forEach((title, index) => {
            titleCount[categoryNumber]++;
            const titleColor = (categoryNumber % 2 === 0) ? '#28a745' : '#007bff'; // Alternating colors
            title.dataset.titleNumber = titleCount[categoryNumber];
            title.style.border = `2px solid ${titleColor}`;

            title.querySelector('label[for^="title_"]').setAttribute('for', `title_${categoryNumber}_${titleCount[categoryNumber]}`);
            title.querySelector('label[for^="title_"]').innerText = `Title ${categoryNumber}.${titleCount[categoryNumber]}:`;
            title.querySelector('input[id^="title_"]').setAttribute('id', `title_${categoryNumber}_${titleCount[categoryNumber]}`);
            title.querySelector('input[id^="title_"]').setAttribute('name', `categories[${categoryNumber}][titles][${titleCount[categoryNumber]}][title]`);

            title.querySelector('label[for^="abstract_"]').setAttribute('for', `abstract_${categoryNumber}_${titleCount[categoryNumber]}`);
            title.querySelector('label[for^="abstract_"]').innerText = `Abstract ${categoryNumber}.${titleCount[categoryNumber]}:`;
            title.querySelector('input[id^="abstract_"]').setAttribute('id', `abstract_${categoryNumber}_${titleCount[categoryNumber]}`);
            title.querySelector('input[id^="abstract_"]').setAttribute('name', `categories[${categoryNumber}][titles][${titleCount[categoryNumber]}][abstract]`);

            title.querySelector('label[for^="authors_"]').setAttribute('for', `authors_${categoryNumber}_${titleCount[categoryNumber]}`);
            title.querySelector('label[for^="authors_"]').innerText = `Authors ${categoryNumber}.${titleCount[categoryNumber]}:`;
            title.querySelector('input[id^="authors_"]').setAttribute('id', `authors_${categoryNumber}_${titleCount[categoryNumber]}`);
            title.querySelector('input[id^="authors_"]').setAttribute('name', `categories[${categoryNumber}][titles][${titleCount[categoryNumber]}][authors]`);

            title.querySelector('label[for^="pdf_upload_"]').setAttribute('for', `pdf_upload_${categoryNumber}_${titleCount[categoryNumber]}`);
            title.querySelector('input[id^="pdf_upload_"]').setAttribute('id', `pdf_upload_${categoryNumber}_${titleCount[categoryNumber]}`);
            title.querySelector('input[id^="pdf_upload_"]').setAttribute('name', `categories[${categoryNumber}][titles][${titleCount[categoryNumber]}][pdf_upload]`);

            title.querySelector('label[for^="pdf_url_"]').setAttribute('for', `pdf_url_${categoryNumber}_${titleCount[categoryNumber]}`);
            title.querySelector('input[id^="pdf_url_"]').setAttribute('id', `pdf_url_${categoryNumber}_${titleCount[categoryNumber]}`);
            title.querySelector('input[id^="pdf_url_"]').setAttribute('name', `categories[${categoryNumber}][titles][${titleCount[categoryNumber]}][pdf_url]`);
        });
    }
</script>

<!-- <?php
        include('includes/footer.php');
        ?> -->

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');

    body {
        background-color: #f8f9fa;
        color: #333;
        font-family: 'Poppins', sans-serif;
        padding: 0;
        margin: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .container {
        width: 90%;
        max-width: 1200px;
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        overflow-y: auto;
        max-height: 90vh;
    }

    .category-header {
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
    }

    .form-group,
    .category-container,
    .title-container {
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

    .btn-primary,
    .btn-success,
    .btn-danger {
        margin-top: 10px;
    }

    .add-title {
        margin: 10px;
    }

    .titles-container {
        padding-left: 20px;
        margin-top: 10px;
        margin-right: 10px;
    }

    .category-container {
        border-radius: 6px;
    }

    .title-container {
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
        background-color: #f0f0f0;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .remove-category {
        margin-bottom: 10px;
    }
</style>