<?php
session_start();
require_once("db.php");

if (!isset($_SESSION['admin'])) {
    die("未授權存取");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $price = floatval($_POST["price"]);

    $imagePath = null;
    if (!empty($_FILES["image"]["name"])) {
        $uploadDir = "uploads/ ";
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir);
        }
        $filename = uniqid() . "_" . basename($_FILES["image"]["name"]);
        $targetFile = $uploadDir . $filename;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $imagePath = $targetFile;
        }
    }

    $stmt = $conn->prepare("INSERT INTO products (name, price, image) VALUES (?, ?, ?)");
    $stmt->bind_param("sds", $name, $price, $imagePath);
    $stmt->execute();
    header("Location: admin_panel.php");
    exit;
}
?>

<h2>新增商品</h2>
<form method="POST" enctype="multipart/form-data">
    商品名稱：<input type="text" name="name" required><br>
    價格：<input type="number" name="price" step="0.01" required><br>
    商品圖片：<input type="file" name="image" accept="image/*"><br>
    <button type="submit">新增商品</button>
</form>
