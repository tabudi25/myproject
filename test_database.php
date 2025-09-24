<?php
// Simple database connection test for XAMPP
echo "<h2>Database Connection Test</h2>";

// Test direct MySQL connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fluffyplanet";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("<p style='color: red;'>❌ Connection failed: " . $conn->connect_error . "</p>");
    }
    
    echo "<p style='color: green;'>✅ Successfully connected to database 'fluffyplanet'!</p>";
    
    // Test if orders table exists
    $result = $conn->query("SHOW TABLES LIKE 'orders'");
    if ($result->num_rows > 0) {
        echo "<p style='color: green;'>✅ Orders table exists!</p>";
        
        // Show table structure
        $result = $conn->query("DESCRIBE orders");
        echo "<h3>Orders Table Structure:</h3>";
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Default']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Extra']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Count existing orders
        $result = $conn->query("SELECT COUNT(*) as count FROM orders");
        $row = $result->fetch_assoc();
        echo "<p><strong>Current orders in database: " . $row['count'] . "</strong></p>";
        
    } else {
        echo "<p style='color: red;'>❌ Orders table does not exist!</p>";
    }
    
    $conn->close();
    
} catch(Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><strong>Database Setup Complete!</strong></p>";
echo "<p>You can now:</p>";
echo "<ul>";
echo "<li>Access your order form at: <a href='/order'>/order</a></li>";
echo "<li>View order transactions at: <a href='/order_transactions'>/order_transactions</a></li>";
echo "<li>Check order history at: <a href='/history'>/history</a></li>";
echo "</ul>";
?>
