
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fluffy Planet - Checkout</title>
    <link rel="stylesheet" href="./web/order.css">
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
            <a href="<?= base_url('order') ?>" class="order">Order</a>
            <a href="<?= base_url('order_transactions') ?>">Order Transaction</a>
            <a href="<?= base_url('history') ?>">History</a>
        </nav>
    </header>

    <div class="container">
        <!-- Pet List -->
        <div class="box">
            <h2>Pet Lists</h2>
            <table id="petTable"></table>
            <div class="total" id="totalAmount">Total: $0.00</div>

            <div id="continueShoppingBtn" style="text-align: center; margin-top: 15px;">
        <button onclick="continueShopping()" 
            style="
                background-color: #f1c40f; 
                color: white; 
                font-weight: bold; 
                border: none; 
                border-radius: 20px; 
                padding: 10px 25px; 
                cursor: pointer;
            ">
            Continue Shopping
        </button>
    </div>
        </div>

        <!-- Payment Info -->
        <div class="box">
            <form id="orderForm" method="POST" action="<?= base_url('order') ?>">
                <h2>Payment Information</h2>
                <input type="hidden" name="order_id" id="orderId">
                <input type="hidden" name="animal_test" id="animalData">
                <input type="hidden" name="total" id="totalData">
                <input type="hidden" name="customer" id="customerName">
                <input type="hidden" name="payment_method" id="paymentMethod">
                <input type="text" name="first_name" class="half-input" placeholder="First Name" required>
                <input type="text" name="last_name" class="half-input" placeholder="Last Name" required>
                <input type="text" name="tel_number_test" class="half-input" placeholder="Phone Number" required>
                <input type="email" name="gmail_test" class="half-input" placeholder="Email" required>
                <input type="text" name="address_test" class="half-input" placeholder="Address" required> <!-- ✅ ADDRESS -->
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
            const continueBtn = document.getElementById("continueShoppingBtn");

            let orderList = JSON.parse(localStorage.getItem("orderList")) || [];
            petTable.innerHTML = "";
            let total = 0;
        if (orderList.length === 0) {
            continueBtn.style.display = "none"; // ✅ hide when empty
        } else {
            continueBtn.style.display = "block"; // ✅ show when not empty
        }

            orderList.forEach((item, index) => {
                const row = document.createElement("tr");
                const animalType = item.type ? `(${item.type})` : '';
                row.innerHTML = `
                    <td>${item.name} ${animalType}</td>
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
            const phone = document.querySelector('input[name="tel_number_test"]').value.trim();
            const email = document.querySelector('input[name="gmail_test"]').value.trim();
            const address = document.querySelector('input[name="address_test"]').value.trim(); // ✅ ADDRESS
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
            const paymentMethod = document.querySelector(".payment-methods button.active")?.innerText || "COD";

            document.getElementById('orderId').value = orderNumber;
            document.getElementById('customerName').value = fullName;
            document.getElementById('animalData').value = animalData;
            document.getElementById('totalData').value = total.toFixed(2);
            document.getElementById('paymentMethod').value = paymentMethod;

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
        function continueShopping() {
    window.location.href = "<?= base_url('categories') ?>"; 
}
    </script>
</body>
</html>
