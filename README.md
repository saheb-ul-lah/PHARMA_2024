---

# 📚 Mathematical Forum Website

## Overview
The **Mathematical Forum Website** is a comprehensive platform designed for the **Mathematics Department** to facilitate the submission, management, and review of mathematical research papers. Developed under the **Digital Solution Cell**, this user-friendly interface streamlines interactions between authors and administrators, ensuring an efficient publishing process.

---

## 🚀 Features

### User Features

#### Homepage
- **Archives Section:** 
  - Access all journal volumes.
  - **Filtering, Sorting, and Search** functionality for easy navigation.
  - Click on a volume to view detailed issues, displaying:
    - **Issue Titles**
    - **Author Names**
    - **PDF Download/View Links**

- **Submit Papers:**
  - Authors can submit their details and PDFs to be added to a volume.
  - Upon submission, an **Article ID** is generated for tracking.
  - Check submission status (approved, rejected, or pending) using the Article ID on the **Track Status** page.

- **Additional Sections:**
  - **About:** Overview of the journal.
  - **Guidelines:** Submission and formatting instructions.
  - **Editorial Board:** Information about the editorial team.
  - **Contact Us:** 
    - Contact form secured with Microsoft’s hCaptcha.

### Admin Panel Features

- **Login Page:**
  - Registration and password recovery functionalities.
  - Password recovery uses **nodemailer** for OTP generation.

- **Dashboard:**
  - Welcomes the admin by name.

- **Volume Management:**
  - **Add Volume:**
    - Input fields for:
      - Volume Name
      - ISSN
      - Editor Name
      - Date (dd-mm-yyyy)
      - Thumbnail Upload
  - **View Volumes:**
    - List of existing volumes with options to edit, activate/deactivate, or delete.

- **Volume Content Management:**
  - **Add Volume Content:**
    - Select volume and issue number.
    - Input fields for:
      - Category (e.g., Research Article)
      - Title
      - Authors
      - Upload PDF or paste PDF URL.
  - **View Content:**
    - List of content with options to edit, activate/deactivate, or delete.

- **Submission Management:**
  - **View Submissions:**
    - List of all author submissions with options to approve or reject.

- **Logout Functionality:**
  - Secure logout option for the admin.

---

## 💻 Technology Stack

- **Frontend:** 
  - HTML5, CSS3, JavaScript
  - Bootstrap for responsive design

- **Backend:** 
  - PHP 7.x
  - MySQL for data storage
  - PDO for secure database interaction

- **Development Tools:** 
  - XAMPP for local server
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
   - Run the SQL commands from the `mathematical_forum_db.sql` file to set up tables.

3. **Configure Database Credentials**
   - Update PHP files with your database credentials.

4. **Set Folder Permissions**
   - Ensure that the upload directories have write permissions.

5. **Run the Project**
   - Move the folder to the `htdocs` directory of XAMPP.
   - Start **Apache** and **MySQL** from the XAMPP Control Panel.
   - Access the project at `http://localhost/Mathematical_Forum/client/My_HTML/`.

---

## 🎯 How to Use

### For Authors
1. Go to the **Submit Paper** page.
2. Fill out the form with your details and manuscript information.
3. Upload your manuscript file.
4. Submit the form and await confirmation.

### For Administrators
1. Log in to the admin panel.
2. Manage submissions and journal volumes.
3. Review manuscripts and oversee editorial processes.

---

## 📂 Project Structure

```plaintext
Mathematical_Forum/
├── client/
│   ├── My_HTML/                  # HTML and frontend files
│   ├── submit_paper.php          # Manuscript submission logic
├── admin/
│   ├── uploads/
│   │   ├── submitted_manuscripts/ # Directory for submitted manuscripts
│   │   └── volume_thumbnails/     # Directory for volume thumbnails
│   ├── add_journal.php           # Admin page for new journal volumes
├── includes/
│   ├── db_connect.php            # Database connection file
│   ├── header.php                # Common header for UI
├── mathematical_forum_db.sql     # SQL script for database setup
└── README.md                     # Project documentation
```

---

## 📥 File Uploads

- Manuscripts are stored in `admin/uploads/submitted_manuscripts/`.
- Journal volume thumbnails are stored in `admin/uploads/volume_thumbnails/`.

⚠️ Ensure that the **uploads** directories have write permissions for file storage.

---

## 🔧 Troubleshooting

### Common Errors:
- **File Upload Error:** Check directory permissions.
- **Database Connection Error:** Verify database credentials.
- **File Size Exceeded:** Ensure files meet size limits or adjust limits in the script.

---

## 🏗️ Contributing

Contributions are welcome! If you have ideas for improvements or new features, feel free to fork the repository and submit a pull request.

### Steps to Contribute:
1. **Fork the repository**.
2. Create a new branch for your feature or bug fix.
3. **Commit your changes**.
4. Open a pull request with a description of your changes.

---


## 🌟 Acknowledgments

Special thanks to our professor, 
Dr. Rizwan Rehman 
and my friend, 
Kalyan Gupta for their support in developing this project.
