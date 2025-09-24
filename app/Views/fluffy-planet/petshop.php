<?php
// Include database connection
require_once 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fluffy Planet</title>
    <style>
      body {
        margin: 0;
        font-family: "Segoe UI", sans-serif;
        background-color: #fff8f1;
        color: #2c2c2c;
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

      .home {
        text-decoration: underline;
        background-color: #eccfb2;
      }

      .search-box {
        background: #f2f2f2;
        padding: 5px 10px;
        border-radius: 5px;
      }

      .hero {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 50px;
        position: relative;
      }

      .hero-text {
        max-width: 50%;
      }

      .hero-text h1 {
        font-size: 48px;
        margin-bottom: 20px;
      }

      .hero-text p {
        font-size: 16px;
        margin-bottom: 30px;
        color: #555;
      }

      .hero-text button {
        background-color: #7b6b64;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 25px;
        cursor: pointer;
        font-weight: bold;
      }

      .hero img {
        height: 300px;
        border-radius: 20px;
        object-fit: cover;
      }

      .dog-img {
        position: absolute;
        right: 50px;
        bottom: 50px;
      }

      .hero-2 {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 50px;
        position: relative;
        height: 200px;
      }

      .buy_now:hover {
        background-color: #2c2c2c;
      }

      .buy_now:active {
        background-color: #535050;
        color: white;
      }

      @media (max-width: 768px) {
        .hero {
          flex-direction: column;
          text-align: center;
        }

        .hero-text {
          max-width: 100%;
        }

        .dog-img {
          position: static;
          margin-top: 20px;
        }
      }

      .search-btn {
        border: none;
      }

      .container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 60px;
        padding: 60px 100px;
        justify-items: center;
      }

      .best-seller {
        border: 1px solid black;
        height: 248px;
        width: 300px;
      }

      .buy {
        display: flex;
        margin-left: 43%;
        background-color: rgb(72, 160, 72);
        border: none;
        padding-right: 10px;
        padding-left: 10px;
        padding-top: 5px;
        padding-bottom: 5px;
        border-radius: 10%;
      }

      .cat-name {
        text-align: center;
        margin: 5px;
      }

      .h3 {
        text-align: center;
        background-color: rgb(245, 237, 237);
        padding: 10px;
      }

      .footer {
        text-align: left;
        margin-top: 15px;
        padding-left: 25px;
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
        width: 200px;
        height: 200px;
        object-fit: contain;
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
    </style>
  </head>

  <body>
    <header>
      <div class="logo">🐾 Fluffy Planet</div>
      <nav>
      <a href="<?= base_url('petshop') ?>" class="home">Home</a>
            <a href="<?= base_url('categories') ?>" class="categories">Categories</a>
            <a href="<?= base_url('newarrival') ?>">New Arrivals</a>
            <a href="<?= base_url('order') ?>">Order</a>
            <a href="<?= base_url('order_transactions') ?>">Order Transaction</a>
            <a href="<?= base_url('history') ?>">History</a>
      </nav>
      <div class="search-box">
        <input type="text" placeholder="Search a Breed..." /><button class="search-btn">Search</button>
      </div>
    </header>

    <section class="hero">
      <div class="hero-text">
        <h1>Welcome to Fluffy Planet</h1>
        <p>
          We are a trusted PetShop that offers a variety of healthy, well-cared for <br />
          animals including <b>Dogs</b>, <b>Kittens</b>, <b>Birds</b>, <b>Hamsters</b>, <b>Rabbits</b>, <b>Fish</b>. We
          help<br />
          you match you with the perfect pet for your lifestyle.
        </p>
        <a href="categories.php"><button class="buy_now">Buy Now</button></a>
      </div>

      <img src="./web/dogbg.png" class="dog-img" alt="Dog" />
    </section>
    <hr />
    <h1 class="h3">Best Seller</h1>

    <section class="hero-2">
      <div class="grid">
        <div class="card">
          <img src="./web/telapia1.jfif" alt="" class="cat" />
          <h4 class="cat-name">Telapia Cat</h4>
        </div>
        <div class="card">
          <img src="./web/Dog1.jfif" alt="" class="cat" />
          <h4 class="cat-name">Siberian Husky</h4>
        </div>
        <div class="card">
          <img src="./web/Bird1.jfif" alt="" class="cat" />
          <h4 class="cat-name">Blue & Yellow Macaw</h4>
        </div>
        <div class="card">
          <img src="./web/Hamster1.jfif" alt="" class="cat" />
          <h4 class="cat-name">Dwarf Hamster</h4>
        </div>
        <div class="card">
          <img src="./web/Fish1.jfif" alt="" class="cat" />
          <h4 class="cat-name">Koi Fish</h4>
        </div>
      </div>
    </section>
    <hr />
    <footer>
      <div class="footer">
        <h4>Developed by Jun Alexes Orao (2025)</h4>
      </div>
    </footer>
  </body>
</html>
