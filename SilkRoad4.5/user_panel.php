<?php
session_start();
$conn = new mysqli("localhost", "root", "", "SilkRoad");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['user_id'];
$sqlUser = "SELECT users.access_level, access_level.level FROM users JOIN access_level ON users.access_level = access_level.id WHERE users.id = $userID";
$resultUser = $conn->query($sqlUser);

if ($resultUser->num_rows > 0) {
    $rowUser = $resultUser->fetch_assoc();
    $userAccess = $rowUser['level'];
    // admin
    if ($userAccess == 'admin') {
        $sqlUserProducts = "SELECT * FROM auction_items";
    } else {
        $sqlUserProducts = "SELECT * FROM auction_items WHERE user_id = $userID";
    }

    $resultUserProducts = $conn->query($sqlUserProducts);

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Panel</title>
</head>
<body>
    <h1>User Panel</h1>

    <?php
    echo "<p>You are on '{$userAccess}' account.</p>";
    
    if (isset($resultUserProducts) && $resultUserProducts->num_rows > 0) {
        while ($rowProduct = $resultUserProducts->fetch_assoc()) {
            echo "<div>";
            echo "<img src='{$rowProduct['image_path']}' alt='{$rowProduct['product_name']}' style='width: 100px; height: 100px;'>";
            echo "<p>{$rowProduct['product_name']}</p>";
            echo "<p>Price: {$rowProduct['price']}</p>";
            echo "<p>Quantity: {$rowProduct['quantity']}</p>";
            echo "<p>Description: {$rowProduct['description']}</p>";
            echo "<a href='edit_product.php?id={$rowProduct['id']}'>Edit</a> | ";
            echo "<a href='delete_product.php?id={$rowProduct['id']}'>Delete</a>";
            echo "</div>";
        }
    } else {
        echo "<p>No products added by the user.</p>";
    }
    ?>

    <br>
    <a href="add_product.php">Add New Product</a>
    <br>
    <a href="logout.php">Logout</a>
    <br>
    <a href="index.php">Main page</a>
</body>
</html>
