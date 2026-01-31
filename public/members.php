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
        'member'
    ]);
    echo "<p>Member login created successfully</p>";
}

$members=$pdo->query("SELECT * FROM users WHERE role='member'")->fetchAll();
?>

<h2>Create Member Login</h2>
<form method="post">
<input name="name" placeholder="Full Name" required>
<input type="email" name="email" placeholder="Email (Login ID)" required>
<input type="password" name="password" placeholder="Password" required>
<button name="add">Create Member</button>
</form>

<h3>Existing Members</h3>
<table border="1">
<tr><th>Name</th><th>Email</th></tr>
<?php foreach($members as $m): ?>
<tr>
<td><?=htmlspecialchars($m['name'])?></td>
<td><?=htmlspecialchars($m['email'])?></td>
</tr>
<?php endforeach ?>
</table>

<?php include '../includes/footer.php'; ?>
