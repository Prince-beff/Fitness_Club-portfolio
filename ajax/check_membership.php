<?php
require '../config/db.php';

$user_id = $_GET['user_id'];

$stmt=$pdo->prepare(
 "SELECT * FROM memberships
  WHERE user_id=? AND expiry_date >= CURDATE()"
);
$stmt->execute([$user_id]);

echo $stmt->rowCount()>0
? "✅ Active Membership"
: "❌ No Active Membership";
