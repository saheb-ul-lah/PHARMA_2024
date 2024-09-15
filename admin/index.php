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

// Database connection
include('includes/db_connect.php');

// Get the total number of volumes
$sql_volumes = "SELECT COUNT(*) FROM pharma_journals";
$result_volumes = $conn->query($sql_volumes);
$total_volumes = $result_volumes->fetch_row()[0];

// Total contributors, visitors, and members (hardcoded for now)
$total_contributors = 22;
$total_visitors = 2000;
$total_members = 12;
?>
<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($admin_email); ?></h2>
    <p>This is the admin dashboard.</p>
    <a href="logout.php" class="btn btn-danger">Logout</a>
</div>

<div class="grid-container">
    <!-- <div class="grid-item">
        <h3>Total Volumes</h3>
        <p><?php echo $total_volumes; ?></p>
    </div> -->
    <!-- <div class="grid-item">
        <h3>Total Contributors</h3>
        <p><?php echo $total_contributors; ?></p>
    </div>
    <div class="grid-item">
        <h3>Total Visitors</h3>
        <p><?php echo $total_visitors; ?></p>
    </div>
    <div class="grid-item">
        <h3>Total Members</h3>
        <p><?php echo $total_members; ?></p>
    </div> -->
</div>

<div class="guidelines">
    <h3>Guidelines</h3>
    <div class="accordion">
        <div class="accordion-item">
            <button class="accordion-header">
                <strong>What is CTPR?</strong>
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="accordion-content">
                <p>CTPR stands for <strong>"Current Trends in Pharmaceutical Research"</strong>, a platform for managing and accessing technical papers and journals of the <strong>Department of Pharmaceutical Sciences</strong>, Dibrugarh University.</p>
            </div>
        </div>
        <div class="accordion-item">
            <button class="accordion-header">
                <strong>Dashboard</strong>
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="accordion-content">
                <p>The dashboard provides an overview of the key metrics including total volumes, contributors, visitors, and members. It is the starting point for navigating the admin panel.</p>
            </div>
        </div>
        <div class="accordion-item">
            <button class="accordion-header">
                <strong>Add Volume</strong>
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="accordion-content">
                <p>This section allows you to add new volumes to the system. You can input details such as the volume title, description, and publication date.</p>
                <h4>Step-by-Step Guide:</h4>
                <ol>
                    <li>Navigate to the "Add Volume" section from the sidebar.</li>
                    <li>Fill in the volume details in the form provided.</li>
                    <li>Click "Submit" to add the volume to the database.</li>
                </ol>
            </div>
        </div>
        <div class="accordion-item">
            <button class="accordion-header">
                <strong>Upload Volume PDF</strong>
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="accordion-content">
                <p>Upload the PDF file associated with the volume. This file will be made available for download or viewing within the platform.</p>
                <h4>Step-by-Step Guide:</h4>
                <ol>
                    <li>Go to the "Upload Volume PDF" section from the sidebar.</li>
                    <li>Select the volume for which you want to upload a PDF.</li>
                    <li>Choose the PDF file from your local system.</li>
                    <li>Click "Upload" to attach the PDF to the selected volume.</li>
                </ol>
            </div>
        </div>
        <div class="accordion-item">
            <button class="accordion-header">
                <strong>View Volumes</strong>
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="accordion-content">
                <p>View the list of all volumes available in the system. This section allows you to browse and search for specific volumes.</p>
            </div>
        </div>
        <div class="accordion-item">
            <button class="accordion-header">
                <strong>Add Volume Content</strong>
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="accordion-content">
                <p>Add content to a volume, such as abstracts, chapters, or supplementary materials.</p>
                <h4>Step-by-Step Guide:</h4>
                <ol>
                    <li>Navigate to the "Add Volume Content" section.</li>
                    <li>Select the volume to which you want to add content.</li>
                    <li>Enter the content details in the provided fields.</li>
                    <li>Click "Submit" to add the content to the volume.</li>
                </ol>
            </div>
        </div>
        <div class="accordion-item">
            <button class="accordion-header">
                <strong>View Volume Content</strong>
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="accordion-content">
                <p>View the content associated with each volume. This allows you to review and manage the details added to each volume.</p>
            </div>
        </div>
        <div class="accordion-item">
            <button class="accordion-header">
                <strong>Logout</strong>
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="accordion-content">
                <p>Log out of the admin panel to end your session and secure your account.</p>
                <h4>Step-by-Step Guide:</h4>
                <ol>
                    <li>Click on the "Logout" button at the top of the page.</li>
                    <li>You will be redirected to the login page.</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
        color: #333;
        margin-top: 100px;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        overflow-y: scroll; /* Enable vertical scrolling */
    }

    .container {
        max-width: 100%; /* Adjust as per your design */
        padding: 30px;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border-radius: 15px; 
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        animation: fadeIn 0.5s ease;
        text-align: center;
    }

    .guidelines {
        /* margin-top: 30px;  */
        background: rgba(255, 255, 255, 0.9);
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        padding: 20px;
        animation: fadeIn 0.5s ease;
    }

    .guidelines h3 {
        font-size: 24px;
        margin-bottom: 20px;
        color: #007bff;
    }

    .accordion {
        border-radius: 10px;
        overflow: hidden;
    }

    .accordion-item {
        border-bottom: 1px solid #ddd;
    }

    .accordion-header {
        background: #f8f9fa;
        border: none;
        padding: 15px;
        text-align: left;
        font-size: 18px;
        font-weight: 600;
        color: #333;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background-color 0.3s;
    }

    .accordion-header:hover {
        background-color: #e2e6ea;
    }

    .accordion-content {
        max-height: 0;
        overflow: hidden;
        padding: 0 15px;
        background-color: #fff;
        transition: max-height 0.3s ease-out;
    }

    .accordion-content p {
        padding: 15px 0;
        margin: 0;
        font-size: 16px;
        color: #666;
    }

    .accordion-item.active .accordion-content {
        max-height: 300px; /* Adjust as needed */
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

        .grid-container {
            grid-template-columns: 1fr; /* Single column layout for smaller screens */
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const accordions = document.querySelectorAll('.accordion-header');

        accordions.forEach(header => {
            header.addEventListener('click', function() {
                const item = this.parentElement;
                const content = item.querySelector('.accordion-content');

                if (item.classList.contains('active')) {
                    item.classList.remove('active');
                    content.style.maxHeight = null;
                } else {
                    document.querySelectorAll('.accordion-item').forEach(i => {
                        i.classList.remove('active');
                        i.querySelector('.accordion-content').style.maxHeight = null;
                    });
                    item.classList.add('active');
                    content.style.maxHeight = content.scrollHeight + "px";
                }
            });
        });
    });
</script>
