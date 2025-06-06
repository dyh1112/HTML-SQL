CREATE DATABASE IF NOT EXISTS `php_message`;
USE `php_message`;

CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL
);

ALTER TABLE users 
ADD COLUMN login_attempts INT DEFAULT 0,
ADD COLUMN last_attempt_time DATETIME DEFAULT NULL;

ALTER TABLE `users` 
ADD COLUMN `full_name` VARCHAR(255),
ADD COLUMN `email` VARCHAR(255),
ADD COLUMN `avatar` VARCHAR(255); -- 頭像檔案名稱（路徑）

CREATE TABLE `messages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `content` TEXT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- 商品列表
CREATE TABLE `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `price` DECIMAL(10, 2) NOT NULL,
    `image` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 購物車（每位用戶一筆）
CREATE TABLE `cart_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `quantity` INT NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

SHOW DATABASES ;
DESCRIBE `messages`;
DESCRIBE `users`;
SELECT *FROM `users`;
DROP DATABASE `php_messages`;
DROP TABLE `messages`;

