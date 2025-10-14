<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fluffy Planet</title>
    <link rel="stylesheet" href="./web/categories.css" />
    <style>
      
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
      <a href="<?= base_url('cat') ?>">
        <button class="animal-button">
          <img src="./web/cat.png" alt="Cat" />
          <h2>Cat</h2>
          <p class="quote">“A home without cat is just a house”</p>
        </button></a
      >

      <a href="<?= base_url('dog') ?>"
        ><button class="animal-button">
          <img src="./web/dog.png" alt="Dog" />
          <h2>Dog</h2>
          <p class="quote">“Happiness is a warm puppy”</p>
        </button></a
      >

      <a href="<?= base_url('bird') ?>"
        ><button class="animal-button">
          <img src="./web/bird.png" alt="Bird" />
          <h2>Bird</h2>
          <p class="quote">“The sound of bird is the voice of nature’s joy”</p>
        </button></a
      >

      <a href="<?= base_url('hamster') ?>"
        ><button class="animal-button">
          <img src="./web/hamster.png" alt="Hamster" />
          <h2>Hamster</h2>
          <p class="quote">“Small paws big heart”</p>
        </button></a
      >

      <a href="<?= base_url('rabbit') ?>"
        ><button class="animal-button">
          <img src="./web/rabbit.png" alt="Rabbit" />
          <h2>Rabbit</h2>
          <p class="quote">“Somebunny loves you”</p>
        </button></a
      >

      <a href="<?= base_url('fish') ?>"
        ><button class="animal-button">
          <img src="./web/fish.png" alt="Fish" />
          <h2>Fish</h2>
          <p class="quote">“Just keep swimming”</p>
        </button></a
      >
    </div>
  </body>
</html>
