---

# 📚 Mathematical Forum Website

## Overview
The **Mathematical Forum Website** is a platform developed for the **Mathematics Department** under the **Digital Solution Cell**. It streamlines the submission, management, and review process of mathematical research papers, offering a smooth experience for authors and robust management tools for administrators.

---

## 🚀 Features

### User Features

#### Homepage
- **Archives Section:** 
  - Browse all journal volumes.
  - **Filtering, Sorting, and Search** capabilities for easy navigation.
  - View details of issues within each volume, including:
    - **Issue Titles**
    - **Author Names**
    - **PDF Download/View Links**

- **Submit Papers:**
  - Authors can submit their details and PDFs for journal volumes.
  - An **Article ID** is generated after submission to track progress.
  - Authors can monitor the status of their submission (approved, rejected, or pending) using the **Track Status** page with their Article ID.

- **Additional Sections:**
  - **About:** Learn more about the journal.
  - **Guidelines:** Provides authors with submission and formatting guidelines.
  - **Editorial Board:** Information on the journal’s editorial team.
  - **Contact Us:** 
    - Secure contact form with Microsoft’s hCaptcha.

### Admin Panel Features

- **Login Page:**
  - Includes registration and forgot password options.
  - Forgot password functionality is integrated with **nodemailer** for OTP generation.

- **Dashboard:**
  - Greets the admin by name and provides quick access to management tools.

- **Volume Management:**
  - **Add Volume:** Admins can input details such as:
    - Volume Name
    - ISSN
    - Editor Name
    - Date (dd-mm-yyyy)
    - Thumbnail Upload
  - **View Volumes:** Allows editing, activating/deactivating, or deleting existing volumes.

- **Volume Content Management:**
  - **Add Volume Content:** Select the volume and issue, then input:
    - Category (e.g., Research Article)
    - Title
    - Authors
    - Upload PDF or provide PDF URL.
  - **View Content:** Offers options to edit, activate/deactivate, or delete content.

- **Submission Management:**
  - **View Submissions:** Displays all submissions, with options to approve or reject them.

- **Admin Management (Super Admin):**
  - **Manage Admins:** The Super Admin has the authority to:
    - Add, remove, or edit admins.
    - Deactivate/activate any admin. Deactivated admins will not be able to log in.
  - **Active Admins Tab:** Displays a list of logged-in admins, including:
    - Username
    - Email
    - IP Address
    - Login Time
    - Status (Active/Inactive)
  - Admins can filter this list by date.

- **Logout Functionality:**
  - Secure logout option for admins.

---

## 💻 Technology Stack

- **Frontend:** 
  - HTML5, CSS3, JavaScript
  - Bootstrap for responsive design

- **Backend:** 
  - PHP 7.x
  - MySQL for data storage

- **Development Tools:** 
  - XAMPP for local server environment
  - Git for version control

---

## 🛠️ Installation Guide

### Prerequisites
1. **XAMPP** (or similar local server with PHP and MySQL support)
2. **PHP** (7.x or higher)
3. **MySQL** (or MariaDB)

### Step-by-Step Installation

1. **Clone the Repository**
   ```bash
   git clone https://github.com/saheb-ul-lah/Mathematical_Forum.git
   ```

2. **Set Up the MySQL Database**
   - Launch PHPMyAdmin and create a new database named `journal_db`.
   - Import the SQL commands from the `mathematical_forum_db.sql` file to set up the database schema.

3. **Configure Database Credentials**
   - Update PHP files (e.g., `db_connect.php`) with your database credentials.

4. **Set Folder Permissions**
   - Ensure the `uploads` directories have write permissions for file storage.

5. **Run the Project**
   - Move the project folder to the `htdocs` directory of XAMPP.
   - Start **Apache** and **MySQL** from the XAMPP Control Panel.
   - Access the project at `http://localhost/Mathematical_Forum/client/My_HTML/`.

---

## 🎯 How to Use

### For Authors
1. Go to the **Submit Paper** page.
2. Fill in the required details and upload the manuscript file.
3. Submit the form and note the generated **Article ID** for future reference.
4. Track your submission status (approved, rejected, or pending) using the **Track Status** page with the Article ID.

### For Administrators
1. Log in to the admin panel with your credentials.
2. Use the dashboard to manage volumes, submissions, and content.
3. Add new journal volumes or upload new articles to existing volumes.
4. Super Admin can manage the access rights of other admins.

---

## 📂 Project Structure

```plaintext
Mathematical_Forum/
├── client/
│   ├── My_HTML/                  # HTML and frontend files
│   ├── forms/                    # Forms for submission and interaction
│   ├── assets/                   # Assets like CSS, JS, and images
│   ├── PHPMailer/                # For email-related functionalities
│   ├── submit_paper.php          # Manuscript submission logic
│   ├── fetch_volumes.php         # Backend for fetching journal volumes
│   ├── contact.php               # Backend for contact form
│   ├── newsletter.php            # Newsletter subscription functionality
├── admin/
│   ├── PHPMailer/                # PHPMailer for sending emails
│   ├── assets/                   # CSS, JS, images for admin panel
│   ├── includes/                 # Reusable PHP components
│   ├── uploads/                  # Storage for submitted manuscripts and volume thumbnails
│   ├── vendor/                   # Composer dependencies
│   ├── add_journal.php           # Admin function to add a new journal volume
│   ├── add_content.php           # Admin function to add content to a volume
│   ├── view_journals.php         # Admin view for managing journals
│   ├── forgot_password.php       # Password reset functionality
│   ├── login.php                 # Admin login page
│   ├── register.php              # Admin registration page
│   ├── verify_otp.php            # OTP verification for account recovery
├── includes/
│   ├── db_connect.php            # Database connection file
│   ├── header.php                # Common header for frontend UI
├── mathematical_forum_db.sql     # SQL script to set up MySQL database
└── README.md                     # Project documentation
```

---

## 📥 File Uploads

- Manuscripts are stored in `admin/uploads/submitted_manuscripts/`.
- Thumbnails for journal volumes are stored in `admin/uploads/volume_thumbnails/`.

⚠️ Ensure that the **uploads** directories have write permissions for file storage.

---

## 🔧 Troubleshooting

### Common Errors:
- **File Upload Error:** Ensure the destination directories (`submitted_manuscripts` and `volume_thumbnails`) have correct permissions.
- **Database Connection Error:** Double-check the database credentials (`host`, `user`, `password`, `db`) in the PHP files.
- **File Size Exceeded:** Ensure uploaded files are within the size limits, or adjust the size limit in your server settings.

---

## 📝 Contact Information

The project is archived and set as **read-only**, but if any institution or individual is interested in implementing this project, feel free to contact me via [LinkedIn](https://www.linkedin.com/in/saheb-ul-lah).

---
