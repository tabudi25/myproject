<?php
// Include database connection
require_once 'db.php';
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
      body {
        font-family: Arial, sans-serif;
        background-color: #fff8f1;
        margin: 0;
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

      nav a {
        margin: 0 15px;
        text-decoration: none;
        color: #333;
        font-weight: 500;
        padding: 5px;
        border-radius: 10px;
      }

      nav a:hover {
        background-color: #7c7a78;
      }

      nav a:active {
        background-color: #bbb5b2;
      }

      .search-box {
        background: #f2f2f2;
        padding: 5px 10px;
        border-radius: 5px;
      }

      .grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        grid-template-rows: repeat(2, auto);
        gap: 20px;
        padding: 20px;
      }

      .card {
        background-color: white;
        border: 1px solid #ccc;
        border-radius: 8px;
        text-align: center;
        padding: 10px;
      }

      .card img {
        width: 100px;
        height: 100px;
        object-fit: contain;
      }

      .buy-btn {
        display: inline-block;
        margin-top: 10px;
        padding: 10px 20px;
        background-color: limegreen;
        color: white;
        font-weight: bold;
        border-radius: 25px;
        text-decoration: none;
      }

      .buy-btn:hover {
        background-color: rgb(57, 236, 57);
      }

      .price {
        display: block;
        margin-top: 5px;
        color: green;
        font-size: 14px;
      }

      .breed {
        text-align: center;
        background-color: rgb(245, 237, 237);
        padding-top: 7px;
        padding-bottom: 7px;
      }

      .search-box input {
        border: none;
        outline: none;
        background: transparent;
        font-size: 14px;
        flex: 1;
      }

      .search-btn {
        border: none;
        background: transparent;
        font-weight: 600;
        cursor: pointer;
        padding: 4px 8px;
      }

      .search-btn:hover {
        color: blue;
      }

      .search-btn:active {
        color: rgb(0, 82, 0);
      }

      .categories {
        text-decoration: underline;
        background-color: #eccfb2;
      }

      .prices {
        margin-left: 7px;
        margin-top: 5px;
        color: green;
        font-size: 14px;
      }
    </style>
  </head>

  <body>
    <header>
      <div class="logo">🐾 Fluffy Planet</div>
      <nav>
        <a href="petshop.php" class="home">Home</a>
        <a href="categories.php" class="categories">Categories</a>
        <a href="newarrival.php">New Arrivals</a>
        <a href="order.php">Order</a>
        <a href="order_transactions.php">Order Transaction</a>
        <a href="order.php">History</a>
      </nav>
      <div class="search-box">
        <input type="text" placeholder="Search a Breed..." /><button class="search-btn">Search</button>
      </div>
    </header>

    <h1 class="breed">Netherland Dwarf rabbit</h1>
    <div class="grid">
      <div class="card">
        <img src="./web/rabbit1.jfif" alt="Pookie" />
        <p>Pookie<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/rabbit2.jfif" alt="Bochi" />
        <p>Bochi<span class="prices">$900.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/rabbit3.jfif" alt="Pochi" />
        <p>Pochi<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/rabbit4.jfif" alt="Chokie" />
        <p>Chokie<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/rabbit5.jfif" alt="Chogi" />
        <p>Chogi<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>
      <div class="card">
        <img src="./web/rabbit6.jfif" alt="Pan-pan" />
        <p>Pan-pan<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/rabbit7.jfif" alt="Bochi" />
        <p>Chingkie<span class="prices">$900.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/rabbit8.jfif" alt="Pookie" />
        <p>Beepo<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/rabbit9.jfif" alt="Pookie" />
        <p>Whity<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/rabbit10.jfif" alt="Pookie" />
        <p>Koko<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>
    </div>
    <h1 class="breed">Mini Lop Rabbit</h1>
    <div class="grid">
      <div class="card">
        <img src="./web/rabbit11.jfif" alt="Pookie" />
        <p>Pookie<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/rabbit12.jfif" alt="Bochi" />
        <p>Bochi<span class="prices">$900.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/rabbit13.jfif" alt="Pookie" />
        <p>Pookie <span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <!-- Repeat more cards to fill rows -->
      <div class="card">
        <img src="./web/rabbit14.jfif" alt="Pookie" />
        <p>Pookie<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/rabbit15.jfif" alt="Pookie" />
        <p>Pookie<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>
      <div class="card">
        <img src="./web/rabbit16.jfif" alt="Pookie" />
        <p>Pookie<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/rabbit17.jfif" alt="Bochi" />
        <p>Bochi<span class="prices">$900.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/rabbit18.jfif" alt="Pookie" />
        <p>Pookie<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <!-- Repeat more cards to fill rows -->
      <div class="card">
        <img src="./web/rabbit19.jfif" alt="Pookie" />
        <p>Pookie<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/rabbit20.jfif" alt="Pookie" />
        <p>Pookie <span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>
    </div>

    <script>
      let selectedItem = null; // store clicked item

      document.querySelectorAll(".buy-btn").forEach((button) => {
        button.addEventListener("click", function (e) {
          e.preventDefault();

          // Find the product card
          const card = this.closest(".card");
          const productName = card.querySelector("p").childNodes[0].textContent.trim();
          const productPrice = card.querySelector(".prices").textContent.trim();

          selectedItem = {
            name: productName,
            price: productPrice,
            qty: 1,
          };

          // Update modal text with pet name
          document.getElementById(
            "confirmText"
          ).innerText = `Are you sure you want to buy ${productName} for ${productPrice}?`;

          // Show modal
          document.getElementById("confirmModal").style.display = "flex";
        });
      });

      // Cancel button
      document.getElementById("cancelBtn").addEventListener("click", function () {
        document.getElementById("confirmModal").style.display = "none";
        selectedItem = null;
      });

      // Continue button
      document.getElementById("continueBtn").addEventListener("click", function () {
        if (selectedItem) {
          let order = JSON.parse(localStorage.getItem("orderList")) || [];
          order.push(selectedItem);
          localStorage.setItem("orderList", JSON.stringify(order));

          // Hide modal and go to order page
          document.getElementById("confirmModal").style.display = "none";
          window.location.href = "order.php";
        }
      });
    </script>
  </body>
</html>
