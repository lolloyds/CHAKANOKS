<?php /* Standalone login page (no shared header/sidebar) */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Chakanoks</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      background: url("<?= base_url('login.png'); ?>") no-repeat center center;
      background-size: 600px;
    }
    .login-container {
      width: 300px;
      margin: 120px auto;
      padding: 20px;
      background: rgba(255, 255, 255, 0.7);
      border-radius: 10px;
      text-align: center;
      box-shadow: 0px 0px 10px rgba(0,0,0,0.3);
    }
    input {
      width: 90%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    button {
      width: 100%;
      padding: 10px;
      background: orange;
      border: none;
      border-radius: 5px;
      color: white;
      font-size: 16px;
      cursor: pointer;
      margin-top: 10px;
    }
    button:hover { background: darkorange; }
    .register-link {
      margin-top: 15px;
      font-size: 14px;
    }
    .register-link a {
      color: #4CAF50;
      text-decoration: none;
      font-weight: bold;
    }
    .register-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Login</h2>
    <form action="<?= base_url('/dashboard'); ?>">
      <input type="text" placeholder="Username" required><br>
      <input type="password" placeholder="Password" required><br>
      <button type="submit">Login</button>
    </form>

    <!-- Register text link -->
    <div class="register-link">
      Don’t have an account? <a href="<?= base_url('/register'); ?>">Register</a>
    </div>
  </div>
</body>
</html>
