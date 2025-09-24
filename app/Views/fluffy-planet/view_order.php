<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .receipt {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border: 1px solid #333;
            border-radius: 10px;
        }
        .title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .row {
            margin: 8px 0;
        }
        .label {
            font-weight: bold;
        }
        .print-btn {
            text-align: center;
            margin-top: 20px;
        }
        button {
            padding: 8px 15px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="title">Order Receipt</div>

        <div class="row"><span class="label">Order ID:</span> <?= htmlspecialchars($order['id']); ?></div>
        <div class="row"><span class="label">Customer:</span> <?= htmlspecialchars($order['customer']); ?></div>
        <div class="row"><span class="label">Email:</span> <?= htmlspecialchars($order['gmail']); ?></div>
        <div class="row"><span class="label">Phone:</span> <?= htmlspecialchars($order['tel_number']); ?></div>
        <div class="row"><span class="label">Address:</span> <?= htmlspecialchars($order['address']); ?></div>
        
        <div class="row">
            <span class="label">Animal:</span>
            <?php
            $animals = json_decode($order['animal'], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($animals)) {
                $animalList = [];
                foreach ($animals as $animal) {
                    $name = $animal['name'] ?? 'Unknown';
                    $price = $animal['price'] ?? 'N/A';
                    $qty = $animal['qty'] ?? 1;
                    $animalList[] = htmlspecialchars($name) . ' - ' . htmlspecialchars($price) . ' (x' . htmlspecialchars($qty) . ')';
                }
                echo implode('<br>', $animalList);
            } else {
                echo htmlspecialchars($order['animal']);
            }
            ?>
        </div>

        <div class="row"><span class="label">Total:</span> $<?= number_format($order['total'], 2); ?></div>
        <div class="row"><span class="label">Payment Status:</span> <?= htmlspecialchars($order['payment_status']); ?></div>
        <div class="row"><span class="label">Order Status:</span> <?= htmlspecialchars($order['order_status']); ?></div>
        <div class="row"><span class="label">Date:</span> <?= htmlspecialchars($order['date']); ?></div>

        <div class="print-btn">
            <button onclick="window.print()">🖨 Print Receipt</button>
        </div>
    </div>
</body>
</html>
