<?php
echo "<h2>URL Rewriting Test</h2>";
echo "<p><strong>Base URL:</strong> " . (isset($_SERVER['HTTP_HOST']) ? 'http://' . $_SERVER['HTTP_HOST'] : 'N/A') . "</p>";
echo "<p><strong>Request URI:</strong> " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "</p>";
echo "<p><strong>Script Name:</strong> " . ($_SERVER['SCRIPT_NAME'] ?? 'N/A') . "</p>";

echo "<hr>";
echo "<h3>Test Links:</h3>";
echo "<ul>";
echo "<li><a href='/order'>Order Page (should be /order, not /index.php/order)</a></li>";
echo "<li><a href='/order_transactions'>Order Transactions (should be /order_transactions)</a></li>";
echo "<li><a href='/history'>History Page (should be /history)</a></li>";
echo "</ul>";

echo "<hr>";
echo "<p><strong>If the links above work without 'index.php' in the URL, then URL rewriting is working correctly!</strong></p>";
?>
