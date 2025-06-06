<?php
session_start();
require_once("db.php");

// 建立 CSRF Token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
        die("CSRF 驗證失敗");
    }

    $account = htmlspecialchars(trim($_POST["account"] ?? ''));
    $password_raw = $_POST["password"] ?? '';

    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password_raw)) {
        $error = "密碼需至少 8 個字元，且至少包含一個英文和一個數字。";
    } else {
        $password_hashed = password_hash($password_raw, PASSWORD_DEFAULT);

        // 檢查帳號是否存在
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE account = ?");
        $check_stmt->bind_param("s", $account);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $error = "帳號已存在，請使用其他名稱。";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (account, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $account, $password_hashed);
            if ($stmt->execute()) {
                unset($_SESSION['csrf_token']);
                $_SESSION["user_id"] = $conn->insert_id;
                $_SESSION["account"] = $account;
                header("Location: index.php");
                exit;
            } else {
                $error = "註冊失敗，請稍後再試。";
            }
        }
    }
}
?>

<!-- 註冊表單 -->
<form method="POST">
    <h2>註冊</h2>
    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    帳號：<input type="text" name="account" required><br>
    密碼：<input type="password" name="password" required><br>

    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
    <button type="submit">註冊</button>
</form>
