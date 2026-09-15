<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $guests_count = isset($_POST['guests']) ? (int)$_POST['guests'] : 1;

    $cart_item = [
        'id' => $_POST['stay_id'],
        'name' => $_POST['property_name'],
        'location' => $_POST['location'],
        'price' => (float)$_POST['price_per_night'],
        'image' => $_POST['image'],
        'check_in' => $_POST['check_in'],
        'check_out' => $_POST['check_out'],
        'guests' => $guests_count,
        'room_type' => $_POST['room_type'],
        'quantity' => $guests_count // Guests ගණන Quantity එක ලෙස යෙදීම
    ];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Cart එකේ දැනටමත් තිබේ නම් Quantity/Guests එකතු කිරීම
    $exists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $cart_item['id']) {
            $item['quantity'] += $guests_count;
            $item['guests'] = $item['quantity'];
            $exists = true;
            break;
        }
    }

    if (!$exists) {
        $_SESSION['cart'][] = $cart_item;
    }

    header("Location: cart.php");
    exit();
}
?>