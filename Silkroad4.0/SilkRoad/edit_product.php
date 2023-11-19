<?php
session_start();
$conn = new mysqli("localhost", "root", "", "aukcja");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
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

    $sqlUpdateProduct = "UPDATE auction_items SET 
                        product_name = ?, 
                        price = ?, 
                        'uantity' = ?, 
                        description = ? 
                        WHERE id = ?";
    $stmt = $conn->prepare($sqlUpdateProduct);
    $stmt->bind_param("sdisi", $productName, $price, $quantity, $description, $productID);

    $productName = htmlspecialchars($_POST['product_name']);
    $description = htmlspecialchars($_POST['description']);
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

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
</head>
<body>
    <h1>Edit Product</h1>

    <form action="edit_product.php?id=<?php echo $productID; ?>" method="post">
    <label for="product_name">Product Name:</label>
    <input type="text" name="product_name" value="<?php echo $rowProduct['product_name']; ?>" required>

    <label for="description">Description:</label>
    <textarea name="description" required><?php echo $rowProduct['description']; ?></textarea>

    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" value="<?php echo $rowProduct['quantity']; ?>" required>

    <label for="price">Price:</label>
    <input type="number" name="price" value="<?php echo $rowProduct['price']; ?>" required>

    <input type="submit" value="Update Product">
</form>


    <br>
    <a href="user_panel.php">Back to User Panel</a>
</body>
</html>
