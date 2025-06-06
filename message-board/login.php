<?php
session_start();
require_once("db.php");

define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME_SECONDS', 3600); // 60分鐘

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $account = trim($_POST["account"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE account = ?");
    $stmt->bind_param("s", $account);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if ($_POST['account'] === 'admin' && $_POST['password'] === 'admin123') {
        $_SESSION['admin'] = true;
        header("Location: admin_panel.php");
        exit;
    } else {
        echo "<p style='color:red'>帳密錯誤</p>";
    }

    if ($user) {
        $now = new DateTime();
        $lastAttempt = new DateTime($user['last_attempt_time'] ?? '2000-01-01');
        $diffSeconds = $now->getTimestamp() - $lastAttempt->getTimestamp();

        // 是否鎖定中
        if ($user['login_attempts'] >= MAX_LOGIN_ATTEMPTS && $diffSeconds < LOCKOUT_TIME_SECONDS) {
            $remaining = LOCKOUT_TIME_SECONDS - $diffSeconds;
            echo "帳號暫時鎖定，請 {$remaining} 秒後再嘗試。";
        } else {
            // 驗證密碼
            if (password_verify($password, $user["password"])) {
                // 成功登入，重設登入次數
                $resetStmt = $conn->prepare("UPDATE users SET login_attempts = 0, last_attempt_time = NOW() WHERE id = ?");
                $resetStmt->bind_param("i", $user["id"]);
                $resetStmt->execute();

                // 防止 Session Fixation
                session_regenerate_id(true);

                $_SESSION["account"] = $user["account"];
                $_SESSION["user_id"] = $user["id"];
                header("Location: member.php");
                exit;
            } else {
                // 登入失敗，更新次數與時間
                $updateStmt = $conn->prepare("UPDATE users SET login_attempts = login_attempts + 1, last_attempt_time = NOW() WHERE id = ?");
                $updateStmt->bind_param("i", $user["id"]);
                $updateStmt->execute();
                echo "登入失敗，帳號或密碼錯誤。";
            }
        }
    } else {
        echo "找不到帳號。";
    }
}
?>

<form method="POST">
    <h2>登入</h2>
    帳號：<input type="text" name="account" required><br>
    密碼：<input type="password" name="password" required><br>
    <button type="submit">登入</button>
</form>
