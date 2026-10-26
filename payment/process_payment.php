<?php
session_start();
require_once __DIR__ . '/../config/payment_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['cart'])) {
    header('Location: ../user/cart.php'); exit;
}

$first = trim($_POST['first_name'] ?? '');
$last = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$city = trim($_POST['city'] ?? '');
$method = $_POST['payment_method'] ?? '';

if (!$first || !$last || !filter_var($email, FILTER_VALIDATE_EMAIL) || !$phone || !$address || !$city || !in_array($method, ['VISA','MASTER'], true)) {
    die('Invalid checkout details. Please go back and check the form.');
}

$subtotal = 0;
foreach ($_SESSION['cart'] as $item) $subtotal += (float)$item['price'] * (int)$item['quantity'];
$service_fee = $subtotal > 0 ? 3900 : 0;
$tax = $subtotal > 0 ? 2300 : 0;
$total = $subtotal + $service_fee + $tax;
$amount = number_format($total, 2, '.', '');
$currency = 'LKR';
$order_id = 'BIY-' . date('YmdHis') . '-' . random_int(1000, 9999);

$db = db_connection();
$cart_json = json_encode($_SESSION['cart'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$name = trim($first . ' ' . $last);
$stmt = $db->prepare('INSERT INTO bookings (order_id,customer_name,email,phone,address,city,cart_json,subtotal,service_fee,tax,total,currency,payment_method,payment_status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,\'pending\')');
$stmt->bind_param('sssssssddddss', $order_id,$name,$email,$phone,$address,$city,$cart_json,$subtotal,$service_fee,$tax,$total,$currency,$method);
$stmt->execute();
$stmt->close();
$db->close();

$_SESSION['last_order_id'] = $order_id;

$hash = strtoupper(md5(PAYHERE_MERCHANT_ID . $order_id . $amount . $currency . strtoupper(md5(PAYHERE_MERCHANT_SECRET))));
$items = count($_SESSION['cart']) === 1 ? $_SESSION['cart'][0]['name'] : 'BookItYourself Hotel Booking';

$return_url = SITE_URL . '/payment/payment_success.php?order_id=' . urlencode($order_id);
$cancel_url = SITE_URL . '/payment/payment_cancel.php?order_id=' . urlencode($order_id);
$notify_url = SITE_URL . '/payment/payment_notify.php';
?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Redirecting to payment...</title></head><body>
<form id="payhereForm" method="post" action="<?= htmlspecialchars(PAYHERE_CHECKOUT_URL) ?>">
<input type="hidden" name="merchant_id" value="<?= htmlspecialchars(PAYHERE_MERCHANT_ID) ?>">
<input type="hidden" name="return_url" value="<?= htmlspecialchars($return_url) ?>">
<input type="hidden" name="cancel_url" value="<?= htmlspecialchars($cancel_url) ?>">
<input type="hidden" name="notify_url" value="<?= htmlspecialchars($notify_url) ?>">
<input type="hidden" name="order_id" value="<?= htmlspecialchars($order_id) ?>">
<input type="hidden" name="items" value="<?= htmlspecialchars($items) ?>">
<input type="hidden" name="currency" value="LKR">
<input type="hidden" name="amount" value="<?= htmlspecialchars($amount) ?>">
<input type="hidden" name="first_name" value="<?= htmlspecialchars($first) ?>">
<input type="hidden" name="last_name" value="<?= htmlspecialchars($last) ?>">
<input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
<input type="hidden" name="phone" value="<?= htmlspecialchars($phone) ?>">
<input type="hidden" name="address" value="<?= htmlspecialchars($address) ?>">
<input type="hidden" name="city" value="<?= htmlspecialchars($city) ?>">
<input type="hidden" name="country" value="Sri Lanka">
<input type="hidden" name="hash" value="<?= htmlspecialchars($hash) ?>">
<input type="hidden" name="payment_method" value="<?= htmlspecialchars($method) ?>">
<noscript><button type="submit">Continue to secure payment</button></noscript>
</form>
<script>document.getElementById('payhereForm').submit();</script>
</body></html>
