<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <link rel="stylesheet" href="./web/newarrivals.css" />
    <title>Fluffy Planet</title>
    <style>
      
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
            const grid = card.closest(".grid");
            const animalType = grid.getAttribute("data-category");
            const productName = card.querySelector("p").childNodes[0].textContent.trim();
            const productPrice = card.querySelector(".prices").textContent.trim();

            selectedItem = { 
              name: productName, 
              price: productPrice, 
              qty: 1,
              type: animalType 
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

      renderPets();
    </script>
  </body>
</html>
