<?php
include 'config/db.php';
include 'includes/header.php';

if ($role != 'admin') { echo "Access Denied"; exit(); }

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM students WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: manage_students.php");
    exit();
}

// View single student
$view = null;
if (isset($_GET['view'])) {
    $id = intval($_GET['view']);
    $stmt = $conn->prepare("SELECT * FROM students WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $view = $stmt->get_result()->fetch_assoc();
}

$students = $conn->query("SELECT * FROM students ORDER BY id DESC");
?>

<h3>Manage Students</h3>
<a href="add_student.php" class="btn btn-success mb-3"><i class="bi bi-plus-circle"></i> Add Student</a>

<?php if ($view): ?>
<div class="card shadow-sm mb-3">
    <div class="card-header bg-info text-white">Student Details</div>
    <div class="card-body">
        <p><b>Roll No:</b> <?php echo htmlspecialchars($view['roll_no']); ?></p>
        <p><b>Name:</b> <?php echo htmlspecialchars($view['name']); ?></p>
        <p><b>Email:</b> <?php echo htmlspecialchars($view['email']); ?></p>
        <p><b>Phone:</b> <?php echo htmlspecialchars($view['phone']); ?></p>
        <p><b>Course:</b> <?php echo htmlspecialchars($view['course']); ?> (<?php echo $view['year']; ?> Year)</p>
        <p><b>Address:</b> <?php echo htmlspecialchars($view['address']); ?></p>
        <a href="manage_students.php" class="btn btn-sm btn-secondary">Close</a>
    </div>
</div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>#</th><th>Roll No</th><th>Name</th><th>Course</th><th>Year</th><th>Phone</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=1; while($s = $students->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars($s['roll_no']); ?></td>
                    <td><?php echo htmlspecialchars($s['name']); ?></td>
                    <td><?php echo htmlspecialchars($s['course']); ?></td>
                    <td><?php echo htmlspecialchars($s['year']); ?></td>
                    <td><?php echo htmlspecialchars($s['phone']); ?></td>
                    <td>
                        <a href="?view=<?php echo $s['id']; ?>" class="btn btn-sm btn-info">View</a>
                        <a href="add_student.php?edit=<?php echo $s['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="javascript:void(0)" onclick="confirmDelete('?delete=<?php echo $s['id']; ?>')" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
