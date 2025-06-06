<!-- admin_login.php -->
<form method="POST">
    <h2>後台登入</h2>
    帳號：<input name="account" required><br>
    密碼：<input type="password" name="password" required><br>
    <button type="submit">登入</button>
</form>

<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['account'] === 'admin' && $_POST['password'] === 'admin123') {
        $_SESSION['admin'] = true;
        header("Location: admin_panel.php");
        exit;
    } else {
        echo "<p style='color:red'>帳密錯誤</p>";
    }
}
?>
