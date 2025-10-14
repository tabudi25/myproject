<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Signup - Fluffy Planet</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f2f2f2;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .signup-box {
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
      background: #4CAF50;
      border: none;
      color: white;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover {
      background: #388E3C;
    }
    .link {
      text-align: center;
      margin-top: 10px;
    }
  </style>
</head>
<body>

<div class="signup-box">
  <h2>Create Account</h2>

  <?php if(session()->getFlashdata('msg')): ?>
    <p style="color:red; background: #ffe6e6; padding: 10px; border-radius: 5px; border: 1px solid #ff9999;"><?= session()->getFlashdata('msg') ?></p>
  <?php endif; ?>

  <form action="/register" method="post">
    <input type="text" name="name" placeholder="Name" value="<?= old('name') ?>" required minlength="2" maxlength="255">
    <input type="email" name="email" placeholder="Email" value="<?= old('email') ?>" required>
    <input type="password" name="password" placeholder="Password (min 6 characters)" required minlength="6">
    <select name="role" required>
        <option value="">Select Role</option>
        <option value="customer" <?= old('role') == 'customer' ? 'selected' : '' ?>>Customer</option>
        <option value="admin" <?= old('role') == 'admin' ? 'selected' : '' ?>>Admin</option>
        <option value="staff" <?= old('role') == 'staff' ? 'selected' : '' ?>>Staff</option>
    </select>
    <button type="submit">Sign Up</button>
</form>


  <div class="link">
    <p>Already have an account? <a href="<?= base_url('login') ?>">Login</a></p>
  </div>
</div>

</body>
</html>
