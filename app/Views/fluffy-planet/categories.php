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

      .categories {
        text-decoration: underline;
        background-color: #eccfb2;
      }

      .about:active {
        background-color: #333;

        color: white;
      }

      .search-box {
        background: #f2f2f2;
        padding: 5px 10px;
        border-radius: 5px;
      }

      .container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 40px;
        padding: 60px 70px;
        justify-items: center;
      }

      .animal-button {
        border: 1px solid #000;
        border-radius: 25px;
        padding: 20px;
        width: 380px;
        background-color: #fff;
        cursor: pointer;
        transition: transform 0.2s ease;
      }

      .animal-button:hover {
        transform: scale(1.05);
        box-shadow: 5px 5px 20px 1px rgb(83, 91, 94);
      }

      .animal-button img {
        height: 150px;
      }

      .animal-button h2 {
        margin: 10px 0 5px;
      }

      .animal-button p {
        margin: 0;
        font-style: italic;
        font-size: 16px;
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
        <a href="<?= base_url('petshop') ?>">Home</a>
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

    <div class="container">
      <a href="cat.html">
        <button class="animal-button">
          <img src="./web/cat.png" alt="Cat" />
          <h2>Cat</h2>
          <p class="quote">“A home without cat is just a house”</p>
        </button></a
      >

      <a href="dog.html"
        ><button class="animal-button">
          <img src="./web/dog.png" alt="Dog" />
          <h2>Dog</h2>
          <p class="quote">“Happiness is a warm puppy”</p>
        </button></a
      >

      <a href="bird.html"
        ><button class="animal-button">
          <img src="./web/bird.png" alt="Bird" />
          <h2>Bird</h2>
          <p class="quote">“The sound of bird is the voice of nature’s joy”</p>
        </button></a
      >

      <a href="hamster.html"
        ><button class="animal-button">
          <img src="./web/hamster.png" alt="Hamster" />
          <h2>Hamster</h2>
          <p class="quote">“Small paws big heart”</p>
        </button></a
      >

      <a href="rabbit.html"
        ><button class="animal-button">
          <img src="./web/rabbit.png" alt="Rabbit" />
          <h2>Rabbit</h2>
          <p class="quote">“Somebunny loves you”</p>
        </button></a
      >

      <a href="fish.html"
        ><button class="animal-button">
          <img src="./web/fish.png" alt="Fish" />
          <h2>Fish</h2>
          <p class="quote">“Just keep swimming”</p>
        </button></a
      >
    </div>
  </body>
</html>
