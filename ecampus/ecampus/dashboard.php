<?php
include 'config/db.php';
include 'includes/header.php';

// Fetch dashboard statistics
$total_students = $conn->query("SELECT COUNT(*) as c FROM students")->fetch_assoc()['c'];
$total_faculty = $conn->query("SELECT COUNT(*) as c FROM faculty")->fetch_assoc()['c'];

// Calculate attendance percentage
$att = $conn->query("SELECT 
    SUM(CASE WHEN status='Present' THEN 1 ELSE 0 END) as p,
    COUNT(*) as t FROM attendance")->fetch_assoc();
$att_percent = ($att['t'] > 0) ? round(($att['p'] / $att['t']) * 100, 2) : 0;

// Total fees collected
$fees = $conn->query("SELECT SUM(paid_amount) as total FROM fees")->fetch_assoc();
$total_fees = $fees['total'] ?? 0;
?>

<h3 class="mb-4">Dashboard</h3>

<div class="row g-3">
    <div class="col-md-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Total Students</h6>
                <h2 class="text-primary"><?php echo $total_students; ?></h2>
                <i class="bi bi-people-fill text-primary" style="font-size:30px;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card shadow-sm" style="border-left-color:#198754;">
            <div class="card-body">
                <h6 class="text-muted">Total Faculty</h6>
                <h2 class="text-success"><?php echo $total_faculty; ?></h2>
                <i class="bi bi-person-badge-fill text-success" style="font-size:30px;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card shadow-sm" style="border-left-color:#ffc107;">
            <div class="card-body">
                <h6 class="text-muted">Attendance %</h6>
                <h2 class="text-warning"><?php echo $att_percent; ?>%</h2>
                <i class="bi bi-calendar-check-fill text-warning" style="font-size:30px;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card shadow-sm" style="border-left-color:#dc3545;">
            <div class="card-body">
                <h6 class="text-muted">Fees Collected</h6>
                <h2 class="text-danger">₹<?php echo number_format($total_fees, 0); ?></h2>
                <i class="bi bi-cash-stack text-danger" style="font-size:30px;"></i>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">Recent Notices</div>
            <ul class="list-group list-group-flush">
                <?php
                $notices = $conn->query("SELECT * FROM notices ORDER BY id DESC LIMIT 5");
                while ($n = $notices->fetch_assoc()):
                ?>
                <li class="list-group-item">
                    <b><?php echo htmlspecialchars($n['title']); ?></b><br>
                    <small class="text-muted"><?php echo $n['posted_on']; ?></small>
                </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">Quick Stats (Bar Chart)</div>
            <div class="card-body">
                <!-- Simple CSS bar chart -->
                <p>Students <div class="progress mb-2"><div class="progress-bar bg-primary" style="width: <?php echo min($total_students*10,100); ?>%"><?php echo $total_students; ?></div></div></p>
                <p>Faculty <div class="progress mb-2"><div class="progress-bar bg-success" style="width: <?php echo min($total_faculty*15,100); ?>%"><?php echo $total_faculty; ?></div></div></p>
                <p>Attendance <div class="progress mb-2"><div class="progress-bar bg-warning" style="width: <?php echo $att_percent; ?>%"><?php echo $att_percent; ?>%</div></div></p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
