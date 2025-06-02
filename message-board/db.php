<?php
$servername = "localhost";
$username = "root";       // 依你的 DB 使用者設定
$password = "testmysqlroot@";           // 預設密碼為空
$dbname = "php_message";

// 建立連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}
?>
