<?php
// Include database connection
require_once 'db.php';

// --- Handle AJAX Delete request ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $sql = "DELETE FROM orders WHERE id = $id";
    if ($conn->query($sql)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
    exit; // stop rendering HTML for AJAX
}

// Fetch all orders from database (✅ includes address)
$sql = "SELECT id, date, customer, gmail, tel_number, address, animal, total, payment_status, order_status 
        FROM orders 
        ORDER BY date DESC, id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
  />
  <title>Fluffy Planet - Order History</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #fff8f1;
      color: #2c2c2c;
      line-height: 1.6;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 50px;
      background-color: #fff;
    }

    .logo {
      font-size: 24px;
      font-weight: bold;
    }

    nav {
      flex: 1;
      display: flex;
      justify-content: center;
      margin-right: 150px;
    }

    nav a {
      margin: 0 20px;
      text-decoration: none;
      color: #333;
      font-weight: 500;
      padding: 5px;
      border-radius: 10px;
    }

    nav a:hover {
      background-color: #7c7a78;
      color: #fff;
    }

    .order {
      text-decoration: underline;
      background-color: #eccfb2;
    }

    .container {
      max-width: 100%;
      margin: 40px auto;
      padding: 20px;
    }

    .box {
      background-color: #fff;
      padding: 25px;
      border-radius: 20px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      overflow-x: auto;
    }

    h2 {
      text-align: center;
      color: #444;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 15px;
      table-layout: auto;
      word-wrap: break-word;
      white-space: normal;
    }

    table th,
    table td {
      padding: 12px 8px;
      border-bottom: 1px solid #eee;
      text-align: left;
      vertical-align: top;
    }

    table th {
      background-color: #eccfb2;
      color: #2c2c2c;
      font-weight: bold;
    }

    .status {
      padding: 6px 12px;
      border-radius: 12px;
      font-size: 13px;
      font-weight: bold;
      display: inline-block;
      white-space: nowrap;
    }

    .pending { background-color: #fff3cd; color: #856404; }
    .processing { background-color: #cce5ff; color: #004085; }
    .completed { background-color: #d4edda; color: #155724; }
    .cancelled { background-color: #f8d7da; color: #721c24; }

    .actions a {
      text-decoration: none;
      font-weight: 500;
      margin-right: 10px;
      padding: 6px 10px;
      border-radius: 6px;
      display: inline-flex;
      align-items: center;
    }

    .actions a.view {
      background-color: #4caf50;
      color: white;
    }

    .actions a.view:hover {
      background-color: #45a049;
    }

    .actions i {
      margin-left: 5px;
    }

    .message {
      text-align: center;
      padding: 10px;
      margin-bottom: 15px;
      font-weight: bold;
      color: green;
    }
  </style>
</head>

<body>
  <header>
    <div class="logo">🐾 Fluffy Planet</div>
    <nav>
      <a href="<?= base_url('petshop') ?>">Home</a>
      <a href="<?= base_url('categories') ?>">Categories</a>
      <a href="<?= base_url('newarrival') ?>">New Arrivals</a>
      <a href="<?= base_url('order') ?>">Order</a>
      <a href="<?= base_url('order_transactions') ?>">Order Transaction</a>
      <a href="<?= base_url('history') ?>" class="order">History</a>
    </nav>
  </header>

  <div class="container">
    <div class="box">
      <h2>Order History</h2>
      <div id="message"></div>

      <table id="orderTable">
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Date</th>
            <th>Customer</th>
            <th>Gmail</th>
            <th>Tel Number</th>
            <th>Address</th>
            <th>Animal</th>
            <th>Total</th>
            <th>Payment Status</th>
            <th>Order Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($order = $result->fetch_assoc()): ?>
              <tr id="row-<?= $order['id']; ?>">
                <td><?= htmlspecialchars($order['id']); ?></td>
                <td><?= htmlspecialchars($order['date']); ?></td>
                <td><?= htmlspecialchars($order['customer']); ?></td>
                <td style="word-break: break-word;"><?= htmlspecialchars($order['gmail']); ?></td>
                <td><?= htmlspecialchars($order['tel_number']); ?></td>
                <td style="max-width:200px; word-break: break-word;"><?= htmlspecialchars($order['address']); ?></td>
                <td style="max-width:250px; word-break: break-word;">
                  <?php
                  // Decode JSON data from "animal" column
                  $animals = json_decode($order['animal'], true);

                  if (json_last_error() === JSON_ERROR_NONE && is_array($animals)) {
                      $animalList = [];
                      foreach ($animals as $animal) {
                          $name  = $animal['name'] ?? 'Unknown';
                          $price = $animal['price'] ?? 'N/A';
                          $qty   = $animal['qty'] ?? 1;

                          // Display in format: Pookie - $100.0 (x1)
                          $animalList[] = htmlspecialchars($name) . ' - ' . htmlspecialchars($price) . ' (x' . htmlspecialchars($qty) . ')';
                      }
                      echo implode('<br>', $animalList);
                  } else {
                      // Fallback: print raw value if not JSON
                      echo htmlspecialchars($order['animal']);
                  }
                  ?>
                </td>
                <td>₱<?= number_format($order['total'], 2); ?></td>
                <td><span class="status <?= strtolower($order['payment_status']); ?>"><?= ucfirst($order['payment_status']); ?></span></td>
                <td><span class="status <?= strtolower($order['order_status']); ?>"><?= ucfirst($order['order_status']); ?></span></td>
                <td class="actions">
                  <a href="<?= base_url('view_order/' . $order['id']); ?>" class="view">
                    View <i class="fa fa-eye"></i>
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="11" style="text-align:center; color:#777;">No orders found in database</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>

    </div>
  </div>
</body>
</html>
