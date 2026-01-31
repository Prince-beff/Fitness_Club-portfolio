<?php
require '../includes/auth.php';
require '../config/db.php';
include '../includes/header.php';

if($_SESSION['user']['role']!='manager') die("Access denied");

if($_POST){
    $pdo->prepare(
      "INSERT INTO attendance(user_id,date,status) VALUES(?,?,?)"
    )->execute([
        $_POST['user'],
        date('Y-m-d'),
        $_POST['status']
    ]);
}

$users=$pdo->query("SELECT * FROM users WHERE role='member'")->fetchAll();
$records=$pdo->query("SELECT * FROM attendance")->fetchAll();
?>
<h2>Attendance</h2>

<form method="post">
<select name="user" onchange="checkMembership(this.value)">
<?php foreach($users as $u): ?>
<option value="<?=$u['id']?>"><?=$u['name']?></option>
<?php endforeach ?>
</select>

<p id="status"></p>
<button>Save</button>
</form>

<ul>
<?php foreach($records as $r): ?>
<li>User <?=$r['user_id']?> - <?=$r['status']?></li>
<?php endforeach ?>
</ul>

<?php include '../includes/footer.php'; ?>
