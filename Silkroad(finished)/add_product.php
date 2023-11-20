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

$sqlCategories = "SELECT * FROM categories";
$resultCategories = $conn->query($sqlCategories);

$sqlConditions = "SELECT * FROM `condition`";
$resultConditions = $conn->query($sqlConditions);

if ($resultConditions === FALSE) {
    die("Error fetching conditions: " . $conn->error);
}

$conditions = [];
while ($rowCondition = $resultConditions->fetch_assoc()) {
    $conditions[] = $rowCondition;
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Hurricane' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <title>Main site</title>
</head>
<body>
<header id="header">

<div id="logo">
<a href="index.php" id="p090">
    <img src="fin.png" id="l1">
</a>
</div>

<div id="p1">
    <table id="t1">
        <tr id="h">
            <td colspan="2"><b>You can pay with:</b></td>
        </tr>
        <tr>
            <td><p>Bitcoin</p></td>
            <td><p>Etherium</p></td>
        </tr>

        <tr>
            <td><p>Litecoin</p></td>
            <td><p>Stellar</p></td>
        </tr>
    </table>
</div>

<div id="sc">
            <form method="get" action="index.php">
                <input type="text" placeholder="If it exists we have it..." id="si" name="search">
                <div id="serr">
                <button type="submit" id="sb">
                    <i class="fas fa-search"></i>
                </button>
                </div>
            </form>
        </div>

<div id="cart">
    <a href="cart.php"><img src="cart.png" id="c1"></a>
</div>

<div id="lr">
        <?php
            if (isset($_SESSION["username"])) {
                echo "<a href='user_panel.php'><button>{$_SESSION['username']}</button></a>";
                echo "<br><br>";
                echo "<a href='add_product.php'><button>Add Product</button></a>";
                echo "<br><br>";
                echo "<a href='logout.php'><button>Logout</button></a>";
            } else {
                echo "<button type='submit' id='b1' onclick=\"document.getElementById('popup').style.display = 'block'\">Sign in<div><img src='login.png'></div> </button>";
                echo "<br><br>";
                echo "<button type='submit' id='b2' onclick=\"document.getElementById('popup-register').style.display = 'block'\">Sign up<div><img src='register.png' ></div></button>";
            }
            ?>
        </div>
</header>

<div id="add-product">
        
        <fieldset>
    <legend>Add product</legend>
        <div id="add-product-content">

             <form action="add_product.php" method="post" enctype="multipart/form-data">
            <lable for="name" id="l1">Product Name:</lable><br>
            <input type="text" name="product_name" required><br><br>

            <label for="image">Upload Image:</label><br>
            <input type="file" name="image" accept="image/*" required>
            <br><br>

            <label for="price">Price:</label><br>
            <input type="number" name="price" step="0.01" required min=0.01><br><br>

            <label for="description">Description</label><br>
            <textarea name="description" id="texxt"></textarea><br><br>
            
            <label for="quantity">Quantity</label><br>
            <input type="number" name="quantity" required min=1><br><br>

            <label for="category">Category:</label>
            <select name="category" required>
                <?php
                while ($rowCategory = $resultCategories->fetch_assoc()) {
                    echo "<option value='{$rowCategory['id']}'>{$rowCategory['name']}</option>";
                }
                ?>
            </select>
            <br><br>
            <label for="condition">Condition:</label>
            <select name="condition" required>
                <?php
                    foreach ($conditions as $condition) {
                        echo "<option value='{$condition['id']}'>{$condition['condition']}</option>";
                    }
                    ?>
                </select>
                <br><br>

            <div id="lrr">
            <button type="submit"> Add</button>
            </form>
            <?php
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
            
                $sqlInsertProduct = "INSERT INTO auction_items (product_name, price, quantity, description, image_path, user_id, category, `condition`) 
                                     VALUES ('$productName', $price, $quantity, '$description', '$targetFile', {$_SESSION['user_id']}, $categoryId, {$_POST['condition']})";
            
                if ($conn->query($sqlInsertProduct) === TRUE) {
                    echo "<p>Product added successfully</p>";
            
                } else {
                    echo "Error adding product: " . $conn->error;
                }
            }
            $conn->close();
            ?>
            </div>
        </div>
        </fieldset>
    </div>
    <script src="script.js"></script>
</body>
</html>