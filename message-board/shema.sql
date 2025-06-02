CREATE DATABASE IF NOT EXISTS `php_message`;
USE `php_message`;

CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL
);

CREATE TABLE `messages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `content` TEXT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
SHOW DATABASES ;
DESCRIBE `messages`;
DESCRIBE `users`;
SELECT *FROM `users`;
DROP DATABASE `php_messages`;
DROP TABLE `messages`;