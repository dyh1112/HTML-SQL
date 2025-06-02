<?php
session_start();
require_once("db.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $content = $_POST["content"];
    $user_id = $_SESSION["user_id"];

    $stmt = $conn->prepare("INSERT INTO messages (user_id, content) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $content);
    $stmt->execute();
    header("Location: index.php");
    exit;
}
?>

<form method="POST">
    <h2>發表留言</h2>
    <textarea name="content" required></textarea><br>
    <button type="submit">送出</button>
</form>
