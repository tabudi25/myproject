<?php
function getDatabaseConnection() {
    $servername = "localhost";  // XAMPP runs locally
    $username   = "root";       // default username in XAMPP
    $password   = "";           // default password is empty
    $dbname     = "fluffyplanet"; // the database you created in Step 1

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Create a global connection instance for backward compatibility
$conn = getDatabaseConnection();
?>
