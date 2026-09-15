<?php
session_start();

// Handle Quantity & Remove Actions
if (isset($_GET['action'])) {
    $index = $_GET['index'];
    if ($_GET['action'] === 'increase') {
        $_SESSION['cart'][$index]['quantity']++;
    } elseif ($_GET['action'] === 'decrease' && $_SESSION['cart'][$index]['quantity'] > 1) {
        $_SESSION['cart'][$index]['quantity']--;
    } elseif ($_GET['action'] === 'remove') {
        array_splice($_SESSION['cart'], $index, 1);
    }
    header("Location: cart.php");
    exit();
}

// Total Calculation
$subtotal = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
}
$service_fee = $subtotal > 0 ? 3900 : 0;
$tax = $subtotal > 0 ? 2300 : 0;
$grand_total = $subtotal + $service_fee + $tax;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Booking Cart - BookItYourself</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="cart.css">
</head>
<body>

  <div id="header-placeholder"></div>

  <main class="container">
    <h1 class="page-title">Your Booking Cart (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?> items)</h1>

    <div class="cart-layout">
      <!-- Left Cart Items -->
      <section class="cart-items-section">
        <div class="table-header">
          <span>Property Name</span>
          <span>Date</span>
          <span>Quantity</span>
          <span>Removal</span>
        </div>

        <?php if (!empty($_SESSION['cart'])): ?>
          <?php foreach ($_SESSION['cart'] as $index => $item): ?>
            <div class="cart-card">
              <div class="card-image">
                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
              </div>

              <div class="card-details">
                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($item['location']); ?></p>
                <p><strong>Dates:</strong> <?php echo htmlspecialchars($item['check_in']); ?> - <?php echo htmlspecialchars($item['check_out']); ?></p>
                <p><strong>Guests:</strong> <?php echo htmlspecialchars($item['guests']); ?> Adults</p>
                <p><strong>Room Type:</strong> <?php echo htmlspecialchars($item['room_type']); ?></p>
              </div>

              <div class="card-quantity">
                <div class="quantity-control">
                  <a href="cart.php?action=decrease&index=<?php echo $index; ?>" class="qty-btn">-</a>
                  <input type="text" value="<?php echo $item['quantity']; ?>" readonly>
                  <a href="cart.php?action=increase&index=<?php echo $index; ?>" class="qty-btn">+</a>
                </div>
                <a href="#" class="edit-link">edit</a>
              </div>

              <div class="card-price-action">
                <a href="cart.php?action=remove&index=<?php echo $index; ?>" class="delete-btn">
                  <i class="fa-regular fa-trash-can"></i>
                </a>
                <div class="price-info">
                  <span class="unit-price">LKR per night</span>
                  <span class="total-price">LKR <?php echo number_format($item['price'] * $item['quantity']); ?></span>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="padding: 20px 0; text-align: center; color: #6b7280;">Your booking cart is currently empty.</p>
        <?php endif; ?>
      </section>

      <!-- Right Cart Summary -->
      <aside class="cart-summary-section">
        <div class="summary-card">
          <h2>Cart Summary</h2>
          <div class="summary-row">
            <span>Subtotal</span>
            <span>LKR <?php echo number_format($subtotal); ?></span>
          </div>
          <div class="summary-row">
            <span>Service Fees</span>
            <span>LKR <?php echo number_format($service_fee); ?></span>
          </div>
          <div class="summary-row">
            <span>Taxes</span>
            <span>LKR <?php echo number_format($tax); ?></span>
          </div>
          <hr class="divider">
          <div class="summary-row total-row">
            <strong>Total Price</strong>
            <strong>LKR <?php echo number_format($grand_total); ?></strong>
          </div>
          <a href="checkout.php" class="btn-checkout">Proceed to Checkout</a>
        </div>

        <div class="promo-card">
          <label for="coupon">Promo/Coupon code</label>
          <div class="promo-input-group">
            <input type="text" id="coupon" placeholder="Promo/coupon code">
            <button class="btn-apply">Apply</button>
          </div>
        </div>

        <div class="security-card">
          <p><i class="fa-solid fa-lock"></i> Secure payment via SSL</p>
          <p><i class="fa-solid fa-credit-card"></i> Multiple payment options</p>
        </div>
      </aside>
    </div>

    <!-- Trust Badges Section -->
    <section class="trust-section">
      <h2>Why BookItYourself?</h2>
      <div class="trust-cards">
        <div class="trust-card">Verified stays</div>
        <div class="trust-card">Flexible choice</div>
        <div class="trust-card">Secure booking</div>
      </div>
    </section>
  </main>

  <div id="footer-placeholder"></div>

  <script>
  fetch('header.php')
    .then(response => response.text())
    .then(data => {
      document.getElementById('header-placeholder').innerHTML = data;
    });

  fetch('footer.php')
    .then(response => response.text())
    .then(data => {
      document.getElementById('footer-placeholder').innerHTML = data;
    });
</script>

</body>
</html>