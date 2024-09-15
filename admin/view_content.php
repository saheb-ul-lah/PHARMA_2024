<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include('includes/header.php');
include('includes/db_connect.php');

// Pagination settings
$limit = 10; // Number of entries per page
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
$start_from = ($page - 1) * $limit;

// Fetch content data with pagination
$content_query = "SELECT * FROM pharma_content ORDER BY volume_id, issue_number LIMIT $start_from, $limit";
$content_result = $conn->query($content_query);

// Fetch total number of records for pagination
$total_query = "SELECT COUNT(*) FROM pharma_content";
$total_result = $conn->query($total_query);
$total_records = $total_result->fetch_row()[0];
$total_pages = ceil($total_records / $limit);
?>

<div class="container">
    <h2>View Content <p>Scroll right -> (use Shift + Scroll)</p></h2>
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr style="background-color: #007BFF;">
                <th>Actions</th>
                <th>ID</th>
                <th>Volume ID</th>
                <th>Issue Number</th>
                <th>Category Name</th>
                <th>Title</th>
                <th>Abstract</th>
                <th>Authors</th>
                <th>PDF File</th>
                <th>PDF URL</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $content_result->fetch_assoc()) : ?>
                <tr>
                    <td class="action-buttons">
                        <a href="edit_content.php?id=<?= $row['id']; ?>" class="btn btn-info">Edit</a>
                        <a href="activate_deactivate_content.php?id=<?= $row['id']; ?>&action=<?= $row['status'] == 'active' ? 'deactivate' : 'activate'; ?>" class="btn btn-warning">
                            <?= $row['status'] == 'active' ? 'Deactivate' : 'Activate'; ?>
                        </a>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal" data-content-id="<?= $row['id']; ?>">Delete</button>
                    </td>
                    <td><?= $row['id']; ?></td>
                    <td><?= $row['volume_id']; ?></td>
                    <td><?= $row['issue_number']; ?></td>
                    <td><?= $row['category_name']; ?></td>
                    <td><?= $row['title']; ?></td>
                    <td><?= $row['abstract']; ?></td>
                    <td><?= $row['authors']; ?></td>
                    <td>
                        <?php if ($row['pdf_file_path']) : ?>
                            <a href="<?= $row['pdf_file_path']; ?>" target="_blank">View PDF</a>
                        <?php else : ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['pdf_url']) : ?>
                            <a href="<?= $row['pdf_url']; ?>" target="_blank">View PDF</a>
                        <?php else : ?>
                            N/A
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1) : ?>
                <li class="page-item"><a class="page-link" href="view_content.php?page=<?= $page - 1; ?>">Previous</a></li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <li class="page-item <?= $i == $page ? 'active' : ''; ?>"><a class="page-link" href="view_content.php?page=<?= $i; ?>"><?= $i; ?></a></li>
            <?php endfor; ?>
            <?php if ($page < $total_pages) : ?>
                <li class="page-item"><a class="page-link" href="view_content.php?page=<?= $page + 1; ?>">Next</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<!-- Modal for Delete Confirmation -->
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
                <p>Are you sure you want to delete this content?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDeleteButton" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<!-- Include the external CSS and JS files -->
<link rel="stylesheet" href="./assets/css/view_content.css">
<?php include('includes/footer.php'); ?>

<script>
    $(document).ready(function() {
        // Handle modal deletion confirmation
        $('#confirmDeleteModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var contentId = button.data('content-id'); // Extract content ID from data attribute
            
            // Update href of Delete button in modal based on content ID
            var confirmDeleteButton = $(this).find('#confirmDeleteButton');
            confirmDeleteButton.attr('href', 'delete_content.php?id=' + contentId);
        });

        // Optional: Handle confirmation action on the delete button
        $('#confirmDeleteButton').on('click', function(event) {
            var href = $(this).attr('href');
            if (!href) {
                event.preventDefault();
                alert('Deletion link is not set properly.');
            }
        });
    });
</script>
