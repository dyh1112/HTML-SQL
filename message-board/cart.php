<?php
session_start();
require_once("db.php");

if (!isset($_SESSION["user_id"])) {
    die("請先登入後再加入購物車");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION["user_id"];
    $product_id = (int)$_POST["product_id"];
    $quantity = max(1, (int)$_POST["quantity"]);

    // 檢查是否已有此商品在購物車內，若有則更新數量
    $check_stmt = $conn->prepare("SELECT id  FROM products WHERE id = ? ");
    $check_stmt->bind_param("i",$product_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // 插入 cart_items
        $stmt = $conn->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);
        if ($stmt->execute()) {
            echo "✅ 已加入購物車";
        } else {
            echo "❌ 加入失敗：" . $conn->error;
        }
    } else {
        echo "❌ 該商品不存在";
    }

    header("Location: cart_view.php");
    exit;
}
?>
