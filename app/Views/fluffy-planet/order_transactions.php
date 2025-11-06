<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fluffy Planet - Order Transaction</title>
    <link rel="stylesheet" href="./web/ordertransaction.css">
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
                                <td><?= htmlspecialchars($order['customer_name']) ?></td>
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
                                <button type="submit" class="download-btn" onclick="return confirmCompleteOrder(event, <?= $order['id'] ?>)">
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmCompleteOrder(event, orderId) {
            event.preventDefault();
            Swal.fire({
                title: 'Confirm Order?',
                text: 'Are you sure you want to mark this order as completed?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, mark as completed!'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.closest('form').submit();
                }
            });
            return false;
        }

        // Show success/error messages if any
        <?php if (session()->getFlashdata('success')): ?>
            Swal.fire({icon: 'success', title: 'Success!', text: "<?= session()->getFlashdata('success') ?>"});
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
            Swal.fire({icon: 'error', title: 'Error', text: "<?= session()->getFlashdata('error') ?>"});
        <?php endif; ?>
    </script>
</body>

</html>
