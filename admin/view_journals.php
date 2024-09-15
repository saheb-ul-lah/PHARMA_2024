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
    <h2>View Volumes</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Volume Name</th>
                <th>ISSN</th>
                <th>Editor Name</th>
                <th>Date</th>
                <th>PDF</th>
                <th>Thumbnail</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include('includes/db_connect.php');
            $result = $conn->query("SELECT * FROM pharma_journals");

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['journal_name']) . "</td>
                            <td>" . htmlspecialchars($row['issn']) . "</td>
                            <td>" . htmlspecialchars($row['editor_name']) . "</td>
                            <td>" . htmlspecialchars($row['date']) . "</td>
                            <td>";
                    if ($row['pdf']) {
                        echo "<a href='uploads/" . htmlspecialchars($row['pdf']) . "' target='_blank'>View PDF</a>";
                    } else {
                        echo "No PDF";
                    }
                    echo "</td>
                            <td>";
                    if ($row['volume_thumbnail']) {
                        // Check if the thumbnail is a URL or a local file
                        if (filter_var($row['volume_thumbnail'], FILTER_VALIDATE_URL)) {
                            echo "<img src='" . htmlspecialchars($row['volume_thumbnail']) . "' alt='Thumbnail' style='width: 100px; height: auto;'>";
                        } else {
                            echo "<img src='uploads/" . htmlspecialchars($row['volume_thumbnail']) . "' alt='Thumbnail' style='width: 100px; height: auto;'>";
                        }
                    } else {
                        echo "No Thumbnail";
                    }
                    echo "</td>
                            <td>" . ucfirst(htmlspecialchars($row['status'])) . "</td>
                            <td class='action-buttons'>
                                <a href='edit_journal.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-info'>Edit</a>
                                <a href='activate_deactivate_journal.php?id=" . htmlspecialchars($row['id']) . "&action=" . ($row['status'] == 'active' ? 'deactivate' : 'activate') . "' class='btn btn-warning'>" . ($row['status'] == 'active' ? 'Deactivate' : 'Activate') . "</a>
                                <button type='button' class='btn btn-danger' data-toggle='modal' data-target='#confirmDeleteModal' data-journal-id='" . htmlspecialchars($row['id']) . "'>Delete</button>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No journals available</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this journal?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDeleteButton" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<!-- Include Footer -->
<!-- <?php include('includes/footer.php'); ?> -->
<div style="opacity: 0;"><?php include('includes/footer.php'); ?></div>


<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
        color: #333;
        margin: 0;
        padding: 0;
        overflow-y: scroll; /* Enable vertical scrolling */
    }

    .container {
        max-width: 100%; /* Full width */
        padding: 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        overflow-y: auto; /* Enable scrolling for the container */
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
        animation: fadeIn 0.5s ease;
    }

    .table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }

    .table th, .table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .table th {
        background-color: #007bff;
        color: white;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f2f2f2;
    }

    .action-buttons {
        display: flex;
        justify-content: space-between; /* Align buttons in a row */
        flex-wrap: nowrap; /* Prevent wrapping */
    }

    .btn {
        transition: background-color 0.3s, transform 0.2s;
        margin-right: 5px; /* Space between buttons */
    }

    .btn:hover {
        transform: scale(1.05);
    }

    .toast {
        visibility: hidden;
        min-width: 250px;
        margin-left: -125px;
        background-color: #333;
        color: #fff;
        text-align: center;
        border-radius: 5px;
        padding: 16px;
        position: fixed;
        z-index: 1;
        left: 50%;
        bottom: 30px;
        font-size: 17px;
        transition: visibility 0s, opacity 0.5s linear;
        opacity: 0;
    }

    .toast.show {
        visibility: visible;
        opacity: 1;
    }

    @keyframes fadeIn {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }

    @media (max-width: 768px) {
        .container {
            width: 90%; /* Responsive width for smaller screens */
        }
    }
</style>

<script>
    $(document).ready(function() {
        // Handle modal deletion confirmation
        $('#confirmDeleteModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var journalId = button.data('journal-id'); // Extract journal ID from data attribute
            
            // Update href of Delete button in modal based on journal ID
            var confirmDeleteButton = $(this).find('#confirmDeleteButton');
            confirmDeleteButton.attr('href', 'delete_journal.php?id=' + journalId);
        });

        // Optional: Handle confirmation action on the delete button
        $('#confirmDeleteButton').on('click', function(event) {
            // Ensure the href attribute is set correctly before the button is clicked
            var href = $(this).attr('href');
            if (!href) {
                event.preventDefault();
                alert('Deletion link is not set properly.');
            }
        });
    });
</script>
