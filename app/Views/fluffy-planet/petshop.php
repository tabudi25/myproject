  <!DOCTYPE html>
  <html lang="en">
    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Fluffy Planet</title>
      <link rel="stylesheet" href="./web/petshop.css" />
    </head>

    <body>
      <header>
        <div class="logo">🐾 Fluffy Planet</div>
        <nav>
        <a href="<?= base_url('petshop') ?>" class="home">Home</a>
              <a href="<?= base_url('categories') ?>" >Categories</a>
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
          <h1>Welcome to Fluffy Planet<?php if (session()->get('isLoggedIn')): ?> - Welcome back, <?= session()->get('user_name') ?>!<?php endif; ?></h1>
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
