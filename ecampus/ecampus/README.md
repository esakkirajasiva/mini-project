# E-Campus Management System

A simple final-year mini project built with **PHP, MySQL, HTML5, CSS3, Bootstrap 5, JavaScript, and AJAX**.

## Features
- Admin & Student Login (Session based)
- Dashboard with statistics
- Student Management (Add / Edit / Delete / View)
- Faculty Management
- Attendance Management (with AJAX auto-save)
- Fees Management (in Indian Rupees ₹)
- Marks / Results Management
- Notice Board

## Setup Instructions (XAMPP)

1. **Install XAMPP** (https://www.apachefriends.org)
2. **Copy** the `ecampus` folder into:
   ```
   C:\xampp\htdocs\ecampus
   ```
3. **Start Apache & MySQL** from XAMPP Control Panel.
4. Open browser → `http://localhost/phpmyadmin`
5. Click **Import** → select `database.sql` from the project folder → Go.
6. Open browser → `http://localhost/ecampus`

## Default Login

| Role    | Username | Password    |
|---------|----------|-------------|
| Admin   | admin    | admin123    |
| Student | rahul    | student123  |
| Student | priya    | student123  |

## Database
- Name: `ecampus_db`
- Host: `localhost`
- User: `root`
- Password: *(empty)*

## Folder Structure
```
ecampus/
├── assets/      (css, js, images)
├── config/      (db.php)
├── includes/    (header, footer)
├── uploads/
├── *.php        (main pages)
└── database.sql
```

## Notes
- Use prepared statements throughout to prevent SQL injection.
- Sessions are used for authentication.
- Sample dummy data is included.
- All amounts are shown in Indian Rupees (₹).
