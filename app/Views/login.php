<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - SCMS</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: "Poppins", sans-serif;
      background: #fbeaea; 
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 70px; 
    }

    .logo {
      max-width: 350px; 
      border-radius: 20px;
      background: #fff;
      padding: 20px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
      transition: 0.3s;
    }

    .logo:hover {
      transform: scale(1.05);
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
      cursor: pointer;
    }

    .login-box {
      width: 420px;  
      background: #fff6f2; 
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 6px 25px rgba(0,0,0,0.15);
      text-align: center;
      border: 2px solid #e8c6b4; 
    }

    .login-box h2 {
      margin-bottom: 25px;
      font-weight: bold;
      color: #5c4033; 
      font-size: 32px; 
    }

    .login-box label {
      display: block;
      text-align: left;
      margin-bottom: 8px;
      font-size: 15px;
      color: #5c4033;
      font-weight: 500;
    }

    .login-box input[type="text"],
    .login-box input[type="password"] {
      width: 100%;
      padding: 14px;
      margin-bottom: 20px;
      border: 1px solid #d9b7a5;
      border-radius: 10px;
      font-size: 15px;
      background: #fff;
      transition: 0.3s;
    }

    .login-box input:focus {
      border-color: #a97463;
      outline: none;
      box-shadow: 0 0 8px rgba(169, 116, 99, 0.3);
    }

    .options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      font-size: 14px;
    }

    .options label {
      display: flex;
      align-items: center;
      gap: 6px;
      cursor: pointer;
      color: #5c4033;
    }

    .options a {
      color: #a97463;
      text-decoration: none;
      font-weight: 500;
      transition: 0.3s;
    }

    .options a:hover {
      color: #8b5c4c;
      text-decoration: underline;
    }

    .login-box button {
      width: 100%;
      padding: 14px;
      background: #a97463;
      border: none;
      color: white;
      font-size: 18px;
      font-weight: bold;
      border-radius: 12px;
      cursor: pointer;
      transition: 0.3s;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .login-box button:hover {
      background: #8b5c4c; 
    }

    .login-box button:active {
      background: #8b5c4c;
      transform: scale(0.97);
      box-shadow: 0 0 20px 5px rgba(250, 180, 190, 0.7);
      animation: glow 0.4s ease-out;
    }

    @keyframes glow {
      from {
        box-shadow: 0 0 5px 2px rgba(250, 180, 190, 0.6);
      }
      to {
        box-shadow: 0 0 20px 6px rgba(250, 180, 190, 0.0);
      }
    }

    .error {
      color: #c0392b;
      margin-bottom: 15px;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <div class="container">
    <!-- Logo -->
    <img src="<?= base_url('images/chakanoks_logo.png') ?>" alt="Chakanoks Logo" class="logo">

    <div class="login-box">
      <h2>Login</h2>

      <?php if (session()->getFlashdata('error')): ?>
        <div class="error"><?= session()->getFlashdata('error') ?></div>
      <?php endif; ?>

      <form method="POST" action="<?= site_url('login/auth') ?>">
        <label for="username_email">Email or Username</label>
        <input type="text" id="username_email" name="username_email" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <div class="options">
          <label>
            <input type="checkbox" name="remember"> Remember Me
          </label>
          <a href="<?= site_url('forgot-password') ?>">Forgot Password?</a>
        </div>

        <button type="submit">Login</button>
      </form>
    </div>
  </div>

</body>
</html>
