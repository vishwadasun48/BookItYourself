<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stay_id      = (int)$_POST['stay_id'];
    $check_in     = $_POST['check_in'];
    $check_out    = $_POST['check_out'];
    $guests_count = isset($_POST['guests']) ? (int)$_POST['guests'] : 1;

    // Database Connection
    $conn = new mysqli("localhost", "root", "", "bookityourself_db");

    // User Login වෙලා ඉන්නවා නම් Database එකේ Save කිරීම
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        
        $stmt = $conn->prepare("INSERT INTO cart (user_id, stay_id, check_in, check_out, guests) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iissi", $user_id, $stay_id, $check_in, $check_out, $guests_count);
        $stmt->execute();
        $stmt->close();
    }

    // Session Cart එකට දත්ත එකතු කිරීම
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // කලින් මේ stay එකම (same dates වලට) cart එකේ තියෙනවද බලමු
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $stay_id && $item['check_in'] == $check_in && $item['check_out'] == $check_out) {
            // දැනටමත් තිබේ නම් guests සහ quantity update කිරීම
            $item['guests'] = $guests_count;
            $item['quantity'] = $guests_count; // cart.php එකේ calculations වලට අවශ්‍යයි
            $found = true;
            break;
        }
    }
    unset($item); // reference variable eka ain kirima

    // නැත්නම් අලුතින් add කිරීම
    if (!$found) {
        $cart_item = [
            'id'        => $stay_id,
            'name'      => $_POST['property_name'],
            'location'  => $_POST['location'],
            'price'     => (float)$_POST['price_per_night'],
            'image'     => $_POST['image'],
            'check_in'  => $check_in,
            'check_out' => $check_out,
            'guests'    => $guests_count,
            'room_type' => $_POST['room_type'],
            'quantity'  => $guests_count // guests count eka quantity eka lesath pawthwa ganeema
        ];
        $_SESSION['cart'][] = $cart_item;
    }

    header("Location: cart.php");
    exit();
}
?>