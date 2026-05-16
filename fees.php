<?php
include 'config/db.php';
include 'includes/header.php';

if ($role != 'admin') { echo "Access Denied"; exit(); }

$msg = "";

// Add fee record
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sid = intval($_POST['student_id']);
    $amt = floatval($_POST['amount']);
    $paid = floatval($_POST['paid_amount']);
    $status = ($paid >= $amt) ? 'Paid' : ($paid > 0 ? 'Partial' : 'Pending');
    $date = $paid > 0 ? date('Y-m-d') : NULL;

    $stmt = $conn->prepare("INSERT INTO fees (student_id, amount, paid_amount, status, pay_date) VALUES (?,?,?,?,?)");
    $stmt->bind_param("iddss", $sid, $amt, $paid, $status, $date);
    $stmt->execute();
    $msg = "<div class='alert alert-success'>Fee record saved!</div>";
}

$students = $conn->query("SELECT * FROM students");
$fees = $conn->query("SELECT f.*, s.roll_no, s.name FROM fees f JOIN students s ON f.student_id=s.id ORDER BY f.id DESC");
?>

<h3>Fees Management</h3>
<?php echo $msg; ?>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">Add Fee Record</div>
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
                    <div class="mb-2"><label>Total Amount (₹)</label><input type="number" name="amount" class="form-control" step="0.01" required></div>
                    <div class="mb-2"><label>Paid Amount (₹)</label><input type="number" name="paid_amount" class="form-control" step="0.01" value="0" required></div>
                    <button class="btn btn-primary w-100">Save</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">Fees Records</div>
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead><tr><th>Roll</th><th>Name</th><th>Total</th><th>Paid</th><th>Pending</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                        <?php while($f = $fees->fetch_assoc()):
                            $pending = $f['amount'] - $f['paid_amount'];
                            $cls = $f['status']=='Paid'?'success':($f['status']=='Partial'?'warning':'danger');
                        ?>
                        <tr>
                            <td><?php echo $f['roll_no']; ?></td>
                            <td><?php echo htmlspecialchars($f['name']); ?></td>
                            <td>₹<?php echo number_format($f['amount'],0); ?></td>
                            <td>₹<?php echo number_format($f['paid_amount'],0); ?></td>
                            <td>₹<?php echo number_format($pending,0); ?></td>
                            <td><span class="badge bg-<?php echo $cls; ?>"><?php echo $f['status']; ?></span></td>
                            <td><?php echo $f['pay_date'] ?? '-'; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
