<?php
session_start();
$conn = new mysqli("localhost", "root", "", "aukcja");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$sqlCategories = "SELECT * FROM categories";
$resultCategories = $conn->query($sqlCategories);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $productName = htmlspecialchars($_POST['product_name']);
    $description = htmlspecialchars($_POST['description']);
    $price = $_POST['price'];
    $image = $_FILES['image'];
    $categoryId = $_POST['category'];
    $quantity = $_POST['quantity'];

    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($image["name"]);
    move_uploaded_file($image["tmp_name"], $targetFile);

    $sqlInsertProduct = "INSERT INTO auction_items (product_name, price, quantity, description, image_path, user_id, category) 
                         VALUES ('$productName', $price, $quantity, '$description', '$targetFile', {$_SESSION['user_id']}, $categoryId)";

    if ($conn->query($sqlInsertProduct) === TRUE) {
        echo "Product added successfully!";
    } else {
        echo "Error adding product: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
</head>
<body>
    <h1>Add Product</h1>

    <form action="add_product.php" method="post" enctype="multipart/form-data">
        <label for="product_name">Product Name:</label>
        <input type="text" name="product_name" required>

        <label for="description">Description:</label>
        <textarea name="description" required></textarea>

        <label for="price">Price:</label>
        <input type="number" name="price" step="0.01" required>

        <label for="image">Upload Image:</label>
        <input type="file" name="image" accept="image/*" required>

        <label for="category">Category:</label>
        <select name="category" required>
            <?php
            while ($rowCategory = $resultCategories->fetch_assoc()) {
                echo "<option value='{$rowCategory['id']}'>{$rowCategory['name']}</option>";
            }
            ?>
        </select>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" required>

        <input type="submit" value="Add Product">
    </form>
</body>
</html>
