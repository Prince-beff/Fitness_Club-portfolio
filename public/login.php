<?php
session_start();
require '../config/db.php';

if($_POST){
    $stmt=$pdo->prepare("SELECT * FROM users WHERE email=?");
    $stmt->execute([$_POST['email']]);
    $user=$stmt->fetch();

    if($user && password_verify($_POST['password'],$user['password'])){
        $_SESSION['user']=$user;
        header("Location: dashboard.php");
    } else $error="Invalid login";
    if(!$user){
        echo "Email not found";
    } elseif(!password_verify($_POST['password'],$user['password'])){
            echo "Password mismatch";
}

}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="stylesheet" type="text/css" href="../assets/style.css">
</head>
<body>
  <div class="login-page">
    <div class="login-box">
      <h2>Login</h2>

      <form method="post">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
      </form>

      <p class="login-error"><?= $error ?? '' ?></p>
    </div>
  </div>
</body>

</html>