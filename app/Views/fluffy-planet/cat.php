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

      /* ===== Missing Modal Styles Added ===== */
      #confirmModal {
        display: none;
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.5);
        align-items: center;
        justify-content: center;
        z-index: 999;
      }

      .modal-content {
        background: white;
        padding: 20px 30px;
        border-radius: 10px;
        text-align: center;
        width: 300px;
      }

      .modal-content p {
        margin-bottom: 20px;
      }

      .modal-buttons {
        display: flex;
        justify-content: space-around;
      }

      .modal-buttons button {
        padding: 8px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
      }

      #cancelBtn {
        background-color: #bbb;
      }

      #continueBtn {
        background-color: limegreen;
        color: white;
      }

      #cancelBtn:hover {
        background-color: #999;
      }

      #continueBtn:hover {
        background-color: rgb(57, 236, 57);
      }
    </style>
  </head>

  <body>
    <header>
      <div class="logo">🐾 Fluffy Planet</div>
      <nav>
        <a href="<?= base_url('petshop') ?>" class="home">Home</a>
        <a href="<?= base_url('categories') ?>" class="categories">Categories</a>
        <a href="<?= base_url('newarrival') ?>">New Arrivals</a>
        <a href="order.php">Order</a>
        <a href="order_transactions.php">Order Transaction</a>
        <a href="order.php">History</a>
      </nav>
      <div class="search-box">
        <input type="text" placeholder="Search a Breed..." />
        <button class="search-btn">Search</button>
      </div>
    </header>

    <h1 class="breed">Telapia Cat</h1>
    <div class="grid" data-category="Cat">
      <div class="card">
        <img src="./web/telapia1.jfif" alt="Pookie" />
        <p>Pookie<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/telapia2.jfif" alt="Bochi" />
        <p>Bochi<span class="prices">$900.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/telapia3.jfif" alt="Pochi" />
        <p>Pochi <span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/telapia4.jfif" alt="Chokie" />
        <p>Chokie<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/telapia5.jfif" alt="Chogi" />
        <p>Chogi <span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>
      <div class="card">
        <img src="./web/telapia6.jfif" alt="Pan-pan" />
        <p>Pan-pan <span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/telapia7.jfif" alt="Bochi" />
        <p>Chingkie<span class="prices">$900.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/telapia8.jfif" alt="Pookie" />
        <p>Beepo<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/telapia9.jfif" alt="Pookie" />
        <p>Whity<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/telapia10.jfif" alt="Pookie" />
        <p>Koko <span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>
    </div>

    <h1 class="breed">Persian Cat</h1>
    <div class="grid">
      <div class="card">
        <img src="./web/persian1.jfif" alt="Pookie" />
        <p>Pookie<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/persian2.jfif" alt="Bochi" />
        <p>Bochi<span class="prices">$900.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/persian3.jfif" alt="Pookie" />
        <p>Pookie<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/persian4.jfif" alt="Pookie" />
        <p>Pookie <span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/persian5.jfif" alt="Pookie" />
        <p>Pookie<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>
      <div class="card">
        <img src="./web/persian7.jfif" alt="Pookie" />
        <p>Pookie<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/persian8.jfif" alt="Bochi" />
        <p>Bochi<span class="prices">$900.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/persian9.jfif" alt="Pookie" />
        <p>Pookie<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>
        
      <div class="card">
        <img src="./web/persian10.jfif" alt="Pookie" />
        <p>Pookie<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/persian6.jfif" alt="Pookie" />
        <p>Pookie <span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>
    </div>

    <!-- ===== Added Confirmation Modal ===== -->
    <div id="confirmModal">
      <div class="modal-content">
        <p id="confirmText">Are you sure you want to buy this item?</p>
        <div class="modal-buttons">
          <button id="cancelBtn">Cancel</button>
          <button id="continueBtn">Continue</button>
        </div>
      </div>
    </div>
  </body>

  <script>
    let selectedItem = null;

    function renderPets() {
      const storedPets = JSON.parse(localStorage.getItem("petList")) || {
        Cat: [{ name: "Pookie", price: "100.0", image: "./web/telapia3.jfif" }],
        Rabbit: [{ name: "Pookie", price: "100.0", image: "./web/rabbit1.jfif" }],
        Dog: [{ name: "Pookie", price: "100.0", image: "./web/dog1.jfif" }],
      };

      document.querySelectorAll(".grid").forEach((grid) => {
        grid.innerHTML = ""; // Clear all cards
      });

      for (const category in storedPets) {
        const grid = document.querySelector(`.grid[data-category="${category}"]`);
        if (!grid) continue;
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
          const grid = card.closest(".grid");
          const animalType = grid.getAttribute("data-category") || "Cat";
          const productName = card.querySelector("p").childNodes[0].textContent.trim();
          const productPrice = card.querySelector(".prices").textContent.trim();

          selectedItem = {
            name: productName,
            price: productPrice,
            qty: 1,
            type: animalType,
          };

          document.getElementById("confirmText").innerText =
            `Are you sure you want to buy ${productName} (${animalType}) for ${productPrice}?`;
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

    attachBuyEvents();
  </script>
</html>
