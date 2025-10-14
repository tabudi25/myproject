
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
  />
  <link rel="stylesheet" href="./web/history.css">
  <title>Fluffy Planet - Order History</title>
  <style>
    
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
          <?php if (isset($orders) && !empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
              <tr id="row-<?= $order['id']; ?>">
                <td><?= htmlspecialchars($order['id']); ?></td>
                <td><?= htmlspecialchars($order['date']); ?></td>
                <td><?= htmlspecialchars($order['customer_name']); ?></td>
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
                          $type  = $animal['type'] ?? 'Unknown';
                          
                          // Display in format: Cat: Pookie - $100.0 (x1)
                          $animalList[] = '<strong>' . htmlspecialchars($type) . ':</strong> ' . htmlspecialchars($name) . ' - ' . htmlspecialchars($price) . ' (x' . htmlspecialchars($qty) . ')';
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
            <?php endforeach; ?>
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
