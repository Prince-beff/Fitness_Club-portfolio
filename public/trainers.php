<?php
require '../includes/auth.php';
require '../config/db.php';
include '../includes/header.php';

if($_SESSION['user']['role']!='manager') die("Access denied");

if(isset($_POST['add'])){
    $stmt=$pdo->prepare(
      "INSERT INTO users(name,email,password,role)
       VALUES(?,?,?,?)"
    );
    $stmt->execute([
        $_POST['name'],
        $_POST['email'],
        password_hash($_POST['password'], PASSWORD_DEFAULT),
        'trainer'
    ]);
    echo "<p>Trainer login created successfully</p>";
}

$trainers=$pdo->query("SELECT * FROM users WHERE role='trainer'")->fetchAll();
?>
<div class="card">
    <h2>Create Trainer Login</h2>
    <form method="post">
        <input name="name" placeholder="Trainer Name" required>
        <input type="email" name="email" placeholder="Email (Login ID)" required>
        <input type="password" name="password" placeholder="Password" required>
        <button name="add">Create Trainer</button>
    </form>
</div>
<div class="card">
<h3>Existing Trainers</h3>
<table border="1">
    <tr><th>Name</th><th>Email</th></tr>
    <?php foreach($trainers as $t): ?>
    <tr>
    <td><?=htmlspecialchars($t['name'])?></td>
    <td><?=htmlspecialchars($t['email'])?></td>
    </tr>
    <?php endforeach ?>
</table>
</div>

<?php include '../includes/footer.php'; ?>
