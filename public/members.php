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

$members = $pdo->query("
  SELECT u.name, u.email, m.type, m.expiry_date
  FROM users u
  LEFT JOIN memberships m ON u.id = m.user_id
  WHERE u.role='member'
")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
</head>
<body>
    <div class="card">
      <h2>Create Member Login</h2>

      <form method="post">
        <input name="name" placeholder="Full Name">
        <input type="email" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Password">
        <button>Create Member</button>
      </form>
</div>


<div class="card">
<h2>Existing Members</h2>

<table>
<tr>
  <th>Name</th>
  <th>Email</th>
  <th>Membership</th>
  <th>Expiry</th>
</tr>

<?php foreach($members as $m): ?>
<tr>
  <td><?=htmlspecialchars($m['name'])?></td>
  <td><?=htmlspecialchars($m['email'])?></td>
  <td><?= $m['type'] ?? 'Not Assigned' ?></td>
  <td><?= $m['expiry_date'] ?? '-' ?></td>
</tr>
<?php endforeach; ?>
</table>
</div>

</body>
</html>

<?php include '../includes/footer.php'; ?>
