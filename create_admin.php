<?php

// Simple script to create admin and staff users
require_once 'vendor/autoload.php';

// Database connection
$host = 'localhost';
$dbname = 'fluffyplanet';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Creating admin and staff users...\n";
    
    // Check if admin exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'admin' LIMIT 1");
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$admin) {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, ?, ?)");
        $result = $stmt->execute([
            'Admin User',
            'admin@fluffyplanet.com',
            password_hash('admin123', PASSWORD_DEFAULT),
            'admin',
            date('Y-m-d H:i:s')
        ]);
        
        if ($result) {
            echo "Admin user created successfully!\n";
            echo "Email: admin@fluffyplanet.com\n";
            echo "Password: admin123\n";
        } else {
            echo "Failed to create admin user\n";
        }
    } else {
        echo "Admin user already exists\n";
        echo "Email: " . $admin['email'] . "\n";
    }
    
    // Check if staff exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'staff' LIMIT 1");
    $stmt->execute();
    $staff = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$staff) {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, ?, ?)");
        $result = $stmt->execute([
            'Staff User',
            'staff@fluffyplanet.com',
            password_hash('staff123', PASSWORD_DEFAULT),
            'staff',
            date('Y-m-d H:i:s')
        ]);
        
        if ($result) {
            echo "Staff user created successfully!\n";
            echo "Email: staff@fluffyplanet.com\n";
            echo "Password: staff123\n";
        } else {
            echo "Failed to create staff user\n";
        }
    } else {
        echo "Staff user already exists\n";
        echo "Email: " . $staff['email'] . "\n";
    }
    
    echo "User creation process completed!\n";
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}