<?php
session_start();
require_once __DIR__ . '/../config/payment_config.php';
$order_id = $_GET['order_id'] ?? ($_SESSION['last_order_id'] ?? '');
$status = 'pending'; $total = 0; $name = '';
if ($order_id) {
  $db = db_connection();
  $stmt = $db->prepare('SELECT customer_name,total,payment_status FROM bookings WHERE order_id=? LIMIT 1');
  $stmt->bind_param('s',$order_id); $stmt->execute(); $r=$stmt->get_result()->fetch_assoc();
  if ($r) { $name=$r['customer_name']; $total=$r['total']; $status=$r['payment_status']; }
  $stmt->close(); $db->close();
}
if ($status === 'paid') unset($_SESSION['cart']);
?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Payment Status - BookItYourself</title><link rel="stylesheet" href="/bookityourself/css/payment_status.css"></head><body><div class="status-card">
<div class="status-icon <?= $status==='paid'?'ok':'wait' ?>"><?= $status==='paid'?'✓':'…' ?></div>
<h1><?= $status==='paid'?'Payment Successful':'Payment Processing' ?></h1>
<p><?= $status==='paid'?'Your booking has been confirmed.':'We are waiting for the secure payment confirmation. Please refresh this page shortly.' ?></p>
<div class="details"><span>Order ID</span><strong><?= htmlspecialchars($order_id) ?></strong><span>Guest</span><strong><?= htmlspecialchars($name) ?></strong><span>Total</span><strong>LKR <?= number_format((float)$total,2) ?></strong><span>Status</span><strong><?= htmlspecialchars(strtoupper($status)) ?></strong></div>
<a href="/bookityourself/homehtml.php">Back to Home</a></div></body></html>
