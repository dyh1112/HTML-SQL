<?php
session_start();
require_once("db.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// CSRF Token：建立與檢查
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
        die("CSRF 驗證失敗");
    }

    $content = trim($_POST["content"]);
    if ($content === '') {
        die("留言內容不可為空");
    }

    $user_id = $_SESSION["user_id"];

    $stmt = $conn->prepare("INSERT INTO messages (user_id, content) VALUES (?, ?)");
    if (!$stmt) {
        die("資料庫錯誤: " . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("is", $user_id, $content);
    if (!$stmt->execute()) {
        die("留言失敗: " . htmlspecialchars($stmt->error));
    }

    // 提交成功後重置 token，防止重送
    unset($_SESSION['csrf_token']);
    header("Location: index.php");
    exit;
}

/* | 功能                     | 說明                      |
| ---------------------- | ----------------------- |
| `trim()`               | 避免全空格留言                 |
| `csrf_token`           | 預防跨站偽造攻擊                |
| `prepare + bind_param` | 防止 SQL Injection        |
| `htmlspecialchars()`   | 輸出時避免 XSS（你已於 index 處理） |*/

?>

<!-- 留言表單 -->
<form method="POST">
    <h2>發表留言</h2>
    <textarea name="content" required></textarea><br>
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
    <button type="submit">送出</button>
</form>


