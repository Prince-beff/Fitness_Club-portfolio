<!DOCTYPE html>
<html>
<head>
<title>Fitness Club Management</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="navbar">
  <div class="logo">Fitness Club Management</div>
  <div class="nav-links">
    <a href="dashboard.php">Dashboard</a>

    <?php if($_SESSION['user']['role']=='manager'): ?>
      <a href="members.php">Members</a>
      <a href="trainers.php">Trainers</a>
      <a href="memberships.php">Memberships</a>
      <a href="attendance.php">Attendance</a>
    <?php endif; ?>

    <?php if($_SESSION['user']['role']=='trainer'): ?>
      <a href="workout_plans.php">Workout Plans</a>
    <?php endif; ?>

    <a href="logout.php" class="logout">Logout</a>
  </div>
</div>

<div class="container">
