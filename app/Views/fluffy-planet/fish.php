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

    <h1 class="breed">Koi Fish</h1>
    <div class="grid">
      <div class="card">
        <img src="./web/Fish1.jfif" alt="Pookie" />
        <p>Pookie <span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/fish2.jfif" alt="Bochi" />
        <p>Bochi<span class="prices">$900.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/fish3.jfif" alt="Pochi" />
        <p>Pochi <span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/fish4.jfif" alt="Chokie" />
        <p>Chokie<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/fish5.jfif" alt="Chogi" />
        <p>Chogi <span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>
      <div class="card">
        <img src="./web/fish6.jfif" alt="Pan-pan" />
        <p>Pan-pan<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/fish7.jfif" alt="Bochi" />
        <p>Chingkie<span class="prices">$900.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/fish8.jfif" alt="Pookie" />
        <p>Beepo<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/fish9.jfif" alt="Pookie" />
        <p>Whity<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/fish10.jfif" alt="Pookie" />
        <p>Koko<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>
    </div>
    <h1 class="breed">Betta Fish</h1>
    <div class="grid">
      <div class="card">
        <img src="./web/fish11.jfif" alt="Pookie" />
        <p>Pookie<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/fish12.jfif" alt="Bochi" />
        <p>Bochi<span class="prices">$900.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/fish13.jfif" alt="Pookie" />
        <p>Pookie<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/fish14.jfif" alt="Pookie" />
        <p>Pookie<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/fish15.jfif" alt="Pookie" />
        <p>Pookie<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>
      <div class="card">
        <img src="./web/fish16.jfif" alt="Pookie" />
        <p>Pookie<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/fish17.jfif" alt="Bochi" />
        <p>Bochi<span class="prices">$900.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/fish18.jfif" alt="Pookie" />
        <p>Pookie<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/fish19.jfif" alt="Pookie" />
        <p>Pookie <span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>

      <div class="card">
        <img src="./web/fish20.png" alt="Pookie" />
        <p>Pookie<span class="prices">$100.0</span></p>
        <a href="#" class="buy-btn">Buy</a>
      </div>
    </div>
  </body>
</html>
