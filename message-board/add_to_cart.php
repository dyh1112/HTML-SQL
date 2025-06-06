<?php
session_start();
require_once("db.php");

if (!isset($_SESSION['user_id'])) {
    die("請先登入");
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$quantity = intval($_POST['quantity']);

$stmt = $conn->prepare("SELECT id FROM cart_items WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // 更新數量
    $update = $conn->prepare("UPDATE cart_items SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
    $update->bind_param("iii", $quantity, $user_id, $product_id);
    $update->execute();
} else {
    // 新增
    $insert = $conn->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)");
    $insert->bind_param("iii", $user_id, $product_id, $quantity);
    $insert->execute();
}

header("Location: cart.php");
exit;
?>
