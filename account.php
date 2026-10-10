<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$db   = "bookityourself_db";

$conn = new mysqli($host, $user, $pass, $db);

$user_id = $_SESSION['user_id'];

// User details
$stmt = $conn->prepare("SELECT username, email, phone, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_data = $stmt->get_result()->fetch_assoc();

// Cart Items Count
$cart_stmt = $conn->prepare("SELECT COUNT(*) as cart_count FROM cart WHERE user_id = ?");
$cart_stmt->bind_param("i", $user_id);
$cart_stmt->execute();
$cart_count = $cart_stmt->get_result()->fetch_assoc()['cart_count'];

// Logout action
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Account - BookItYourself</title>
  <link rel="stylesheet" href="account.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

  <div id="header-placeholder"></div>

  <main class="container">
    <div class="account-card">
      <div class="profile-header">
        <div class="avatar-large">
          <?php echo strtoupper(substr($user_data['username'], 0, 1)); ?>
        </div>
        <h2>Welcome, <?php echo htmlspecialchars($user_data['username']); ?>!</h2>
        <p class="email-text"><?php echo htmlspecialchars($user_data['email']); ?></p>
      </div>

      <div class="account-grid">
        <div class="info-box">
          <i class="fa-solid fa-cart-shopping"></i>
          <h3>Cart Items</h3>
          <p>You have <strong><?php echo $cart_count; ?></strong> stay(s) in your cart.</p>
          <a href="cart.php" class="btn-action">View Cart</a>
        </div>

        <div class="info-box">
          <i class="fa-solid fa-user-gear"></i>
          <h3>Member Since</h3>
          <p><?php echo date('M d, Y', strtotime($user_data['created_at'])); ?></p>
          <a href="account.php?action=logout" class="btn-logout">Logout</a>
        </div>
      </div>
    </div>
  </main>

  <div id="footer-placeholder"></div>

  <script>
    fetch('header.php')
      .then(response => response.text())
      .then(data => { document.getElementById('header-placeholder').innerHTML = data; });

    fetch('footer.php')
      .then(response => response.text())
      .then(data => { document.getElementById('footer-placeholder').innerHTML = data; });
  </script>
</body>
</html>