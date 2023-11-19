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

$rowProduct = $resultProduct->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $productName = htmlspecialchars($_POST['product_name']);
    $description = htmlspecialchars($_POST['description']);
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $condition = $_POST['condition'];

    $sqlUpdateProduct = "UPDATE auction_items SET 
    product_name = ?, 
    price = ?, 
    quantity = ?, 
    description = ?, 
    condition = ?,
    WHERE id = ?";
    $stmt = $conn->prepare($sqlUpdateProduct);
    $stmt->bind_param("sdisii", $productName, $price, $quantity, $description, $condition, $productID);

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Product updated successfully!";
    } else {
        echo "Error updating product: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
 <link rel="stylesheet" href="style.css">
</head>
<body>
<div id="edit_product">
        <div id="edit_product_content">
<fieldset>
    <legend><h4>Edit Product</h4></legend>
    
    <form action="edit_product.php?id=<?php echo $productID; ?>" method="post">

        <label for="product_name">Product Name:</label><br>
        <input id="it" type="text" name="product_name" value="<?php echo $rowProduct['product_name']; ?>" required><br><br>

        <label for="description">Description:</label><br>
        <textarea id="it" name="description" required><?php echo $rowProduct['description']; ?></textarea><br><br>

        <label for="quantity">Quantity:</label><br>
        <input type="number" name="quantity" value="<?php echo $rowProduct['quantity']; ?>" required><br><br>

        <label for="price">Price:</label><br>
        <input type="number" name="price" value="<?php echo $rowProduct['price']; ?>" required><br><br>

        <input type="submit" value="Update Product" id="up"><br>
    </form>

    <br>
    <a href="user_panel.php">Back to User Panel</a>
</fieldset>
</div>
    </div>
</body>
</html>
