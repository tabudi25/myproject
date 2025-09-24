<?php
// save_order.php
include 'db.php'; // database connection

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo "Method not allowed";
    exit;
}

// collect form data
$order_id       = $_POST['order_id'] ?? '';
$date           = $_POST['date'] ?? date('Y-m-d');
$customer       = $_POST['customer'] ?? '';
$gmail          = $_POST['gmail'] ?? '';
$tel_number     = $_POST['tel_number'] ?? '';
$address        = $_POST['address'] ?? '';
$total_raw      = $_POST['total'] ?? '0';
$payment_status = $_POST['payment_status'] ?? 'pending';
$order_status   = $_POST['order_status'] ?? 'pending';

// handle animal data (ensure JSON format)
$animal_input = $_POST['animal'] ?? null;
$animal_json = '[]';

if (is_array($animal_input)) {
    $animal_json = json_encode($animal_input);
} else if (is_string($animal_input)) {
    $decoded = json_decode($animal_input, true);
    if (json_last_error() === JSON_ERROR_NONE && (is_array($decoded) || is_object($decoded))) {
        $animal_json = json_encode($decoded);
    } else {
        $animal_json = json_encode([['name' => $animal_input, 'qty' => 1]]);
    }
}

// normalize total
$total = floatval(str_replace([',','$',' '], ['', '', ''], $total_raw));

// save to database using prepared statement
$sql = "INSERT INTO orders (order_id, date, customer, gmail, tel_number, animal, address, total, payment_status, order_status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param(
        "sssssssdss",
        $order_id,
        $date,
        $customer,
        $gmail,
        $tel_number,
        $animal_json,
        $address,
        $total,
        $payment_status,
        $order_status
    );

    if ($stmt->execute()) {
        // redirect to history page
        header("Location: history.php");
        exit;
    } else {
        echo "❌ Error executing query: " . htmlspecialchars($stmt->error);
    }
    $stmt->close();
} else {
    echo "❌ Error preparing query: " . htmlspecialchars($conn->error);
}

$conn->close();
?>
