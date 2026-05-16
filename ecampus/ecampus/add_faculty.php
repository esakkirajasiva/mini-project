<?php
include 'config/db.php';
include 'includes/header.php';

if ($role != 'admin') { echo "Access Denied"; exit(); }

$msg = "";

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM faculty WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: add_faculty.php");
    exit();
}

// Add new faculty
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $dept = $_POST['department'];
    $desg = $_POST['designation'];

    if (empty($name)) {
        $msg = "<div class='alert alert-danger'>Name is required.</div>";
    } else {
        $stmt = $conn->prepare("INSERT INTO faculty (name,email,phone,department,designation) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssss", $name, $email, $phone, $dept, $desg);
        $stmt->execute();
        $msg = "<div class='alert alert-success'>Faculty added successfully!</div>";
    }
}

$faculty = $conn->query("SELECT * FROM faculty ORDER BY id DESC");
?>

<h3>Faculty Management</h3>
<?php echo $msg; ?>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">Add Faculty</div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-2"><label>Name *</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-2"><label>Email</label><input type="email" name="email" class="form-control"></div>
                    <div class="mb-2"><label>Phone</label><input type="text" name="phone" class="form-control"></div>
                    <div class="mb-2"><label>Department</label>
                        <select name="department" class="form-select">
                            <option>Computer Science</option>
                            <option>Information Tech</option>
                            <option>Mathematics</option>
                            <option>Physics</option>
                            <option>English</option>
                        </select>
                    </div>
                    <div class="mb-2"><label>Designation</label>
                        <select name="designation" class="form-select">
                            <option>HOD</option>
                            <option>Professor</option>
                            <option>Asst. Professor</option>
                            <option>Lecturer</option>
                        </select>
                    </div>
                    <button class="btn btn-primary w-100">Save Faculty</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">Faculty List</div>
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead><tr><th>#</th><th>Name</th><th>Department</th><th>Designation</th><th>Phone</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php $i=1; while($f = $faculty->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo htmlspecialchars($f['name']); ?></td>
                            <td><?php echo htmlspecialchars($f['department']); ?></td>
                            <td><?php echo htmlspecialchars($f['designation']); ?></td>
                            <td><?php echo htmlspecialchars($f['phone']); ?></td>
                            <td><a href="javascript:void(0)" onclick="confirmDelete('?delete=<?php echo $f['id']; ?>')" class="btn btn-sm btn-danger">Delete</a></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
