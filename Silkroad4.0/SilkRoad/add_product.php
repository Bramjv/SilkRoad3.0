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
    <input type="text" placeholder="If it exists we have it..." id="si">
     <a href="#" id="sb">
        <i class="fas fa-search"></i>
    </a>
</div>

<div id="cart">
    <a href="cart.php"><img src="cart.png" id="c1"></a>
</div>

<div id="lr">
    <?php
    if (isset($_SESSION["username"])) {
        echo "<button>Welcome, {$_SESSION['username']}</button>";
        echo "<br><br>";
        echo "<button><a href='add_product.php'>Add Product</a></button>";
        echo "<br><br>";
        echo "<button><a href='logout.php'>Logout</a></button>";
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
             <form action="add_product.php" method="post" enctype="multipart/form-data"></form>
            <lable for="name" id="l1">Name</lable><br>
            <input type="text" name="name" required><br><br>

            <lable for="img" id="l1">Image</lable><br>
            <br><br>

            <label for="value" id="l1">Value</label><br>
            <input type="number" name="vaule" required><br><br>

            <label for="phone_number" id="l1">Phone number</label><br>
            <input type="tel" name="phone_number" required><br><br>

            <label for="description">Description</label><br>
            <textarea name="description" id="texxt"></textarea><br><br>
            
            <label for="quantity">Quantity</label><br>
            <input type="number" name="quantity" required><br><br>

            <label for="condition" >Condition</label><br>
            <select option name="condition" required>

            </select>
            <br><br>

            <label for="Categories">Categories</label><br>
            <select option name="Categories" required>

            </select>
            <br><br>

            
            <div id="lrr">
            <button type="submit"> Add</button>
            </div>
            </form>
        </div>
        </fieldset>
    </div>
</body>
</html>
