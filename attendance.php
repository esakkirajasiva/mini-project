<?php
include 'config/db.php';

// AJAX handler - mark attendance
if (isset($_POST['ajax'])) {
    session_start();
    $sid = intval($_POST['sid']);
    $status = $_POST['status'];
    $date = $_POST['date'];

    // Check existing
    $stmt = $conn->prepare("SELECT id FROM attendance WHERE student_id=? AND att_date=?");
    $stmt->bind_param("is", $sid, $date);
    $stmt->execute();
    $r = $stmt->get_result();
    if ($r->num_rows > 0) {
        $row = $r->fetch_assoc();
        $u = $conn->prepare("UPDATE attendance SET status=? WHERE id=?");
        $u->bind_param("si", $status, $row['id']);
        $u->execute();
    } else {
        $i = $conn->prepare("INSERT INTO attendance (student_id,att_date,status) VALUES (?,?,?)");
        $i->bind_param("iss", $sid, $date, $status);
        $i->execute();
    }
    echo "OK";
    exit();
}

include 'includes/header.php';
if ($role != 'admin') { echo "Access Denied"; exit(); }

$date = $_GET['date'] ?? date('Y-m-d');
$students = $conn->query("SELECT * FROM students ORDER BY roll_no");
?>

<h3>Mark Attendance</h3>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label>Date</label>
                <input type="date" id="att_date" name="date" class="form-control" value="<?php echo $date; ?>">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary">Load</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">Students - <?php echo $date; ?> (Auto-saves via AJAX)</div>
    <table class="table table-striped mb-0">
        <thead><tr><th>Roll No</th><th>Name</th><th>Course</th><th>Status</th><th></th></tr></thead>
        <tbody>
            <?php while ($s = $students->fetch_assoc()):
                // Get current status
                $st = $conn->prepare("SELECT status FROM attendance WHERE student_id=? AND att_date=?");
                $st->bind_param("is", $s['id'], $date);
                $st->execute();
                $cur = $st->get_result()->fetch_assoc();
                $curStatus = $cur['status'] ?? '';
            ?>
            <tr>
                <td><?php echo $s['roll_no']; ?></td>
                <td><?php echo htmlspecialchars($s['name']); ?></td>
                <td><?php echo $s['course']; ?></td>
                <td>
                    <select class="form-select form-select-sm att-status" data-sid="<?php echo $s['id']; ?>">
                        <option value="">-- Select --</option>
                        <option value="Present" <?php if($curStatus=='Present') echo 'selected'; ?>>Present</option>
                        <option value="Absent" <?php if($curStatus=='Absent') echo 'selected'; ?>>Absent</option>
                    </select>
                </td>
                <td><span id="msg-<?php echo $s['id']; ?>"></span></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<h4 class="mt-4">Attendance Report</h4>
<div class="card shadow-sm">
    <table class="table mb-0">
        <thead><tr><th>Roll No</th><th>Student</th><th>Total Days</th><th>Present</th><th>Absent</th><th>Percentage</th></tr></thead>
        <tbody>
            <?php
            $rep = $conn->query("SELECT s.roll_no, s.name,
                COUNT(a.id) as total,
                SUM(CASE WHEN a.status='Present' THEN 1 ELSE 0 END) as present,
                SUM(CASE WHEN a.status='Absent' THEN 1 ELSE 0 END) as absent
                FROM students s LEFT JOIN attendance a ON s.id=a.student_id GROUP BY s.id");
            while ($r = $rep->fetch_assoc()):
                $pct = $r['total'] > 0 ? round(($r['present']/$r['total'])*100, 1) : 0;
            ?>
            <tr>
                <td><?php echo $r['roll_no']; ?></td>
                <td><?php echo htmlspecialchars($r['name']); ?></td>
                <td><?php echo $r['total']; ?></td>
                <td><?php echo $r['present']; ?></td>
                <td><?php echo $r['absent']; ?></td>
                <td><span class="badge bg-<?php echo $pct>=75?'success':($pct>=50?'warning':'danger'); ?>"><?php echo $pct; ?>%</span></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
