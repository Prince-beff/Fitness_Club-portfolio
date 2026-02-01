<?php
require '../includes/auth.php';
require '../config/db.php';
include '../includes/header.php';

$user = $_SESSION['user'];
?>

<div class="card">
  <h2>Welcome, <?= htmlspecialchars($user['name']) ?></h2>
  <p>Role: <strong><?= htmlspecialchars(ucfirst($user['role'])) ?></strong></p>
</div>

<?php if($user['role'] == 'manager'): ?>

  <?php
    $totalMembers = $pdo->query("SELECT COUNT(*) FROM users WHERE role='member'")->fetchColumn();
    $totalTrainers = $pdo->query("SELECT COUNT(*) FROM users WHERE role='trainer'")->fetchColumn();
    $activeMemberships = $pdo->query("SELECT COUNT(*) FROM memberships WHERE expiry_date >= CURDATE()")->fetchColumn();
    $todayAttendance = $pdo->query("SELECT COUNT(*) FROM attendance WHERE date = CURDATE()")->fetchColumn();
  ?>

  <div class="card">
    <h2>Admin Overview</h2>

    <table>
      <tr><th>Total Members</th><td><?= $totalMembers ?></td></tr>
      <tr><th>Total Trainers</th><td><?= $totalTrainers ?></td></tr>
      <tr><th>Active Memberships</th><td><?= $activeMemberships ?></td></tr>
      <tr><th>Attendance Today</th><td><?= $todayAttendance ?></td></tr>
    </table>

    <p style="margin-top:15px;">
      Use the navigation bar to manage members, trainers, memberships and attendance.
    </p>
  </div>

<?php elseif($user['role'] == 'trainer'): ?>

  <?php
    $myPlans = $pdo->prepare("SELECT COUNT(*) FROM workout_plans WHERE trainer_id=?");
    $myPlans->execute([$user['id']]);
    $planCount = $myPlans->fetchColumn();
  ?>

  <div class="card">
    <h2>Trainer Panel</h2>
    <p>You can create workout plans for members.</p>

    <table>
      <tr><th>Your Workout Plans</th><td><?= $planCount ?></td></tr>
    </table>

    <p style="margin-top:15px;">
      Go to <strong>Workout Plans</strong> from the navigation bar to add or view plans.
    </p>
  </div>

<?php else: ?>

  <?php
    // Membership info
    $stmt = $pdo->prepare("SELECT type, start_date, expiry_date FROM memberships WHERE user_id=? ORDER BY id DESC LIMIT 1");
    $stmt->execute([$user['id']]);
    $membership = $stmt->fetch();

    // Latest workout plan
    $stmt2 = $pdo->prepare("
      SELECT wp.plan, u.name AS trainer_name
      FROM workout_plans wp
      JOIN users u ON wp.trainer_id = u.id
      WHERE wp.member_id=?
      ORDER BY wp.id DESC LIMIT 1
    ");
    $stmt2->execute([$user['id']]);
    $plan = $stmt2->fetch();
  ?>

  <div class="card">
    <h2>Member Dashboard</h2>

    <h3>Membership Status</h3>
    <?php if($membership): ?>
      <table>
        <tr><th>Type</th><td><?= htmlspecialchars($membership['type']) ?></td></tr>
        <tr><th>Start</th><td><?= htmlspecialchars($membership['start_date']) ?></td></tr>
        <tr><th>Expiry</th><td><?= htmlspecialchars($membership['expiry_date']) ?></td></tr>
      </table>
    <?php else: ?>
      <p><strong>No membership assigned yet.</strong> Please contact the manager.</p>
    <?php endif; ?>

    <h3 style="margin-top:20px;">Latest Workout Plan</h3>
    <?php if($plan): ?>
      <p><strong>Trainer:</strong> <?= htmlspecialchars($plan['trainer_name']) ?></p>
      <div style="background:#f7f7f7; padding:12px; border-radius:4px;">
        <?= nl2br(htmlspecialchars($plan['plan'])) ?>
      </div>
    <?php else: ?>
      <p><strong>No workout plan assigned yet.</strong></p>
    <?php endif; ?>
  </div>

<?php endif; ?>

<?php include '../includes/footer.php'; ?>
