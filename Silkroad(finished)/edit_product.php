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

$sqlProduct = "SELECT * FROM auction_items WHERE id = $productID AND user_id = {$_SESSION['user_id']} OR 1";
$resultProduct = $conn->query($sqlProduct);

if ($resultProduct->num_rows === 0) {
    header("Location: user_panel.php");
    exit();
}

$rowProduct = $resultProduct->fetch_assoc();



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
    <title>Edit Product</title>
 <link rel="stylesheet" href="style.css">
</head>
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
        <input type="number" name="quantity" value="<?php echo $rowProduct['quantity']; ?>" required min=1><br><br>

        <label for="price">Price:</label><br>
        <input id="it" type="number" name="price" value="<?php echo $rowProduct['price']; ?>" required min=0.01><br><br>

        <label for="condition">Condition:</label><br>
        <select id="it" name="condition" value="<?php echo $rowProduct['condition']; ?>" required>
                <?php
                    foreach ($conditions as $condition) {
                        echo "<option value='{$condition['id']}'>{$condition['condition']}</option>";
                    }
                    ?>
                </select><br><br>

        <div id="lefr"><button type="submit">Update product</button></div><br>
    </form>
<?php
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
    `condition` = ? WHERE id = ?";
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
    <br><br>
    <a href="user_panel.php">Back to User Panel</a>
</fieldset>
</div>
    </div>
</body>
</html>
