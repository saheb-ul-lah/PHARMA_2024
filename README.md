---

# **PHARMA_2024: Pharmaceutical Manuscript Submission & Review System**

PHARMA_2024 is a user-friendly, web-based platform designed to simplify the process of submitting, managing, and reviewing pharmaceutical research papers. It streamlines interactions between researchers and journal editors, allowing easy submission of manuscripts and efficient management of journal volumes.

---

## 🚀 **Features**

- **Manuscript Submission System**: Researchers can upload manuscripts (PDF, DOC, DOCX) with proper validation for size and format.
- **Admin Dashboard**: Journal editors can view, review, and manage all submissions in a dedicated admin panel.
- **Volume Management**: Automatic journal volume creation, complete with thumbnail image uploads.
- **Security**: Basic authentication for accessing the admin panel and secure data handling.
- **Error Handling & Validation**: Comprehensive file validation with error messages for file size and format.
- **Responsive Design**: Fully responsive interface for seamless use on desktop and mobile devices.

---

## 💻 **Technology Stack**

- **Frontend**: 
  - HTML5, CSS3, JavaScript
  - Bootstrap 4 (for responsiveness and styling)

- **Backend**: 
  - PHP 7.x
  - MySQL (for storing manuscript and journal data)
  - PDO (for secure database interaction)

- **Development Tools**: 
  - XAMPP (Local server environment)
  - Git (Version control)

---

## 🛠️ **Installation Guide**

### **Prerequisites**
1. **XAMPP** (or an equivalent local server with PHP and MySQL support)
2. **PHP** (7.x or higher)
3. **MySQL** (or MariaDB)

### **Step-by-Step Installation**

1. **Clone the Repository**
   ```bash
   git clone https://github.com/saheb-ul-lah/PHARMA_2024.git
   ```

2. **Set Up the MySQL Database**
   - Launch PHPMyAdmin (or use any MySQL client).
   - Create a new database named `journal_db`.
   - Run the SQL commands found in `pharma2024_db.sql` file to set up the necessary tables:
     ```sql
     CREATE TABLE `submissions` (
       `id` INT AUTO_INCREMENT PRIMARY KEY,
       `name` VARCHAR(255) NOT NULL,
       `email` VARCHAR(255) NOT NULL,
       `address` TEXT NOT NULL,
       `phone` VARCHAR(20) NOT NULL,
       `title` VARCHAR(255) NOT NULL,
       `description` TEXT NOT NULL,
       `file` VARCHAR(255) NOT NULL
     );
     ```

3. **Configure Database Credentials**
   - Update your PHP files (`submit_paper.php`, `add_journal.php`, etc.) with the correct database credentials:
     ```php
     $host = 'localhost';
     $db = 'journal_db';
     $user = 'root';  // Your MySQL username
     $pass = '';      // Your MySQL password
     ```

4. **Ensure Correct Folder Permissions**
   - Make sure the folders for file uploads (`/admin/uploads/submitted_manuscripts/` and `/admin/uploads/volume_thumbnails/`) have write permissions.

5. **Run the Project**
   - Move the `PHARMA_2024` folder to the `htdocs` directory of XAMPP.
   - Open XAMPP Control Panel and start **Apache** and **MySQL**.
   - Visit the project by navigating to `http://localhost/PHARMA_2024/client/My_HTML/` in your browser.

---

## 🎯 **How to Use**

### **1. Manuscript Submission (Researcher Side)**
- Go to the **Submit Paper** page.
- Fill out the form with your name, email, address, phone number, and manuscript details (title and description).
- Upload your manuscript file in PDF, DOC, or DOCX format.
- Submit the form and wait for a success confirmation.

### **2. Admin Dashboard (Journal Editor Side)**
- Log in to the admin panel using your credentials.
- Access the submissions list to view all submitted manuscripts.
- Review manuscripts, and add new journal volumes with cover thumbnails.
- Manage journal data (e.g., journal name, ISSN, editor information).

---

## 📂 **Project Structure**

```plaintext
PHARMA_2024/
├── client/
│   ├── My_HTML/                  # HTML and frontend files
│   ├── submit_paper.php          # Manuscript submission backend logic
├── admin/
│   ├── uploads/
│   │   ├── submitted_manuscripts/ # Directory for uploaded research papers
│   │   └── volume_thumbnails/     # Directory for journal volume thumbnails
│   ├── add_journal.php           # Admin page for adding new journal volumes
├── includes/
│   ├── db_connect.php            # Database connection file
│   ├── header.php                # Common header file for UI
├── pharma2024_db.sql             # SQL script to set up the MySQL database
└── README.md                     # Project documentation
```

---

## 📥 **File Uploads**

- **Manuscript Submission**: Uploaded files are stored in `admin/uploads/submitted_manuscripts/`.
- **Journal Volume Thumbnails**: Thumbnails for journal volumes are stored in `admin/uploads/volume_thumbnails/`.
  
⚠️ Ensure the **uploads** directories have write permissions, so the server can store uploaded files.

---

## 🔧 **Troubleshooting**

### Common Errors:
- **File upload error**: 
  - Ensure that the destination directories (`submitted_manuscripts` and `volume_thumbnails`) exist and have proper permissions.
  
- **Database connection error**: 
  - Double-check your database credentials (`host`, `user`, `pass`, and `db`) in the PHP files.

- **File size exceeded**: 
  - Ensure your uploaded file is below the 5MB size limit, or adjust the size limit in the script.

---

## 🏗️ **Contributing**

Contributions are welcome! If you have ideas for improvements or new features, feel free to fork the repository and submit a pull request.

### Steps to Contribute:
1. **Fork the repository**.
2. Create a new branch for your feature or bug fix.
3. **Commit your changes**.
4. Open a pull request with a description of your changes.

---

## 📝 **License**

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more information.

---

## 🌟 **Acknowledgments**

Special thanks to all contributors and developers who have worked on this project. Also, appreciation to the open-source community for providing the tools and frameworks used in this project.

---
