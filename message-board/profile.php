<?php
session_start();
require_once("db.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = $_POST["full_name"];
    $email = $_POST["email"];

    // 上傳頭像
    if (!empty($_FILES["avatar"]["name"])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir);
        }
        $avatar_name = basename($_FILES["avatar"]["name"]);
        $target_file = $target_dir . time() . "_" . $avatar_name;

        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
            $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, avatar = ? WHERE id = ?");
            $stmt->bind_param("sssi", $full_name, $email, $target_file, $user_id);
        } else {
            echo "頭像上傳失敗。";
            exit;
        }
    } else {
        $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $full_name, $email, $user_id);
    }

    if ($stmt->execute()) {
        echo "資料更新成功！<br><a href='profile.php'>重新整理</a>";
        exit;
    } else {
        echo "更新失敗：" . $conn->error;
    }
}

// 撈出使用者資料
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<h2>會員資料管理</h2>
<form method="POST" enctype="multipart/form-data">
    姓名：<input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>"><br>
    Email：<input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>"><br>
    頭像：<input type="file" name="avatar"><br>
    <?php if (!empty($user["avatar"])): ?>
        <img src="<?= htmlspecialchars($user["avatar"]) ?>" width="100" alt="目前頭像"><br>
    <?php endif; ?>
    <button type="submit">更新資料</button>
</form>
