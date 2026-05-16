<?php
session_start();
include 'config/db.php';

$error = "";

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];

    // Basic validation
    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=? AND role=?");
        $stmt->bind_param("sss", $username, $password, $role);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid username, password or role.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - E-Campus</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="background: linear-gradient(135deg, #0d6efd, #6610f2); min-height: 100vh;">

<div class="login-box">
    <div class="text-center mb-4">
        <i class="bi bi-mortarboard-fill" style="font-size:48px; color:#0d6efd;"></i>
        <h3>E-Campus Login</h3>
        <p class="text-muted">Management System</p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Login As</label>
            <select name="role" class="form-select" required>
                <option value="admin">Admin</option>
                <option value="student">Student</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <hr>
    <small class="text-muted">
        <b>Demo Login:</b><br>
        Admin: admin / admin123<br>
        Student: rahul / student123
    </small>
</div>

</body>
</html>
