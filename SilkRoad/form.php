<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Auction Site</title>
</head>
<body>
    <form action="auction.php" method="post" enctype="multipart/form-data">
        <label for="product_name">Product Name:</label>
        <input type="text" name="product_name" required>

        <label for="price">Price:</label>
        <input type="number" name="price" step="0.01" required>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" required>

        <label for="description">Description:</label>
        <textarea name="description" required></textarea>

        <label for="image">Upload Image:</label>
        <input type="file" name="image" accept="image/*" required>

        <input type="submit" name="submit" value="Submit">
    </form>
</body>
</html>