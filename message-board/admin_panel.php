<?php
session_start();
require_once("db.php");
if (!isset($_SESSION['admin'])) {
    die("未授權存取。");
}

// 商品刪除
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM products WHERE id = $id");
    header("Location: admin_panel.php");
    exit;
}

// 顯示商品列表
$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<h2>後台商品管理</h2>
<a href="admin_add_product.php">新增商品</a><br><br>
<table border="1" cellpadding="8">
    <tr><th>圖片</th><th>名稱</th><th>價格</th><th>操作</th></tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><img src="<?= htmlspecialchars($row['image']) ?>" width="80"></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td>$<?= $row['price'] ?></td>
            <td>
                <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('確認刪除？')">刪除</a>
            </td>
        </tr>

    <?php endwhile; ?>
</table>
