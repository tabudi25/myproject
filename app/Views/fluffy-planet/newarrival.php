<?php
// Include database connection
require_once 'db.php';
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <title>Fluffy Planet</title>
    <style>
      body {
        margin: 0;
        font-family: "Segoe UI", sans-serif;
        background-color: #fff8f1;
        color: #2c2c2c;
      }

      /* HEADER */
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

      .newarrival {
        text-decoration: underline;
        background-color: #eccfb2;
      }

      .search-box {
        background: #f2f2f2;
        padding: 5px 10px;
        border-radius: 5px;
      }

      /* CATEGORY TITLE */
      .breed {
        text-align: center;
        font-size: 30px;
        font-weight: bold;
        margin: 20px 50px 10px;
      }

      /* PRODUCT GRID */
      .grid {
        display: grid;
        grid-template-columns: repeat(5, 2fr);
        gap: 20px;
        padding: 20px 50px;
        background-color: #fff1e6;
        border-radius: 5px;
        margin: 0 50px 30px;
      }

      .card {
        background-color: white;
        border: 1px solid #ccc;
        border-radius: 8px;
        text-align: center;
        padding: 10px;
        position: relative;
      }

      .card img {
        width: 80px;
        height: 80px;
        object-fit: contain;
      }

      .buy-btn {
        display: inline-block;
        margin-top: 10px;
        padding: 8px 20px;
        background-color: limegreen;
        color: white;
        font-weight: bold;
        border-radius: 25px;
        text-decoration: none;
        cursor: pointer;
      }

      .buy-btn:hover {
        background-color: rgb(57, 236, 57);
      }

      .prices {
        margin-left: 7px;
        margin-top: 5px;
        color: green;
        font-size: 14px;
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

      /* CONFIRMATION MODAL */
      .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        align-items: center;
        justify-content: center;
      }

      .modal-content {
        background: white;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
        width: 300px;
      }

      .modal-content p {
        margin-bottom: 20px;
        font-size: 16px;
      }

      .modal-content button {
        margin: 0 10px;
        padding: 8px 16px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
      }

      .cancel-btn {
        background-color: #ccc;
      }

      .continue-btn {
        background-color: limegreen;
        color: white;
      }
    </style>
  </head>

  <body>
    <header>
      <div class="logo">🐾 Fluffy Planet</div>
      <nav>
        <a href="<?= base_url('petshop') ?>">Home</a>
        <a href="<?= base_url('categories') ?>" class="categories">Categories</a>
        <a href="<?= base_url('newarrival') ?>" class="newarrival">New Arrivals</a>
        <a href="<?= base_url('order') ?>">Order</a>
        <a href="<?= base_url('order_transactions') ?>">Order Transaction</a>
        <a href="<?= base_url('history') ?>">History</a>
      </nav>
      <div class="search-box">
        <input type="text" placeholder="Search a Breed..." /><button
          class="search-btn"
        >
          Search
        </button>
      </div>
    </header>

    <h2 class="breed">Cat</h2>
    <div class="grid" data-category="Cat"></div>

    <h2 class="breed">Rabbit</h2>
    <div class="grid" data-category="Rabbit"></div>

    <h2 class="breed">Dog</h2>
    <div class="grid" data-category="Dog"></div>

    <!-- CONFIRMATION MODAL -->
    <div class="modal" id="confirmModal">
      <div class="modal-content">
        <p id="confirmText">Are you sure you want to buy this?</p>
        <button class="cancel-btn" id="cancelBtn">Cancel</button>
        <button class="continue-btn" id="continueBtn">Continue</button>
      </div>
    </div>

    <!-- ORDER LOGIC -->
    <script>
      let selectedItem = null;

      function renderPets() {
        const storedPets = JSON.parse(localStorage.getItem("petList")) || {
          Cat: [
            { name: "Pookie", price: "100.0", image: "./web/telapia3.jfif" },
          ],
          Rabbit: [
            { name: "Pookie", price: "100.0", image: "./web/rabbit1.jfif" },
          ],
          Dog: [
            { name: "Pookie", price: "100.0", image: "./web/dog1.jfif" },
          ],
        };

        document.querySelectorAll(".grid").forEach((grid) => {
          grid.innerHTML = ""; // Clear all cards
        });

        for (const category in storedPets) {
          const grid = document.querySelector(`.grid[data-category="${category}"]`);
          storedPets[category].forEach((pet) => {
            const card = document.createElement("div");
            card.className = "card";
            card.innerHTML = `
              <img src="${pet.image}" alt="${pet.name}" />
              <p>${pet.name}<span class="prices">$${pet.price}</span></p>
              <a href="#" class="buy-btn">Buy</a>
            `;
            grid.appendChild(card);
          });
        }

        attachBuyEvents();
      }

      function attachBuyEvents() {
        document.querySelectorAll(".buy-btn").forEach((button) => {
          button.onclick = function (e) {
            e.preventDefault();
            const card = this.closest(".card");
            const productName = card.querySelector("p").childNodes[0].textContent.trim();
            const productPrice = card.querySelector(".prices").textContent.trim();

            selectedItem = { name: productName, price: productPrice, qty: 1 };

            document.getElementById("confirmText").innerText =
              `Are you sure you want to buy ${productName} for ${productPrice}?`;
            document.getElementById("confirmModal").style.display = "flex";
          };
        });
      }

      document.getElementById("cancelBtn").onclick = () => {
        document.getElementById("confirmModal").style.display = "none";
        selectedItem = null;
      };

      document.getElementById("continueBtn").onclick = () => {
        if (selectedItem) {
          let order = JSON.parse(localStorage.getItem("orderList")) || [];
          order.push(selectedItem);
          localStorage.setItem("orderList", JSON.stringify(order));
          document.getElementById("confirmModal").style.display = "none";
          window.location.href = "<?= base_url('order') ?>";
        }
      };

      renderPets();
    </script>
  </body>
</html>
