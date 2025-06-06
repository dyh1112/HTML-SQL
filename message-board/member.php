<?php
session_start();
require_once("db.php");

// 未登入者導向登入頁
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>會員專區</title>
</head>
<body>
    <h1>會員介面</h1>
    <p>歡迎回來，<?= htmlspecialchars($_SESSION["account"]) ?>！</p>

    <ul>
        <li><a href="index.php">回首頁</a></li>
        <li><a href="post.php">發表留言</a></li>
        <li><a href="logout.php">登出</a></li>
    </ul>

    <p>這是只有登入會員才能看到的內容。</p>
</body>
</html>
