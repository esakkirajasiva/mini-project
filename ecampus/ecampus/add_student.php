<?php
include 'config/db.php';
include 'includes/header.php';

// Only admin can access
if ($role != 'admin') { echo "Access Denied"; exit(); }

$msg = "";
$edit = false;
$student = ['id'=>'','roll_no'=>'','name'=>'','email'=>'','phone'=>'','course'=>'','year'=>'','address'=>''];

// Edit mode
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM students WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();
    $edit = true;
}

// Save student (insert or update)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $roll = trim($_POST['roll_no']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $course = $_POST['course'];
    $year = $_POST['year'];
    $address = trim($_POST['address']);

    // Basic validation
    if (empty($roll) || empty($name)) {
        $msg = "<div class='alert alert-danger'>Roll No. and Name are required.</div>";
    } else {
        if ($id) {
            // Update
            $stmt = $conn->prepare("UPDATE students SET roll_no=?, name=?, email=?, phone=?, course=?, year=?, address=? WHERE id=?");
            $stmt->bind_param("sssssssi", $roll, $name, $email, $phone, $course, $year, $address, $id);
            $stmt->execute();
            $msg = "<div class='alert alert-success'>Student updated successfully!</div>";
        } else {
            // Insert
            $stmt = $conn->prepare("INSERT INTO students (roll_no, name, email, phone, course, year, address) VALUES (?,?,?,?,?,?,?)");
            $stmt->bind_param("sssssss", $roll, $name, $email, $phone, $course, $year, $address);
            if ($stmt->execute()) {
                $msg = "<div class='alert alert-success'>Student added successfully!</div>";
                $student = ['id'=>'','roll_no'=>'','name'=>'','email'=>'','phone'=>'','course'=>'','year'=>'','address'=>''];
            } else {
                $msg = "<div class='alert alert-danger'>Error: Roll No. may already exist.</div>";
            }
        }
    }
}
?>

<h3><?php echo $edit ? "Edit Student" : "Add New Student"; ?></h3>
<?php echo $msg; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
            <div class="row g-3">
                <div class="col-md-4">
                    <label>Roll No. *</label>
                    <input type="text" name="roll_no" class="form-control" value="<?php echo htmlspecialchars($student['roll_no']); ?>" required>
                </div>
                <div class="col-md-4">
                    <label>Name *</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($student['name']); ?>" required>
                </div>
                <div class="col-md-4">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($student['email']); ?>">
                </div>
                <div class="col-md-4">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($student['phone']); ?>">
                </div>
                <div class="col-md-4">
                    <label>Course</label>
                    <select name="course" class="form-select">
                        <?php foreach (['BCA','MCA','BSc IT','BTech','MTech'] as $c): ?>
                        <option <?php if($student['course']==$c) echo 'selected'; ?>><?php echo $c; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Year</label>
                    <select name="year" class="form-select">
                        <?php foreach (['1st','2nd','3rd','4th'] as $y): ?>
                        <option <?php if($student['year']==$y) echo 'selected'; ?>><?php echo $y; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <label>Address</label>
                    <textarea name="address" class="form-control" rows="2"><?php echo htmlspecialchars($student['address']); ?></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3"><?php echo $edit ? "Update" : "Save"; ?></button>
            <a href="manage_students.php" class="btn btn-secondary mt-3">Back</a>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
