
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Journal Website</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
            padding-top: 56px; /* Height of navbar */
        }
        .wrapper {
            display: flex;
            width: 100%;
        }
        #sidebar {
            box-shadow: 0 8px 10px rgba(0, 0, 0, 0.8);
            background-color: #343a40;
            min-width: 250px;
            max-width: 250px;
            background: #343a40;
            color: #fff;
            transition: all 0.3s;
            overflow-y: auto; /* Allow sidebar to scroll if content exceeds height */
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100; /* Ensure sidebar is above content */
        }
        #sidebar.active {
            min-width: 80px;
            max-width: 80px;
        }
        #sidebar .sidebar-header {
            padding: 20px;
            color: #343a40;
            background: lightsteelblue;
            text-align: center;
            border: 4px solid #343A40;
            border-radius: 10px;
        }
      
        #sidebar.active .sidebar-header h3 {
            display: inline-block;
        }
        #sidebar ul.components {
            padding: 20px 0;
            /* border-bottom: 1px solid #47748b; */
        }
        #sidebar ul p {
            color: #fff;
            padding: 10px;
        }
        #sidebar ul li a {
            padding: 10px;
            font-size: 1.1em;
            display: block;
            background-color: lightslategrey;
            border: 4px solid #343A40;
            border-radius: 10px;
            text-decoration: none;
        }
        #sidebar ul li a i {
            margin-right: 10px;
        }
        #sidebar.active ul li a {
            text-align: center;
        }
        #sidebar.active ul li a i {
            margin: 0;
        }
        #sidebar.active ul li a span {
            display: none;
        }
        #sidebarCollapse {
            display: none; /* Hide by default */
        }
        #content {
            width: calc(100% - 250px); /* Adjust content width based on sidebar width */
            padding: 20px;
            min-height: 100vh;
            transition: all 0.3s;
            margin-left: 250px; /* Offset content to account for sidebar */
        }
        

        /* Media Queries for responsiveness */
        @media (max-width: 850px) {
            #sidebar {
                min-width: 350px;
                max-width: 300px;
            }
            #sidebar.active {
                margin-left: -80px;
            }
            #content {
                width: 100%;
                margin-left: 0;
            }
            #sidebar.active + #content {
                margin-left: 0;
            }
            #sidebarCollapse {
                display: block; /* Display sidebar toggle button */
                position: absolute;
                top: 0px;
                right: 0px;
                /* margin: 30px; */
                background-color: lightslategrey;
                border: none;
                border-radius: 50%;
                z-index: 999; /* Ensure toggle button is above sidebar */
            }
            
        }
    </style>
</head>
<body>
<div class="wrapper">
    <nav id="sidebar">
        <div class="sidebar-header">
            <h3>CTPR</h3>
        </div>
        <ul class="list-unstyled components">
            <li>
                <a href="index.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a>
            </li>
            <li>
                <a href="add_journal.php"><i class="fas fa-plus"></i> <span>Add Volume</span></a>
            </li>
            <li>
                <a href="upload_journal.php"><i class="fas fa-upload"></i> <span>Upload Volume PDF</span></a>
            </li>
            <li>
                <a href="view_journals.php"><i class="fas fa-eye"></i> <span>View Volumes</span></a>
            </li>
            <li>
                <a href="add_content.php"><i class="fas fa-plus"></i> <span>Add Volume Content</span></a>
            </li>
            <li>
                <a href="view_content.php"><i class="fas fa-plus"></i> <span>View Volume Content</span></a>
            </li>
            <!-- <li>
                <a href="add_section.php"><i class="fas fa-sign-out-alt"></i> <span>Add Sections</span></a>
            </li> -->
            <li>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
            </li>
        </ul>
    </nav>
    <div id="content">
        <!-- <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <button type="button" id="sidebarCollapse" class="btn btn-info">
                </button>
            </div>
        </nav> -->
        <div class="main-content">
            
