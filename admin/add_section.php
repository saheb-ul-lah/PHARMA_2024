<?php
include('includes/db_connect.php'); // Include the database connection

// Initialize variables
$journal_id = $section_name = $start_page = $end_page = $profile_name = '';
$journal_id_err = $section_name_err = $start_page_err = $end_page_err = $profile_name_err = '';
$success_message = '';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate journal_id (selected from dropdown)
    $journal_id = $_POST['journal_id'];
    
    // Validate section_name
    if (empty(trim($_POST["section_name"]))) {
        $section_name_err = "Please enter section name.";
    } else {
        $section_name = trim($_POST["section_name"]);
    }
    
    // Validate start_page
    if (empty(trim($_POST["start_page"]))) {
        $start_page_err = "Please enter start page.";
    } elseif (!is_numeric($_POST["start_page"])) {
        $start_page_err = "Start page must be a number.";
    } else {
        $start_page = trim($_POST["start_page"]);
    }
    
    // Validate end_page
    if (empty(trim($_POST["end_page"]))) {
        $end_page_err = "Please enter end page.";
    } elseif (!is_numeric($_POST["end_page"])) {
        $end_page_err = "End page must be a number.";
    } else {
        $end_page = trim($_POST["end_page"]);
    }
    
    // Validate profile_name
    if (empty(trim($_POST["profile_name"]))) {
        $profile_name_err = "Please enter profile name.";
    } else {
        $profile_name = trim($_POST["profile_name"]);
    }
    
    // Check input errors before inserting into database
    if (empty($section_name_err) && empty($start_page_err) && empty($end_page_err) && empty($profile_name_err)) {
        // Insert into pdf_sections table
        $sql = "INSERT INTO pharma_sections (journal_id, section_name, start_page, end_page, profile_name) 
                VALUES (?, ?, ?, ?, ?)";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("isiss", $journal_id, $section_name, $start_page, $end_page, $profile_name);
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Section added successfully
                $success_message = "Section added successfully.";
                // Reset form input variables
                $journal_id = $section_name = $start_page = $end_page = $profile_name = '';
            } else {
                echo "<div class='alert alert-danger'>Error adding section: " . $conn->error . "</div>";
            }
            
            // Close statement
            $stmt->close();
        }
    }
    
    // Close connection
    $conn->close();
}
?>

<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Add Section to Journal</h2>
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $success_message; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <script>
            setTimeout(function() {
                location.reload();
            }, 2000); // Reload page after 2 seconds
        </script>
    <?php endif; ?>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="journal_id">Journal:</label>
            <select class="form-control" id="journal_id" name="journal_id">
                <?php
                $sql = "SELECT id, journal_name FROM pharma_journals WHERE status = 'active'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $selected = ($journal_id == $row['id']) ? 'selected' : '';
                        echo "<option value=\"" . $row['id'] . "\" $selected>" . $row['journal_name'] . "</option>";
                    }
                }
                ?>
            </select>
            <span class="text-danger"><?php echo $journal_id_err; ?></span>
        </div>
        <div class="form-group">
            <label for="section_name">Section Name:</label>
            <input type="text" class="form-control" id="section_name" name="section_name" value="<?php echo $section_name; ?>" required>
            <span class="text-danger"><?php echo $section_name_err; ?></span>
        </div>
        <div class="form-group">
            <label for="start_page">Start Page:</label>
            <input type="number" class="form-control" id="start_page" name="start_page" value="<?php echo $start_page; ?>" required>
            <span class="text-danger"><?php echo $start_page_err; ?></span>
        </div>
        <div class="form-group">
            <label for="end_page">End Page:</label>
            <input type="number" class="form-control" id="end_page" name="end_page" value="<?php echo $end_page; ?>" required>
            <span class="text-danger"><?php echo $end_page_err; ?></span>
        </div>
        <div class="form-group">
            <label for="profile_name">Profile Name:</label>
            <input type="text" class="form-control" id="profile_name" name="profile_name" value="<?php echo $profile_name; ?>" required>
            <span class="text-danger"><?php echo $profile_name_err; ?></span>
        </div>
        <!-- Add file upload field for profile_image if needed -->
        <button type="submit" class="btn btn-primary">Add Section</button>
    </form>
</div>

<!-- <?php include('includes/footer.php'); ?> -->

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
        color: #333;
        /* margin: 0 0 110px 0; */
        margin: 0 0 0 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        /* overflow-y: scroll; */
    }

    .container {
        max-width: 600px;
        padding: 30px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        animation: fadeIn 0.5s ease;
    }

    h2 {
        text-align: center;
        margin-bottom: 30px;
        animation: slideDown 0.5s ease;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-control {
        border-radius: 5px;
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    .btn-primary {
        width: 100%;
        padding: 12px;
        background-color: #007bff;
        border: none;
        border-radius: 5px;
        color: white;
        transition: background-color 0.3s, transform 0.2s;
        animation: fadeIn 0.5s ease 0.2s backwards;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        transform: scale(1.05);
    }

    .alert {
        margin-bottom: 20px;
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }

    @keyframes slideDown {
        0% {
            transform: translateY(-20px);
            opacity: 0;
        }
        100% {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>