<?php
session_start();
require_once("db.php");

if (!isset($_SESSION["user_id"])) {
    die("請先登入後再操作");
}

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare("DELETE FROM cart_items WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

header("Location: cart_view.php");
exit;
?>
