<?php
session_start();
$conn = new mysqli("localhost", "root", "", "SilkRoad");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: user_panel.php");
    exit();
}

$productID = $_GET['id'];

$sqlProduct = "SELECT * FROM auction_items WHERE id = $productID AND user_id = {$_SESSION['user_id']}";
$resultProduct = $conn->query($sqlProduct);

if ($resultProduct->num_rows === 0) {
    header("Location: user_panel.php");
    exit();
}

$sqlDeleteProduct = "DELETE FROM auction_items WHERE id = $productID";

if ($conn->query($sqlDeleteProduct) === TRUE) {
    echo "Product deleted successfully!";
} else {
    echo "Error deleting product: " . $conn->error;
}

$conn->close();
?>
