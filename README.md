---

# 📚 PHARMA_2024

## Overview
The **PHARMA_2024** repository is a part of the **Mathematical Forum** initiative, initially developed for the **Mathematics Department** under the **Digital Solution Cell**. It is a comprehensive platform that facilitates the submission, management, and review of research papers. The repository version is archived (read-only), but the actual hosted version on 
**[mathematical-forum.org](http://mathematical-forum.org)** 
includes additional features for real-world use.

Institutes interested in implementing the project are welcome to contact the developer via [LinkedIn](https://www.linkedin.com/in/saheb-ullah-05292a258/).

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
  - Authors can submit their details and PDFs to be included in journal volumes.
  - After submission, an **Article ID** is generated for tracking.
  - Track the submission status (approved, rejected, or pending) using the Article ID on the **Track Status** page.

- **Additional Sections:**
  - **About:** Learn more about the journal.
  - **Guidelines:** Submission and formatting guidelines for authors.
  - **Editorial Board:** Information about the journal’s editorial team.
  - **Contact Us:** 
    - Secured contact form featuring Microsoft’s hCaptcha.

### Admin Panel Features

- **Login Page:**
  - Provides registration and forgot password functionality.
  - Forgot password feature integrated with **nodemailer** for OTP generation and reset.

- **Dashboard:**
  - Greets the admin by name and provides quick access to all management tools.

- **Volume Management:**
  - **Add Volume:**
    - Input fields to enter volume details:
      - Volume Name
      - ISSN
      - Editor Name
      - Date (dd-mm-yyyy)
      - Thumbnail Upload
  - **View Volumes:**
    - List all existing volumes with options to edit, activate/deactivate, or delete.

- **Volume Content Management:**
  - **Add Volume Content:**
    - Select volume and issue number.
    - Input fields to enter:
      - Category (e.g., Research Article)
      - Title
      - Authors
      - Upload PDF or provide PDF URL.
  - **View Content:**
    - List of content with edit, activate/deactivate, or delete options.

- **Submission Management:**
  - **View Submissions:**
    - View all author submissions with options to approve or reject them.

- **Super Admin Features:**
  - **Manage Admins:**
    - The super admin can manage all admins, including the ability to activate/deactivate any admin.
    - Deactivated admins will not be able to log in.
  - **Active Admins:**
    - A special tab allows viewing all active admins along with their login details.
    - Data table includes:
      - Username
      - Email
      - IP Address
      - Login Time
      - Status (Active/Inactive)
    - Filter by date functionality available.

- **Logout Functionality:**
  - Secure logout from the admin panel.

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
   git clone https://github.com/saheb-ul-lah/PHARMA_2024.git
   ```

2. **Set Up the MySQL Database**
   - Launch PHPMyAdmin and create a new database named `journal_db`.
   - Import the SQL commands from the `journal_db.sql` file to set up the database schema.

3. **Configure Database Credentials**
   - Update PHP files (e.g., `db_connect.php`) with your database credentials.

4. **Set Folder Permissions**
   - Ensure that the `uploads` directories have write permissions for file storage.

5. **Run the Project**
   - Move the project folder to the `htdocs` directory of XAMPP.
   - Start **Apache** and **MySQL** from the XAMPP Control Panel.
   - Access the project at `http://localhost/PHARMA_2024/client/My_HTML/`.
   - Access the admin panel at `http://localhost/PHARMA_2024/admin/login.php`.

---

## 🎯 How to Use

### For Authors
1. Navigate to the **Submit Paper** page.
2. Complete the form with your details and manuscript information.
3. Upload the manuscript file.
4. Submit the form and track the submission using the generated Article ID.

### For Administrators
1. Log in to the admin panel.
2. Manage submissions, journal volumes, and review processes.
3. Add new journal volumes and upload issues.

---

## 📂 Project Structure

```plaintext
PHARMA_2024/
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

⚠️ Ensure that the **uploads** directories have write permissions for storing uploaded files.

---

## 🔧 Troubleshooting

### Common Errors:
- **File Upload Error:** Ensure the destination directories (`submitted_manuscripts` and `volume_thumbnails`) have correct permissions.
- **Database Connection Error:** Double-check the database credentials (`host`, `user`, `password`, `db`) in the PHP files.
- **File Size Exceeded:** Ensure uploaded files are within the size limits, or adjust the size limit in your server settings.

---

## 📝 Project Status

This repository is archived (read-only), and no further contributions are accepted. However, if any institute or organization is interested in implementing this project, feel free to contact me through [LinkedIn](https://www.linkedin.com/in/md-sahebullah-262aa919b/).

---

## 🌟 Acknowledgments

Special thanks to all contributors and the open-source community for providing the tools and frameworks used in this project.

---
