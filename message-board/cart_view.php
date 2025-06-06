<?php
session_start();
require_once("db.php");

if (!isset($_SESSION["user_id"])) {
    die("請先登入後再查看購物車");
}

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare("
    SELECT c.id, c.quantity, p.name, p.price, p.image
    FROM cart_items c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>購物車內容</title>
    <style>
        body { font-family: Arial; margin: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        img { height: 80px; }
    </style>
</head>
<body>
    <h2>🛒 您的購物車</h2>

    <a href="cart_clear.php">清空購物車</a> | <a href="index.php">回首頁</a>

    <?php if ($result->num_rows > 0): ?>
    <table>
        <tr>
            <th>商品圖</th>
            <th>名稱</th>
            <th>價格</th>
            <th>數量</th>
            <th>小計</th>
        </tr>
        <?php
        $total = 0;
        while ($row = $result->fetch_assoc()):
            $subtotal = $row["price"] * $row["quantity"];
            $total += $subtotal;
        ?>
        <tr>
            <td><img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt=""></td>
            <td><?= htmlspecialchars($row["name"]) ?></td>
            <td>$<?= number_format($row["price"], 2) ?></td>
            <td><?= $row["quantity"] ?></td>
            <td>$<?= number_format($subtotal, 2) ?></td>
        </tr>
        <?php endwhile; ?>
        <tr>
            <td colspan="4"><strong>總金額</strong></td>
            <td><strong>$<?= number_format($total, 2) ?></strong></td>
        </tr>
    </table>
    <?php else: ?>
        <p>購物車是空的。</p>
    <?php endif; ?>
</body>
</html>
