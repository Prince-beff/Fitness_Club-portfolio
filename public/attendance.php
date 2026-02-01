<?php
require '../includes/auth.php';
require '../config/db.php';
include '../includes/header.php';

if($_SESSION['user']['role']!='manager') die("Access denied");

// Members dropdown
$users = $pdo->query("SELECT id, name FROM users WHERE role='member' ORDER BY name")->fetchAll();

// SAVE attendance
$message = "";

if(isset($_POST['submit'])){
    $user_id = $_POST['user'];
    $status  = $_POST['status'];
    $date    = $_POST['date'];

    // prevent duplicates (same member same day)
    $check = $pdo->prepare("SELECT COUNT(*) FROM attendance WHERE user_id=? AND date=?");
    $check->execute([$user_id, $date]);

    if($check->fetchColumn() > 0){
        $message = "<p style='color:red;font-weight:bold;'>Attendance already marked for this member on this date.</p>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO attendance (user_id, date, status) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $date, $status]);
        $message = "<p style='color:green;font-weight:bold;'>Attendance saved successfully.</p>";
    }
}

// Attendance records with member names
$records = $pdo->query("
  SELECT a.date, a.status, u.name
  FROM attendance a
  JOIN users u ON a.user_id = u.id
  WHERE u.role='member'
  ORDER BY a.date DESC
")->fetchAll();
?>

<div class="card">
  <h2>Mark Attendance</h2>

  <?= $message ?>

  <form method="post">
    <label>Select Member</label>
    <select name="user" onchange="checkMembership(this.value)" required>
      <option value="">-- Select Member --</option>
      <?php foreach($users as $u): ?>
        <option value="<?=$u['id']?>"><?=htmlspecialchars($u['name'])?></option>
      <?php endforeach; ?>
    </select>

    <p id="status" style="margin:6px 0; font-weight:bold;"></p>

    <label>Date</label>
    <input type="date" name="date" value="<?=date('Y-m-d')?>" required>

    <label>Status</label>
    <select name="status" required>
      <option value="Present">Present</option>
      <option value="Absent">Absent</option>
    </select>

    <button type="submit" name="submit">Save Attendance</button>
  </form>
</div>

<div class="card">
  <h2>Attendance Records</h2>
  <table>
    <tr>
      <th>Member Name</th>
      <th>Date</th>
      <th>Status</th>
    </tr>

    <?php if(count($records)==0): ?>
      <tr><td colspan="3">No attendance records yet.</td></tr>
    <?php endif; ?>

    <?php foreach($records as $r): ?>
    <tr>
      <td><?= htmlspecialchars($r['name']) ?></td>
      <td><?= htmlspecialchars($r['date']) ?></td>
      <td><?= htmlspecialchars($r['status']) ?></td>
    </tr>
    <?php endforeach; ?>
  </table>
</div>

<script src="../assets/js/app.js"></script>
<?php include '../includes/footer.php'; ?>
