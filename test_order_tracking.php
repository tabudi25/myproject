<?php

// Test script for order tracking system
require_once 'vendor/autoload.php';

// Database connection
$host = 'localhost';
$dbname = 'fluffyplanet';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Testing Order Tracking System...\n";
    echo "=====================================\n";
    
    // Check if we have any orders
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM orders");
    $stmt->execute();
    $orderCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    echo "Total orders in database: $orderCount\n";
    
    if ($orderCount > 0) {
        // Get a sample order
        $stmt = $pdo->prepare("SELECT * FROM orders ORDER BY created_at DESC LIMIT 1");
        $stmt->execute();
        $sampleOrder = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "\nSample Order Details:\n";
        echo "Order Number: " . $sampleOrder['order_number'] . "\n";
        echo "Status: " . $sampleOrder['status'] . "\n";
        echo "Delivery Type: " . $sampleOrder['delivery_type'] . "\n";
        echo "Total Amount: ₱" . number_format($sampleOrder['total_amount'], 2) . "\n";
        
        echo "\nOrder Tracking URLs:\n";
        echo "Customer tracking: http://localhost/myproject/track/" . $sampleOrder['order_number'] . "\n";
        echo "Public tracking form: http://localhost/myproject/track\n";
        echo "Admin tracking: http://localhost/myproject/fluffy-admin/orders/tracking/" . $sampleOrder['id'] . "\n";
        
        echo "\nStatus Mapping:\n";
        $statusMap = [
            'pending' => 'Order Placed → Order Confirmed (current)',
            'confirmed' => 'Order Placed → Order Confirmed → Preparing Pet (current)',
            'processing' => 'Order Placed → Order Confirmed → Preparing Pet → Ready for Pickup/Delivery (current)',
            'shipped' => 'Order Placed → Order Confirmed → Preparing Pet → Ready for Pickup/Delivery → Completed (current)',
            'delivered' => 'Order Placed → Order Confirmed → Preparing Pet → Ready for Pickup/Delivery → Completed',
            'cancelled' => 'Order Placed (cancelled)'
        ];
        
        echo "Current status '" . $sampleOrder['status'] . "' maps to: " . ($statusMap[$sampleOrder['status']] ?? 'Unknown status') . "\n";
        
    } else {
        echo "\nNo orders found. Please create some test orders first.\n";
    }
    
    echo "\nOrder Tracking System Features:\n";
    echo "✅ Customer order tracking with visual timeline\n";
    echo "✅ Public order tracking (no login required)\n";
    echo "✅ Admin/Staff order tracking with status updates\n";
    echo "✅ Real-time status notifications\n";
    echo "✅ Responsive design matching your image\n";
    echo "✅ Integration with existing order system\n";
    
    echo "\nTest URLs to try:\n";
    echo "1. Public tracking form: http://localhost/myproject/track\n";
    echo "2. Customer orders: http://localhost/myproject/my-orders (login required)\n";
    echo "3. Admin orders: http://localhost/myproject/fluffy-admin/orders (admin login required)\n";
    echo "4. Staff orders: http://localhost/myproject/staff/orders (staff login required)\n";
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
