<?php
session_start();

// 假設帳號密碼寫死為 admin / 1234
$valid_user = "admin";
$valid_pass = "1234";

$username = $_POST['username'];
$password = $_POST['password'];

if ($username === $valid_user && $password === $valid_pass) {
    $_SESSION['user'] = $username;

    if (isset($_POST['remember'])) {
        setcookie("user", $username, time() + (86400 * 30), "/"); // 保存 30 天
    }

    header("Location: welcome.php");
    exit();
} else {
    echo "帳號或密碼錯誤";
}
?>
