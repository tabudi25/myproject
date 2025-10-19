<?php

// Script to check staff account passwords
require_once 'vendor/autoload.php';

// Database connection
$host = 'localhost';
$dbname = 'fluffyplanet';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Checking staff account passwords...\n";
    echo "=====================================\n";
    
    // Get staff accounts with their password hashes
    $stmt = $pdo->prepare("SELECT id, name, email, password, role, created_at FROM users WHERE role = 'staff' ORDER BY id");
    $stmt->execute();
    $staffAccounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($staffAccounts as $account) {
        echo "Staff Account #" . $account['id'] . ":\n";
        echo "Name: " . $account['name'] . "\n";
        echo "Email: " . $account['email'] . "\n";
        echo "Password Hash: " . $account['password'] . "\n";
        echo "Role: " . $account['role'] . "\n";
        echo "Created: " . ($account['created_at'] ?: 'No date') . "\n";
        echo "-------------------------------\n";
    }
    
    echo "\nNote: Passwords are stored as hashed values for security.\n";
    echo "If you need to reset passwords, you can use the password reset functionality\n";
    echo "or create new accounts with known passwords.\n";
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
