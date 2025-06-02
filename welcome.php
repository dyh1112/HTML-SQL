<?php
session_start();

if (!isset($_SESSION['user']) && !isset($_COOKIE['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'] ?? $_COOKIE['user'];
echo "歡迎你，$user！<br>";
echo '<a href="logout.php">登出</a>';
?>
