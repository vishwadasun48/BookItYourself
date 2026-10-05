<?php
require_once 'payment_config.php';

$merchant_id = $_POST['merchant_id'] ?? '';
$order_id = $_POST['order_id'] ?? '';
$payment_id = $_POST['payment_id'] ?? '';
$amount = $_POST['payhere_amount'] ?? '0.00';
$currency = $_POST['payhere_currency'] ?? '';
$status_code = (int)($_POST['status_code'] ?? -999);
$md5sig = $_POST['md5sig'] ?? '';
$method = $_POST['method'] ?? '';
$status_message = $_POST['status_message'] ?? '';
$masked_card = $_POST['card_no'] ?? null;
$card_holder = $_POST['card_holder_name'] ?? null;
$card_expiry = $_POST['card_expiry'] ?? null;

$local_sig = strtoupper(md5($merchant_id . $order_id . $amount . $currency . $status_code . strtoupper(md5(PAYHERE_MERCHANT_SECRET))));
if ($merchant_id !== PAYHERE_MERCHANT_ID || !hash_equals($local_sig, $md5sig)) {
    http_response_code(400); exit('Invalid signature');
}

$status = match($status_code) { 2=>'paid', 0=>'pending', -1=>'cancelled', -2=>'failed', -3=>'chargedback', default=>'failed' };
$db = db_connection();
$db->begin_transaction();
try {
    $stmt = $db->prepare('UPDATE bookings SET payment_status=?, payment_method=? WHERE order_id=?');
    $stmt->bind_param('sss', $status, $method, $order_id);
    $stmt->execute(); $stmt->close();

    $stmt = $db->prepare('INSERT INTO payments (order_id,payhere_payment_id,method,amount,currency,status_code,status_message,masked_card,card_holder_name,card_expiry) VALUES (?,?,?,?,?,?,?,?,?,?)');
    $amt = (float)$amount;
    $stmt->bind_param('sssdsissss', $order_id,$payment_id,$method,$amt,$currency,$status_code,$status_message,$masked_card,$card_holder,$card_expiry);
    $stmt->execute(); $stmt->close();
    $db->commit();
    http_response_code(200); echo 'OK';
} catch (Throwable $e) {
    $db->rollback(); http_response_code(500); echo 'Database error';
}
$db->close();
