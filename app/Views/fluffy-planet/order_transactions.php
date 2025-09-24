<?php
// Include database connection
require_once 'db.php';
?>

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
            <h2>Order Transaction</h2>
            <table>
                <tr>
                    <td>Order Number:</td>
                    <td id="orderNumber">00001</td>
                </tr>
                <tr>
                    <td>Customer:</td>
                    <td id="customerName">---</td>
                </tr>
                <tr>
                    <td>Tel Number:</td>
                    <td id="customerTel">---</td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td id="customerEmail">---</td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td id="customerAddress">---</td>
                </tr>
                <tr>
                    <td>Date:</td>
                    <td id="orderDate">---</td>
                </tr>
                <tr>
                    <td>Payment:</td>
                    <td id="paymentMethod">---</td>
                </tr>
                <tr>
                    <td>Total:</td>
                    <td id="orderTotal">$0.00</td>
                </tr>
            </table>
            <!-- Status: Only show if orderData exists -->
            <div class="total" id="orderStatus" style="display: none;">Status: Processing</div>
            
        </div>

        <!-- Confirm Button -->
        <button class="download-btn" id="confirmBtn">Confirm</button>
        
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const orderData = JSON.parse(localStorage.getItem("orderData"));

            function formatOrderNumber(num) {
                return num.toString().padStart(5, '0');
            }

            let lastOrderNumber = parseInt(localStorage.getItem("lastOrderNumber") || "0", 10);
            let newOrderNumber = lastOrderNumber + 1;
            localStorage.setItem("lastOrderNumber", newOrderNumber);

            const formattedOrderNumber = formatOrderNumber(newOrderNumber);

            document.getElementById("orderNumber").textContent = formattedOrderNumber;
            if (orderData) {
                document.getElementById("customerName").textContent = orderData.name || "---";
                document.getElementById("customerTel").textContent = orderData.tel || "---";
                document.getElementById("customerEmail").textContent = orderData.email || "---";
                document.getElementById("customerAddress").textContent = orderData.address || "---"; // ✅ ADDRESS
                document.getElementById("orderDate").textContent = orderData.date || "---";
                document.getElementById("paymentMethod").textContent = orderData.payment || "---";
                document.getElementById("orderTotal").textContent = orderData.total || "$0.00";

                // Show status only if orderData exists
                document.getElementById("orderStatus").style.display = "block";
            }

            document.getElementById("confirmBtn").addEventListener("click", function () {
                if (!orderData) {
                    alert("No order data to confirm!");
                    return;
                }

                const newHistory = {
                    orderNumber: formattedOrderNumber,
                    date: orderData.date || new Date().toLocaleDateString(),
                    customer: orderData.name || "Guest",
                    telNumber: orderData.tel || "---",
                    gmail: orderData.email || "---",
                    address: orderData.address || "---", // ✅ save address
                    items: orderData.items || "1 Item",
                    total: orderData.total || "$0.00",
                    payment: orderData.payment || "---",
                    paymentStatus: "Paid",
                    orderStatus: "Processing"
                };

                let history = JSON.parse(localStorage.getItem("orderHistory")) || [];
                history.push(newHistory);
                localStorage.setItem("orderHistory", JSON.stringify(history));

                localStorage.removeItem("orderData");
                document.getElementById("customerName").textContent = "---";
                document.getElementById("customerTel").textContent = "---";
                document.getElementById("customerEmail").textContent = "---";
                document.getElementById("customerAddress").textContent = "---"; // reset address
                document.getElementById("orderDate").textContent = "---";
                document.getElementById("paymentMethod").textContent = "---";
                document.getElementById("orderTotal").textContent = "$0.00";

                // Hide status after confirm
                document.getElementById("orderStatus").style.display = "none";

                alert("Order confirmed and added to history!");
                window.location.href = "<?= base_url('history') ?>";
            });
        });
    </script>
</body>

</html>
