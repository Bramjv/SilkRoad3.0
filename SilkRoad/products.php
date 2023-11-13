<?php
$conn = new mysqli("localhost", "root", "", "aukcja");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM auction_items";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Auction Products</title>
</head>
<body>
    <h1>Auction Products</h1>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div id='p'>";
            echo "<img src='{$row['image_path']}' alt='{$row['product_name']}' id='pf'>";
            echo "<p>{$row['product_name']}</p>";
            echo "<p>Price: {$row['price']}</p>";
            echo "<p>Quantity: {$row['quantity']}</p>";
            echo "<p>Description: {$row['description']}</p>";
            echo "</div>";
        }
    } else {
        echo "<p>No auction items available.</p>";
    }
    ?>

</body>
</html>
