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
  <link rel="stylesheet" href="homecss.css">
</head>
<body>

  <header>
    <div class="container nav-container">
      <a href="#" class="logo">
        <img src="logo.png" alt="BookItYourself Logo" class="logo-img">
        BookItYourself
      </a>
      <nav>
        <ul>
          <li><a href="homehtml.php" class="active">Home</a></li>
          <li><a href="#">Hotels</a></li>
          <li><a href="#">Villas</a></li>
          <li><a href="#">Deals</a></li>
          <li><a href="#">About</a></li>
        </ul>
      </nav>

      <a href="cart.php" class="cart-icon">
        <i class="fa-solid fa-cart-shopping"></i>
        <span class="cart-count">
          <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
        </span>
      </a>
      
      <a href="login.php" class="btn-auth">Sign In / Register</a>
    </div>
  </header>

</body>
</html>