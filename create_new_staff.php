<?php

// Script to create a new staff account
require_once 'vendor/autoload.php';

// Database connection
$host = 'localhost';
$dbname = 'fluffyplanet';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Creating new staff account...\n";
    
    // Staff details - you can modify these as needed
    $staffName = 'New Staff Member';
    $staffEmail = 'newstaff@fluffyplanet.com';
    $staffPassword = 'newstaff123';
    $staffPhone = '09123456789';
    $staffPosition = 'Sales Associate';
    $staffDepartment = 'Sales';
    
    // Check if email already exists in users table
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$staffEmail]);
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existingUser) {
        echo "Error: Email $staffEmail already exists in users table!\n";
        exit;
    }
    
    // Check if email already exists in staff table
    $stmt = $pdo->prepare("SELECT * FROM staff WHERE email = ?");
    $stmt->execute([$staffEmail]);
    $existingStaff = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existingStaff) {
        echo "Error: Email $staffEmail already exists in staff table!\n";
        exit;
    }
    
    // Start transaction
    $pdo->beginTransaction();
    
    try {
        // Insert into users table
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, ?, ?)");
        $result1 = $stmt->execute([
            $staffName,
            $staffEmail,
            password_hash($staffPassword, PASSWORD_DEFAULT),
            'staff',
            date('Y-m-d H:i:s')
        ]);
        
        if (!$result1) {
            throw new Exception("Failed to insert into users table");
        }
        
        // Get the user ID
        $userId = $pdo->lastInsertId();
        
        // Insert into staff table
        $stmt = $pdo->prepare("INSERT INTO staff (name, email, password, phone, position, department, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $result2 = $stmt->execute([
            $staffName,
            $staffEmail,
            password_hash($staffPassword, PASSWORD_DEFAULT),
            $staffPhone,
            $staffPosition,
            $staffDepartment,
            'active',
            date('Y-m-d H:i:s')
        ]);
        
        if (!$result2) {
            throw new Exception("Failed to insert into staff table");
        }
        
        // Commit transaction
        $pdo->commit();
        
        echo "New staff account created successfully!\n";
        echo "=====================================\n";
        echo "Name: $staffName\n";
        echo "Email: $staffEmail\n";
        echo "Password: $staffPassword\n";
        echo "Phone: $staffPhone\n";
        echo "Position: $staffPosition\n";
        echo "Department: $staffDepartment\n";
        echo "Status: active\n";
        echo "=====================================\n";
        echo "Staff can now login using these credentials.\n";
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollback();
        throw $e;
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
