<?php
session_start();
if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += ((float)$item['price']) * ((int)$item['quantity']);
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
<title>Secure Checkout - BookItYourself</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="checkout.css">
</head>
<body>
<div id="header-placeholder"></div>
<main class="checkout-container">
  <div class="checkout-heading">
    <a href="cart.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to cart</a>
    <h1>Secure Checkout</h1>
    <p>Complete your details and pay securely by Visa or Mastercard.</p>
  </div>

  <form action="process_payment.php" method="post" class="checkout-grid">
    <section class="checkout-card">
      <div class="section-title"><span>1</span><div><h2>Guest Details</h2><p>Enter the contact details for this booking.</p></div></div>
      <div class="form-grid">
        <div class="field"><label>First Name</label><input name="first_name" required maxlength="80"></div>
        <div class="field"><label>Last Name</label><input name="last_name" required maxlength="80"></div>
        <div class="field"><label>Email</label><input type="email" name="email" required maxlength="190"></div>
        <div class="field"><label>Phone Number</label><input name="phone" required maxlength="30" placeholder="0771234567"></div>
        <div class="field full"><label>Address</label><input name="address" required maxlength="255"></div>
        <div class="field"><label>City</label><input name="city" required maxlength="100"></div>
        <div class="field"><label>Country</label><input value="Sri Lanka" readonly></div>
      </div>

      <div class="payment-section">
        <div class="section-title"><span>2</span><div><h2>Payment Method</h2><p>Select your preferred card network.</p></div></div>
        <div class="card-options">
          <label class="pay-option"><input type="radio" name="payment_method" value="VISA" required><span class="radio-ui"></span><strong>VISA</strong><small>Credit / Debit Card</small></label>
          <label class="pay-option"><input type="radio" name="payment_method" value="MASTER" required><span class="radio-ui"></span><strong>Mastercard</strong><small>Credit / Debit Card</small></label>
        </div>
        <div class="secure-note"><i class="fa-solid fa-lock"></i> Your card details are entered on PayHere's secure payment page. BookItYourself does not store your card number or CVV.</div>
      </div>
    </section>

    <aside class="summary-card">
      <h2>Booking Summary</h2>
      <?php foreach ($_SESSION['cart'] as $item): ?>
      <div class="booking-item">
        <?php if (!empty($item['image'])): ?><img src="<?= htmlspecialchars($item['image']) ?>" alt=""><?php endif; ?>
        <div><h3><?= htmlspecialchars($item['name']) ?></h3><p><?= htmlspecialchars($item['check_in']) ?> - <?= htmlspecialchars($item['check_out']) ?></p><p><?= (int)$item['guests'] ?> guest(s) · <?= htmlspecialchars($item['room_type']) ?></p></div>
      </div>
      <?php endforeach; ?>
      <div class="money-row"><span>Subtotal</span><span>LKR <?= number_format($subtotal) ?></span></div>
      <div class="money-row"><span>Service Fees</span><span>LKR <?= number_format($service_fee) ?></span></div>
      <div class="money-row"><span>Taxes</span><span>LKR <?= number_format($tax) ?></span></div>
      <div class="money-row total"><span>Total</span><span>LKR <?= number_format($grand_total) ?></span></div>
      <button class="pay-button" type="submit"><i class="fa-solid fa-lock"></i> Pay LKR <?= number_format($grand_total) ?></button>
      <p class="terms">By continuing, you agree to the booking and payment terms.</p>
    </aside>
  </form>
</main>
<div id="footer-placeholder"></div>
<script>
fetch('header.php').then(r=>r.text()).then(d=>document.getElementById('header-placeholder').innerHTML=d).catch(()=>{});
fetch('footer.php').then(r=>r.text()).then(d=>document.getElementById('footer-placeholder').innerHTML=d).catch(()=>{});
</script>
</body>
</html>
