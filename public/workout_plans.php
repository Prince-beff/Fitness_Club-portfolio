<?php
require '../includes/auth.php';
require '../config/db.php';
include '../includes/header.php';

if($_SESSION['user']['role']!='trainer') die("Access denied");

if($_POST){
    $pdo->prepare(
      "INSERT INTO workout_plans(trainer_id,member_id,plan)
       VALUES(?,?,?)"
    )->execute([
        $_SESSION['user']['id'],
        $_POST['member'],
        $_POST['plan']
    ]);
}

$members=$pdo->query("SELECT * FROM users WHERE role='member'")->fetchAll();
$plans=$pdo->query("SELECT * FROM workout_plans")->fetchAll();
?>
<div class="card">
<h2>Workout Plans</h2>

<form method="post">
<select name="member">
<?php foreach($members as $m): ?>
<option value="<?=$m['id']?>"><?=$m['name']?></option>
<?php endforeach ?>
</select><br>
<textarea name="plan" required></textarea><br>
<button>Add</button>
</form>
</div>

<ul>
<?php foreach($plans as $p): ?>
<li><?=htmlspecialchars($p['plan'])?></li>
<?php endforeach ?>
</ul>

<?php include '../includes/footer.php'; ?>
