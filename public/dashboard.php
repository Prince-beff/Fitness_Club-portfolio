<?php require '../includes/auth.php'; include '../includes/header.php'; ?>

<h3>Welcome <?=htmlspecialchars($_SESSION['user']['name'])?></h3>

<?php if($_SESSION['user']['role']=="manager"): ?>
<a href="members.php">Manage Members</a><br>
<a href="trainers.php">Manage Trainers</a><br>
<a href="attendance.php">Attendance</a><br>
<?php endif; ?>

<?php if($_SESSION['user']['role']=="trainer"): ?>
<a href="workout_plans.php">Workout Plans</a><br>
<?php endif; ?>

<a href="logout.php">Logout</a>
<?php if($_SESSION['user']['role']=="manager"): ?>
<a href="memberships.php">Manage Memberships</a><br>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
