<?php
session_start();
require_once 'db.php'; // 引入連線檔

// 撈留言和使用者資料，JOIN語法
$sql = "SELECT messages.*, users.username 
        FROM messages 
        JOIN users ON messages.user_id = users.id 
        ORDER BY messages.created_at DESC";


$result = mysqli_query($conn, $sql);

$messages = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $messages[] = $row;
    }
} else {
    die("查詢失敗: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>留言板首頁</title>
</head>
<body>
  <h1>留言板</h1>

  <?php if (isset($_SESSION['user_id'])): ?>
    <p>你好，<?= htmlspecialchars($_SESSION['username']) ?>！<a href="logout.php">登出</a> | <a href="post.php">發表留言</a></p>
  <?php else: ?>
    <p><a href="login.php">登入</a> | <a href="register.php">註冊</a></p>
  <?php endif; ?>

  <hr>

  <?php foreach ($messages as $msg): ?>
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">      
      <?= nl2br(htmlspecialchars($msg['content'])) ?><br>
      <small>by <?= htmlspecialchars($msg['username']) ?> at <?= $msg['created_at'] ?></small>
    </div>
  <?php endforeach; ?>

</body>
</html>
