<?php
// Common header file - included on every page after login
session_start();

// Check if user is logged in, otherwise redirect
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>E-Campus Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- Top Navbar -->
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand mb-0 h1"><i class="bi bi-mortarboard-fill"></i> E-Campus Management System</span>
    <div class="d-flex align-items-center text-white">
        <span class="me-3"><i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($username); ?> (<?php echo ucfirst($role); ?>)</span>
        <a href="logout.php" class="btn btn-sm btn-outline-light">Logout</a>
    </div>
</nav>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar bg-light p-3">
        <h6 class="text-muted">MENU</h6>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <?php if ($role == 'admin'): ?>
            <li class="nav-item"><a class="nav-link" href="manage_students.php"><i class="bi bi-people"></i> Students</a></li>
            <li class="nav-item"><a class="nav-link" href="add_student.php"><i class="bi bi-person-plus"></i> Add Student</a></li>
            <li class="nav-item"><a class="nav-link" href="add_faculty.php"><i class="bi bi-person-badge"></i> Faculty</a></li>
            <li class="nav-item"><a class="nav-link" href="attendance.php"><i class="bi bi-calendar-check"></i> Attendance</a></li>
            <li class="nav-item"><a class="nav-link" href="fees.php"><i class="bi bi-cash-stack"></i> Fees</a></li>
            <li class="nav-item"><a class="nav-link" href="marks.php"><i class="bi bi-journal-text"></i> Marks</a></li>
            <?php endif; ?>
            <li class="nav-item"><a class="nav-link" href="notices.php"><i class="bi bi-megaphone"></i> Notices</a></li>
        </ul>
    </div>

    <!-- Main Content Area -->
    <div class="content p-4 flex-grow-1">
