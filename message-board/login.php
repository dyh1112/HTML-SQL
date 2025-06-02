<?php
session_start();
require_once("db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["username"] = $user["username"];
        $_SESSION["user_id"] = $user["id"];
        header("Location: index.php");
        exit;
    } else {
        echo "登入失敗。";
    }
}
?>

<form method="POST">
    <h2>登入</h2>
    使用者名稱：<input type="text" name="username" required><br>
    密碼：<input type="password" name="password" required><br>
    <button type="submit">登入</button>
</form>
