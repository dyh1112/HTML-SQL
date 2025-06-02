<?php
session_start();

if (isset($_SESSION['user']) || isset($_COOKIE['user'])) {
    header("Location: welcome.php");
    exit();
}
?>

<form action="do_login.php" method="POST">
    <label>帳號：</label>
    <input type="text" name="username" required><br>
    <label>密碼：</label>
    <input type="password" name="password" required><br>
    <label>
        <input type="checkbox" name="remember"> 記住我
    </label><br>
    <button type="submit">登入</button>
</form>
