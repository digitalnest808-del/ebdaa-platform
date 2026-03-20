<?php
/**
 * Database Initialization Script
 * Creates all necessary tables for the Ebdaa platform
 */

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'ebdaa_platform';

try {
    // Connect to MySQL server (without database)
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    
    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    // Select the database
    $pdo->exec("USE `$dbname`");
    
    // Create users table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            full_name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            user_type ENUM('كاتب', 'مصمم', 'قارئ') DEFAULT 'قارئ',
            bio TEXT,
            profile_image VARCHAR(255) DEFAULT 'default-avatar.png',
            cover_image VARCHAR(255) DEFAULT 'default-cover.jpg',
            is_active BOOLEAN DEFAULT TRUE,
            is_admin BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            last_login TIMESTAMP NULL,
            remember_token VARCHAR(255) NULL,
            INDEX idx_email (email),
            INDEX idx_user_type (user_type)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    
    // Create books table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS books (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            title VARCHAR(200) NOT NULL,
            description TEXT,
            cover_image VARCHAR(255),
            book_file VARCHAR(255),
            category VARCHAR(50),
            views INT DEFAULT 0,
            downloads INT DEFAULT 0,
            is_published BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_user_id (user_id),
            INDEX idx_category (category),
            INDEX idx_is_published (is_published)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    
    // Create designs table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS designs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            title VARCHAR(200) NOT NULL,
            description TEXT,
            design_image VARCHAR(255) NOT NULL,
            category VARCHAR(50),
            views INT DEFAULT 0,
            is_published BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_user_id (user_id),
            INDEX idx_category (category),
            INDEX idx_is_published (is_published)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    
    // Create comments table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS comments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            content_type ENUM('book', 'design') NOT NULL,
            content_id INT NOT NULL,
            comment_text TEXT NOT NULL,
            rating INT DEFAULT NULL CHECK (rating >= 1 AND rating <= 5),
            is_approved BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_content (content_type, content_id),
            INDEX idx_user_id (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    
    // Create stats table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS stats (
            id INT AUTO_INCREMENT PRIMARY KEY,
            page_views INT DEFAULT 0,
            total_users INT DEFAULT 0,
            total_books INT DEFAULT 0,
            total_designs INT DEFAULT 0,
            date DATE UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_date (date)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    
    // Create follows table for user following system
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS follows (
            id INT AUTO_INCREMENT PRIMARY KEY,
            follower_id INT NOT NULL,
            following_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (follower_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (following_id) REFERENCES users(id) ON DELETE CASCADE,
            UNIQUE KEY unique_follow (follower_id, following_id),
            INDEX idx_following_id (following_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    
    echo "✓ تم إنشاء قاعدة البيانات والجداول بنجاح!";
    
} catch (PDOException $e) {
    echo "✗ خطأ: " . $e->getMessage();
    exit;
}
?>
