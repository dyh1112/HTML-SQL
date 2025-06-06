<?php
require_once("db.php");

$sql = "SELECT id, name, price, image FROM products ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>商品列表</title>
    <style>
        .product { border: 1px solid #ccc; padding: 10px; margin: 10px; width: 200px; float: left; }
        .product img { width: 100%; height: auto; }
    </style>
</head>
<body>
    <h1>商品列表</h1>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="product">
            <a href="product.php?id=<?= $row['id'] ?>">
                <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                <h3><?= htmlspecialchars($row['name']) ?></h3>
                <p>價格：$<?= number_format($row['price'], 2) ?></p>
            </a>
        </div>
    <?php endwhile; ?>
</body>
</html>