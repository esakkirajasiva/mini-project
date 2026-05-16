-- E-Campus Management System Database
-- Import this file in phpMyAdmin to create the database

CREATE DATABASE IF NOT EXISTS ecampus_db;
USE ecampus_db;

-- Users table (Admin & Student login)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','student') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Students table
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roll_no VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(15),
    course VARCHAR(50),
    year VARCHAR(10),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Faculty table
CREATE TABLE faculty (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(15),
    department VARCHAR(50),
    designation VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Attendance table
CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    att_date DATE NOT NULL,
    status ENUM('Present','Absent') NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Fees table
CREATE TABLE fees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    paid_amount DECIMAL(10,2) DEFAULT 0,
    status ENUM('Paid','Pending','Partial') DEFAULT 'Pending',
    pay_date DATE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Marks table
CREATE TABLE marks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject VARCHAR(50) NOT NULL,
    marks_obtained INT NOT NULL,
    total_marks INT NOT NULL DEFAULT 100,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Notices table
CREATE TABLE notices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    posted_on TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ====== DUMMY DATA ======

-- Default Admin (username: admin, password: admin123)
INSERT INTO users (username, password, role) VALUES
('admin', 'admin123', 'admin'),
('rahul', 'student123', 'student'),
('priya', 'student123', 'student');

-- Sample Students
INSERT INTO students (roll_no, name, email, phone, course, year, address) VALUES
('CS101', 'Rahul Sharma', 'rahul@gmail.com', '9876543210', 'BCA', '2nd', 'Delhi'),
('CS102', 'Priya Verma', 'priya@gmail.com', '9876543211', 'BCA', '2nd', 'Mumbai'),
('CS103', 'Amit Kumar', 'amit@gmail.com', '9876543212', 'BSc IT', '1st', 'Pune'),
('CS104', 'Sneha Patil', 'sneha@gmail.com', '9876543213', 'MCA', '1st', 'Bangalore'),
('CS105', 'Karan Singh', 'karan@gmail.com', '9876543214', 'BCA', '3rd', 'Jaipur');

-- Sample Faculty
INSERT INTO faculty (name, email, phone, department, designation) VALUES
('Dr. Ramesh Gupta', 'ramesh@college.edu', '9123456780', 'Computer Science', 'HOD'),
('Prof. Anjali Mehta', 'anjali@college.edu', '9123456781', 'Information Tech', 'Asst. Professor'),
('Dr. Suresh Nair', 'suresh@college.edu', '9123456782', 'Mathematics', 'Professor');

-- Sample Attendance
INSERT INTO attendance (student_id, att_date, status) VALUES
(1, '2025-05-01', 'Present'),
(1, '2025-05-02', 'Present'),
(1, '2025-05-03', 'Absent'),
(2, '2025-05-01', 'Present'),
(2, '2025-05-02', 'Present'),
(3, '2025-05-01', 'Absent'),
(3, '2025-05-02', 'Present'),
(4, '2025-05-01', 'Present'),
(5, '2025-05-01', 'Present');

-- Sample Fees
INSERT INTO fees (student_id, amount, paid_amount, status, pay_date) VALUES
(1, 50000.00, 50000.00, 'Paid', '2025-04-10'),
(2, 50000.00, 25000.00, 'Partial', '2025-04-12'),
(3, 45000.00, 0.00, 'Pending', NULL),
(4, 60000.00, 60000.00, 'Paid', '2025-04-15'),
(5, 50000.00, 0.00, 'Pending', NULL);

-- Sample Marks
INSERT INTO marks (student_id, subject, marks_obtained, total_marks) VALUES
(1, 'Mathematics', 85, 100),
(1, 'Programming in C', 78, 100),
(1, 'DBMS', 92, 100),
(2, 'Mathematics', 72, 100),
(2, 'Programming in C', 88, 100),
(3, 'Mathematics', 65, 100),
(4, 'Operating System', 90, 100);

-- Sample Notices
INSERT INTO notices (title, description) VALUES
('Mid Semester Exam', 'Mid semester exams will start from 15th May 2025. Prepare well!'),
('Holiday Notice', 'College will remain closed on 20th May 2025 due to public holiday.'),
('Sports Day', 'Annual sports day will be celebrated on 25th May 2025 at college ground.');
