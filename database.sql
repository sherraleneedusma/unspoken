-- SQL file to create the database and letters table for Unspoken project

CREATE DATABASE IF NOT EXISTS unspoken CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE unspoken;

DROP TABLE IF EXISTS letters;

CREATE TABLE letters (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    recipient VARCHAR(255) DEFAULT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
