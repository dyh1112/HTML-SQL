<?php
require_once("db.php");
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);
    if ($stmt->execute()) {
        header("Location: login.php");
        exit;
    } else {
        echo "註冊失敗: " . $conn->error;
    }
}
?>

<form method="POST">
    <h2>註冊</h2>
    使用者名稱：<input type="text" name="username" required><br>
    密碼：<input type="password" name="password" required><br>
    <button type="submit">註冊</button>
</form>
