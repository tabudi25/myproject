<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fluffy Planet - Order Transaction</title>
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
        }

        .order {
            text-decoration: underline;
            background-color: #eccfb2;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }

        .box {
            background-color: #fff;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        h2 {
            text-align: center;
            color: #444;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            font-size: 16px;
            border-collapse: collapse;
        }

        table td {
            padding: 10px 6px;
            border-bottom: 1px solid #eee;
        }

        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 15px;
        }

        .download-btn {
            background-color: #4caf50;
            color: white;
            font-weight: bold;
            display: block;
            margin: 20px auto 0;
            border: none;
            border-radius: 20px;
            padding: 12px 25px;
            cursor: pointer;
        }

        .download-btn:hover {
            background-color: #45a049;
        }

        .status {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }

        .status.processing {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status.completed {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .order-card {
            background-color: #f9f9f9;
            transition: box-shadow 0.3s ease;
        }

        .order-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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
            <a href="<?= base_url('order_transactions') ?>" class="order">Order Transaction</a>
            <a href="<?= base_url('history') ?>">History</a>
        </nav>
    </header>

    <div class="container">
        <!-- Transaction Summary -->
        <div class="box">
            <h2>Order Transactions</h2>
            <?php if (isset($orders) && !empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                    <div class="order-card" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 8px;">
                        <h3>Order #<?= htmlspecialchars($order['id']) ?></h3>
                        <table style="width: 100%;">
                            <tr>
                                <td><strong>Customer:</strong></td>
                                <td><?= htmlspecialchars($order['customer']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td><?= htmlspecialchars($order['gmail']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Phone:</strong></td>
                                <td><?= htmlspecialchars($order['tel_number']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Address:</strong></td>
                                <td><?= htmlspecialchars($order['address']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Date:</strong></td>
                                <td><?= htmlspecialchars($order['date']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Animals:</strong></td>
                                <td>
                                    <?php
                                    $animals = json_decode($order['animal'], true);
                                    if (json_last_error() === JSON_ERROR_NONE && is_array($animals)) {
                                        foreach ($animals as $animal) {
                                            $name = $animal['name'] ?? 'Unknown';
                                            $price = $animal['price'] ?? 'N/A';
                                            $qty = $animal['qty'] ?? 1;
                                            $type = $animal['type'] ?? 'Unknown';
                                            echo '<strong>' . htmlspecialchars($type) . ':</strong> ' . htmlspecialchars($name) . ' - ' . htmlspecialchars($price) . ' (x' . htmlspecialchars($qty) . ')<br>';
                                        }
                                    } else {
                                        echo htmlspecialchars($order['animal']);
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Payment Method:</strong></td>
                                <td><?= htmlspecialchars($order['payment_method'] ?? 'COD') ?></td>
                            </tr>
                            <tr>
                                <td><strong>Total:</strong></td>
                                <td>$<?= number_format($order['total'], 2) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="status <?= strtolower($order['order_status']) ?>"><?= htmlspecialchars($order['order_status']) ?></span>
                                </td>
                            </tr>
                        </table>
                        
                        <?php if ($order['order_status'] === 'Processing'): ?>
                            <form method="POST" action="<?= base_url('confirm_order/' . $order['id']) ?>" style="margin-top: 15px;">
                                <button type="submit" class="download-btn" onclick="return confirm('Are you sure you want to mark this order as completed?')">
                                    Confirm Order
                                </button>
                            </form>
                        <?php else: ?>
                            <div style="margin-top: 15px; color: green; font-weight: bold;">✅ Order Completed</div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; color: #777;">No orders found.</p>
            <?php endif; ?>
        </div>
        
    </div>

    <script>
        // Show success/error messages if any
        <?php if (session()->getFlashdata('success')): ?>
            alert("<?= session()->getFlashdata('success') ?>");
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
            alert("<?= session()->getFlashdata('error') ?>");
        <?php endif; ?>
    </script>
</body>

</html>
