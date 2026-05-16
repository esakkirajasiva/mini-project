<?php
include 'config/db.php';
include 'includes/header.php';

if ($role != 'admin') { echo "Access Denied"; exit(); }

$msg = "";

if ($_SERVER['REQUEST_METHOD']=='POST') {
    $sid = intval($_POST['student_id']);
    $sub = trim($_POST['subject']);
    $obt = intval($_POST['marks_obtained']);
    $tot = intval($_POST['total_marks']);

    if ($obt > $tot) {
        $msg = "<div class='alert alert-danger'>Marks obtained cannot be greater than total!</div>";
    } else {
        $stmt = $conn->prepare("INSERT INTO marks (student_id,subject,marks_obtained,total_marks) VALUES (?,?,?,?)");
        $stmt->bind_param("isii", $sid, $sub, $obt, $tot);
        $stmt->execute();
        $msg = "<div class='alert alert-success'>Marks added!</div>";
    }
}

$students = $conn->query("SELECT * FROM students");
$marks = $conn->query("SELECT m.*, s.roll_no, s.name FROM marks m JOIN students s ON m.student_id=s.id ORDER BY m.id DESC");
?>

<h3>Marks / Results Management</h3>
<?php echo $msg; ?>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">Add Marks</div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-2"><label>Student</label>
                        <select name="student_id" class="form-select" required>
                            <option value="">-- Select --</option>
                            <?php while($s=$students->fetch_assoc()): ?>
                            <option value="<?php echo $s['id']; ?>"><?php echo $s['roll_no'].' - '.$s['name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-2"><label>Subject</label><input type="text" name="subject" class="form-control" required></div>
                    <div class="mb-2"><label>Marks Obtained</label><input type="number" name="marks_obtained" class="form-control" required></div>
                    <div class="mb-2"><label>Total Marks</label><input type="number" name="total_marks" class="form-control" value="100" required></div>
                    <button class="btn btn-primary w-100">Save</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">Results</div>
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead><tr><th>Roll</th><th>Student</th><th>Subject</th><th>Marks</th><th>%</th><th>Grade</th></tr></thead>
                    <tbody>
                        <?php while($m=$marks->fetch_assoc()):
                            $pct = round(($m['marks_obtained']/$m['total_marks'])*100,1);
                            if($pct>=75) $g='A'; elseif($pct>=60) $g='B'; elseif($pct>=40) $g='C'; else $g='F';
                        ?>
                        <tr>
                            <td><?php echo $m['roll_no']; ?></td>
                            <td><?php echo htmlspecialchars($m['name']); ?></td>
                            <td><?php echo htmlspecialchars($m['subject']); ?></td>
                            <td><?php echo $m['marks_obtained'].'/'.$m['total_marks']; ?></td>
                            <td><?php echo $pct; ?>%</td>
                            <td><span class="badge bg-<?php echo $g=='A'?'success':($g=='F'?'danger':'primary'); ?>"><?php echo $g; ?></span></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
