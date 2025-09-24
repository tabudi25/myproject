<?php
// Include database connection
require_once 'db.php';

if ($_POST) {
    $id = $_POST['order_id'];
    $date = $_POST['date'];
    $customer = $_POST['customer'];
    $gmail = $_POST['gmail'];
    $tel_number = $_POST['tel_number'];
    $animal = $_POST['animal'];
    $total = $_POST['total'];
    $address = $_POST['address']; // ✅ ADDRESS
    $payment_status = 'paid';
    $order_status = 'processing';
    
    $stmt = $conn->prepare("INSERT INTO orders (id, date, customer, gmail, tel_number, address, animal, total, payment_status, order_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssdss", $id, $date, $customer, $gmail, $tel_number, $address, $animal, $total, $payment_status, $order_status);
    
    if ($stmt->execute()) {
        echo "<script>alert('Order saved successfully!'); window.location.href = '<?= base_url('order_transactions') ?>';</script>";
    } else {
        echo "<script>alert('Error saving order: " . $conn->error . "');</script>";
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fluffy Planet - Checkout</title>
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
            display: flex;
            justify-content: center;
            gap: 40px;
            margin: 40px auto;
            max-width: 1100px;
            flex-wrap: wrap;
            padding: 0 20px;
        }

        .box {
            background-color: white;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            flex: 1;
            min-width: 320px;
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

        .half-input {
            border-radius: 20px;
            border: 1px solid #ccc;
            padding: 10px;
            margin: 8px 0;
            font-size: 14px;
            width: calc(50% - 14px);
            display: inline-block;
        }

        .payment-methods {
            text-align: center;
            margin: 20px 0;
        }

        .payment-methods button {
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            margin: 5px;
            cursor: pointer;
            background-color: #f5f5f5;
            font-weight: 500;
        }

        .payment-methods button.active {
            background-color: #eccfb2;
        }

        .confirm-btn {
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

        .delete-btn {
            background-color: crimson;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 5px 12px;
            cursor: pointer;
            font-size: 14px;
        }

        .delete-btn:hover {
            background-color: darkred;
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
            <a href="<?= base_url('order') ?>" class="order">Order</a>
            <a href="<?= base_url('order_transactions') ?>">Order Transaction</a>
            <a href="<?= base_url('history') ?>">History</a>
        </nav>
    </header>

    <div class="container">
        <!-- Pet List -->
        <div class="box">
            <h2>Pet List</h2>
            <table id="petTable"></table>
            <div class="total" id="totalAmount">Total: $0.00</div>
        </div>

        <!-- Payment Info -->
        <div class="box">
            <form id="orderForm" method="POST" action="">
                <h2>Payment Information</h2>
                <input type="hidden" name="order_id" id="orderId">
                <input type="hidden" name="animal" id="animalData">
                <input type="hidden" name="total" id="totalData">
                <input type="hidden" name="customer" id="customerName">
                <input type="text" name="first_name" class="half-input" placeholder="First Name" required>
                <input type="text" name="last_name" class="half-input" placeholder="Last Name" required>
                <input type="text" name="tel_number" class="half-input" placeholder="Phone Number" required>
                <input type="email" name="gmail" class="half-input" placeholder="Email" required>
                <input type="text" name="address" class="half-input" placeholder="Address" required> <!-- ✅ ADDRESS -->
                <input type="date" name="date" class="half-input" id="orderDate" required>

                <div class="payment-methods">
                    <button type="button" data-payment="COD">COD</button>
                    <button type="button" data-payment="G CASH">G CASH</button>
                    <button type="button" data-payment="Credit Card">Credit Card</button>
                </div>
                <button type="submit" class="confirm-btn">Confirm Payment</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const today = new Date().toISOString().split("T")[0];
            document.getElementById("orderDate").value = today;
            loadCart();
        });

        function loadCart() {
            const petTable = document.getElementById("petTable");
            const totalAmount = document.getElementById("totalAmount");

            let orderList = JSON.parse(localStorage.getItem("orderList")) || [];
            petTable.innerHTML = "";
            let total = 0;

            orderList.forEach((item, index) => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${item.name}</td>
                    <td>${item.price}</td>
                    <td><button class="delete-btn" onclick="deleteItem(${index})">Delete</button></td>
                `;
                petTable.appendChild(row);

                let priceNum = parseFloat(item.price.replace("$", ""));
                total += priceNum * item.qty; // ✅ still uses qty for calculations
            });

            totalAmount.textContent = "Total: $" + total.toFixed(2);
        }

        function deleteItem(index) {
            let orderList = JSON.parse(localStorage.getItem("orderList")) || [];
            orderList.splice(index, 1);
            localStorage.setItem("orderList", JSON.stringify(orderList));
            loadCart();
        }

        document.querySelectorAll(".payment-methods button").forEach(btn => {
            btn.addEventListener("click", function () {
                document.querySelectorAll(".payment-methods button").forEach(b => b.classList.remove("active"));
                this.classList.add("active");
            });
        });

        document.getElementById('orderForm').addEventListener('submit', function(e) {
            e.preventDefault();
            confirmOrder();
        });

        function confirmOrder() {
            if (!confirm("Are you sure you want to proceed with this order?")) return;

            const firstName = document.querySelector('input[name="first_name"]').value.trim();
            const lastName = document.querySelector('input[name="last_name"]').value.trim();
            const fullName = firstName + " " + lastName;
            const phone = document.querySelector('input[name="tel_number"]').value.trim();
            const email = document.querySelector('input[name="gmail"]').value.trim();
            const address = document.querySelector('input[name="address"]').value.trim(); // ✅ ADDRESS
            const date = document.getElementById("orderDate").value;

            if (!firstName || !lastName || !phone || !email || !address || !date) {
                alert('Please fill in all required fields.');
                return;
            }

            const pets = JSON.parse(localStorage.getItem("orderList")) || [];
            const totalText = document.getElementById("totalAmount").innerText.replace("Total: $", "");
            const total = parseFloat(totalText);

            if (pets.length === 0) {
                alert('Please add some pets to your order first.');
                return;
            }

            const orderNumber = "FP" + Math.floor(100000 + Math.random() * 900000);
            const animalData = JSON.stringify(pets);

            document.getElementById('orderId').value = orderNumber;
            document.getElementById('customerName').value = fullName;
            document.getElementById('animalData').value = animalData;
            document.getElementById('totalData').value = total.toFixed(2);

            const orderData = {
                orderNumber,
                name: fullName,
                tel: phone,
                email: email,
                address: address, // ✅ save to localStorage
                date,
                payment: document.querySelector(".payment-methods button.active")?.innerText || "COD",
                items: pets.length + " Item(s)",
                total: "$" + total.toFixed(2)
            };

            localStorage.setItem("orderData", JSON.stringify(orderData));
            localStorage.removeItem("orderList");
            document.getElementById('orderForm').submit();
        }
    </script>
</body>
</html>
