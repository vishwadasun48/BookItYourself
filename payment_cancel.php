<?php
session_start();
require_once 'payment_config.php';
$order_id = $_GET['order_id'] ?? ($_SESSION['last_order_id'] ?? '');
if ($order_id) {
 $db=db_connection();
 $stmt=$db->prepare("UPDATE bookings SET payment_status='cancelled' WHERE order_id=? AND payment_status='pending'");
 $stmt->bind_param('s',$order_id); $stmt->execute(); $stmt->close(); $db->close();
}
?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Payment Cancelled</title><link rel="stylesheet" href="payment_status.css"></head><body><div class="status-card"><div class="status-icon bad">×</div><h1>Payment Cancelled</h1><p>No successful payment was recorded. Your cart is still available, so you can try again.</p><div class="details"><span>Order ID</span><strong><?= htmlspecialchars($order_id) ?></strong></div><a href="cart.php">Return to Cart</a></div></body></html>
