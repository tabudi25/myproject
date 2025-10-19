<?php

// Script to check existing staff accounts
require_once 'vendor/autoload.php';

// Database connection
$host = 'localhost';
$dbname = 'fluffyplanet';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Checking existing staff accounts...\n";
    echo "=====================================\n";
    
    // Check users table for staff accounts
    $stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'staff' ORDER BY created_at");
    $stmt->execute();
    $staffUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Staff accounts in users table:\n";
    echo "-------------------------------\n";
    
    if (empty($staffUsers)) {
        echo "No staff accounts found in users table.\n";
    } else {
        foreach ($staffUsers as $user) {
            echo "ID: " . $user['id'] . "\n";
            echo "Name: " . $user['name'] . "\n";
            echo "Email: " . $user['email'] . "\n";
            echo "Role: " . $user['role'] . "\n";
            echo "Created: " . $user['created_at'] . "\n";
            echo "-------------------------------\n";
        }
    }
    
    // Check staff table for detailed staff info
    $stmt = $pdo->prepare("SELECT * FROM staff ORDER BY created_at");
    $stmt->execute();
    $staffDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\nStaff details in staff table:\n";
    echo "-------------------------------\n";
    
    if (empty($staffDetails)) {
        echo "No staff details found in staff table.\n";
    } else {
        foreach ($staffDetails as $staff) {
            echo "ID: " . $staff['id'] . "\n";
            echo "Name: " . $staff['name'] . "\n";
            echo "Email: " . $staff['email'] . "\n";
            echo "Phone: " . ($staff['phone'] ?? 'N/A') . "\n";
            echo "Position: " . ($staff['position'] ?? 'N/A') . "\n";
            echo "Department: " . ($staff['department'] ?? 'N/A') . "\n";
            echo "Status: " . ($staff['status'] ?? 'N/A') . "\n";
            echo "Created: " . $staff['created_at'] . "\n";
            echo "-------------------------------\n";
        }
    }
    
    echo "\nTotal staff accounts found: " . count($staffUsers) . "\n";
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
