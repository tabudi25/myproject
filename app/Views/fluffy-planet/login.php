<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Fluffy Planet</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f2f2f2;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .login-box {
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      width: 350px;
      box-shadow: 0px 0px 10px rgba(0,0,0,0.2);
    }
    h2 { text-align: center; }
    input, select {
      width: 100%;
      padding: 10px;
      margin: 8px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    button {
      width: 100%;
      padding: 10px;
      background: #2196F3;
      border: none;
      color: white;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover {
      background: #1976D2;
    }
    .link {
      text-align: center;
      margin-top: 10px;
    }
  </style>
</head>
<body>

<div class="login-box">
  <h2>Login</h2>

  <?php if(session()->getFlashdata('msg')): ?>
    <?php 
    $msg = session()->getFlashdata('msg');
    $isSuccess = strpos($msg, 'Account created') !== false || strpos($msg, 'Welcome') !== false;
    $isError = strpos($msg, 'Please login') !== false || strpos($msg, 'Wrong') !== false || strpos($msg, 'not found') !== false;
    ?>
    <p style="<?= $isSuccess ? 'color: green; background: #e6ffe6; border: 1px solid #99ff99;' : ($isError ? 'color: red; background: #ffe6e6; border: 1px solid #ff9999;' : 'color: orange; background: #fff3e6; border: 1px solid #ffcc99;') ?> padding: 10px; border-radius: 5px;">
      <?= $msg ?>
    </p>
  <?php endif; ?>

  <form action="<?= base_url('loginAuth') ?>" method="post">
    <input type="email" name="email" placeholder="Enter Email" required>
    <input type="password" name="password" placeholder="Enter Password" required>
    <button type="submit">Login</button>
  </form>

  <div class="link">
    <p>Don’t have an account? <a href="<?= base_url('signup') ?>">Sign Up</a></p>
  </div>
</div>

</body>
</html>
