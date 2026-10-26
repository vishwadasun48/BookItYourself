<?php
// Session එක start වී නැත්නම් පමණක් start කිරීම
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Header of the Page</title>
  <link rel="stylesheet" href="/bookityourself/css/homecss.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

  <header>
    <div class="container nav-container">
      <a href="/bookityourself/homehtml.php" class="logo">
        <img src="/bookityourself/Images/logo.png" alt="BookItYourself Logo" class="logo-img">
        BookItYourself
      </a>
      <nav>
        <ul>
          <li><a href="/bookityourself/homehtml.php" class="active">Home</a></li>
          <li><a href="/bookityourself/pages/hotels.php" class="active">Hotels & Villas</a></li>
          <li><a href="#">Deals</a></li>
          <li><a href="/bookityourself/pages/about.php" class="active">About</a></li>
        </ul>
      </nav>

      <a href="/bookityourself/user/cart.php" class="cart-icon">
        <i class="fa-solid fa-cart-shopping"></i>
        <span class="cart-count">
          <?php 
            // Database එකෙන් හෝ Session එකෙන් Cart count එක පෙන්වීම
            echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; 
          ?>
        </span>
      </a>
      
      <!-- User Login වී ඇත්දැයි පරීක්ෂා කර Header එක වෙනස් කිරීම -->
      <?php if (isset($_SESSION['user_id'])): ?>
        <div class="user-menu" style="display: flex; align-items: center; gap: 10px;">
          <a href="/bookityourself/user/account.php" class="btn-auth" style="background-color: #007a78; color: white; text-decoration: none; padding: 8px 15px; border-radius: 6px; font-weight: 600;">
            <i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
          </a>
        </div>
      <?php else: ?>
        <a href="/bookityourself/login.php" class="btn-auth">Sign In / Register</a>
      <?php endif; ?>

    </div>
  </header>

</body>
</html>