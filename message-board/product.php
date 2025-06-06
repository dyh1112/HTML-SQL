<?php
session_start();
require_once("db.php");

// 取得商品 ID
if (!isset($_GET["id"])) {
    echo "找不到商品 ID。";
    exit;
}

$product_id = (int)$_GET["id"];

// 查詢商品
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "找不到該商品。";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product["name"]) ?> - 商品頁面</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        .product-box { max-width: 600px; border: 1px solid #ccc; padding: 20px; border-radius: 10px; }
        .product-box img { max-width: 100%; height: auto; }
        .price { font-size: 1.5em; color: #d9534f; }
        .desc { margin-top: 10px; color: #555; }
    </style>
</head>
<body>
    <div class="product-box">
        <?php if (!empty($product['image'])): ?>
            <img src="uploads/<?= $product['image'] ?>" alt="商品圖片">
        <?php endif; ?>
        <h2><?= htmlspecialchars($product["name"]) ?></h2>
        <div class="price">價格：$<?= number_format($product["price"], 2) ?></div>
        


        <!-- 加入購物車表單 -->
        <form action="cart.php" method="POST" style="margin-top: 20px;">
            <input type="hidden" name="product_id" value="<?= $product["id"] ?>">
            數量：<input type="number" name="quantity" value="1" min="1" style="width: 60px;">
            <button type="submit">加入購物車</button>
        </form>
    </div>
</body>
</html>
