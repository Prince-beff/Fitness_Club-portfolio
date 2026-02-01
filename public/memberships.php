<?php
require '../includes/auth.php';
require '../config/db.php';
include '../includes/header.php';

if($_SESSION['user']['role']!='manager') die("Access denied");

/* ADD / UPDATE */
if(isset($_POST['save'])){
    if(empty($_POST['id'])){
        $stmt=$pdo->prepare(
          "INSERT INTO memberships(user_id,type,start_date,expiry_date)
           VALUES(?,?,?,?)"
        );
        $stmt->execute([
            $_POST['user'],
            $_POST['type'],
            $_POST['start'],
            $_POST['expiry']
        ]);
    } else {
        $stmt=$pdo->prepare(
          "UPDATE memberships SET type=?, start_date=?, expiry_date=? WHERE id=?"
        );
        $stmt->execute([
            $_POST['type'],
            $_POST['start'],
            $_POST['expiry'],
            $_POST['id']
        ]);
    }
}

/* DELETE */
if(isset($_GET['delete'])){
    $pdo->prepare("DELETE FROM memberships WHERE id=?")->execute([$_GET['delete']]);
}

/* EDIT */
$edit=null;
if(isset($_GET['edit'])){
    $stmt=$pdo->prepare("SELECT * FROM memberships WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $edit=$stmt->fetch();
}

$users=$pdo->query("SELECT * FROM users WHERE role='member'")->fetchAll();
$memberships=$pdo->query(
 "SELECT m.*, u.name FROM memberships m 
  JOIN users u ON m.user_id=u.id"
)->fetchAll();
?>
<div class="card">
    <h2>Manage Memberships</h2>

    <form method="post">
        <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">

        <select name="user" required>
        <?php foreach($users as $u): ?>
        <option value="<?=$u['id']?>" 
        <?=isset($edit)&&$edit['user_id']==$u['id']?'selected':''?>>
        <?=$u['name']?>
        </option>
        <?php endforeach ?>
        </select>

        <select name="type">
        <option <?=($edit['type']??'')=='Monthly'?'selected':''?>>Monthly</option>
        <option <?=($edit['type']??'')=='Quarterly'?'selected':''?>>Quarterly</option>
        <option <?=($edit['type']??'')=='Yearly'?'selected':''?>>Yearly</option>
        </select>

        <input type="date" name="start" value="<?= $edit['start_date'] ?? '' ?>" required>
        <input type="date" name="expiry" value="<?= $edit['expiry_date'] ?? '' ?>" required>

        <button name="save">Save Membership</button>
    </form>
</div>
<div class="card">
<table>
<tr>
<th>Member</th><th>Type</th><th>Expiry</th><th>Action</th>
</tr>
<?php foreach($memberships as $m): ?>
<tr>
<td><?=htmlspecialchars($m['name'])?></td>
<td><?=$m['type']?></td>
<td><?=$m['expiry_date']?></td>
<td>
<a href="?edit=<?=$m['id']?>">Edit</a> |
<a href="?delete=<?=$m['id']?>">Delete</a>
</td>
</tr>
<?php endforeach ?>
</table>
</div>

<?php include '../includes/footer.php'; ?>
