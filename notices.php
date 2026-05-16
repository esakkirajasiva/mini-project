<?php
include 'config/db.php';
include 'includes/header.php';

$msg = "";

// Only admin can post / delete
if ($role == 'admin') {
    if (isset($_GET['delete'])) {
        $id = intval($_GET['delete']);
        $stmt = $conn->prepare("DELETE FROM notices WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: notices.php"); exit();
    }
    if ($_SERVER['REQUEST_METHOD']=='POST') {
        $title = trim($_POST['title']);
        $desc = trim($_POST['description']);
        if (!empty($title)) {
            $stmt = $conn->prepare("INSERT INTO notices (title,description) VALUES (?,?)");
            $stmt->bind_param("ss", $title, $desc);
            $stmt->execute();
            $msg = "<div class='alert alert-success'>Notice posted!</div>";
        }
    }
}

$notices = $conn->query("SELECT * FROM notices ORDER BY id DESC");
?>

<h3>Notice Board</h3>
<?php echo $msg; ?>

<?php if ($role == 'admin'): ?>
<div class="card shadow-sm mb-3">
    <div class="card-header bg-primary text-white">Post New Notice</div>
    <div class="card-body">
        <form method="POST">
            <div class="mb-2"><label>Title *</label><input type="text" name="title" class="form-control" required></div>
            <div class="mb-2"><label>Description</label><textarea name="description" class="form-control" rows="3"></textarea></div>
            <button class="btn btn-primary">Post Notice</button>
        </form>
    </div>
</div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header bg-success text-white">All Notices</div>
    <ul class="list-group list-group-flush">
        <?php while($n = $notices->fetch_assoc()): ?>
        <li class="list-group-item">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="mb-1"><i class="bi bi-megaphone-fill text-primary"></i> <?php echo htmlspecialchars($n['title']); ?></h6>
                    <p class="mb-1"><?php echo nl2br(htmlspecialchars($n['description'])); ?></p>
                    <small class="text-muted">Posted on: <?php echo $n['posted_on']; ?></small>
                </div>
                <?php if ($role=='admin'): ?>
                <a href="javascript:void(0)" onclick="confirmDelete('?delete=<?php echo $n['id']; ?>')" class="btn btn-sm btn-danger" style="height:fit-content;">Delete</a>
                <?php endif; ?>
            </div>
        </li>
        <?php endwhile; ?>
    </ul>
</div>

<?php include 'includes/footer.php'; ?>
