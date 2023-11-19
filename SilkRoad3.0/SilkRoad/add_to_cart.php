<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = $_POST['id'];
    $itemName = $_POST['product_name'];
    $itemPrice = $_POST['price'];
    $itemQuantity = $_POST['quantity'];

    $item = array(
        'id' => $itemId,
        'product_name' => $itemName,
        'price' => $itemPrice,
        'quantity' => $itemQuantity
    );

    $_SESSION['cart'][] = $item;

    header("Location: cart.php");
} else {
    echo 'Invalid request method.';
}
?>
