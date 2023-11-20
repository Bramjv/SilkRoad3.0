<?php
session_start();

$conn = new mysqli("localhost", "root", "", "SilkRoad");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$itemId = isset($_GET['id']) ? $_GET['id'] : null;

if (!$itemId) {

    header("Location: index.php");
    exit();
}

$itemSql = "SELECT * FROM auction_items WHERE id = ?";
$itemStmt = $conn->prepare($itemSql);
$itemStmt->bind_param("i", $itemId);

$itemStmt->execute();
$itemResult = $itemStmt->get_result();

if ($itemResult->num_rows === 0) {
    header("Location: index.php");
    exit();
}

$itemDetails = $itemResult->fetch_assoc();

$sqlConditions = "SELECT * FROM `condition`";
$resultConditions = $conn->query($sqlConditions);

if ($resultConditions === FALSE) {
    die("Error fetching conditions: " . $conn->error);
}

$conditions = [];
while ($rowCondition = $resultConditions->fetch_assoc()) {
    $conditions[] = $rowCondition;
}

$conditionId = $itemDetails['condition'];
$conditionName = '';

foreach ($conditions as $condition) {
    if ($condition['id'] == $conditionId) {
        $conditionName = $condition['condition'];
        break;
    }
}

$itemDetails['condition'] = $conditionName;

$itemStmt->close();
$conn->close();
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
    <title>Silkroad</title>
</head>
<body>
    <header id="header">

        <div id="logo">
            <a href="index.php" id="p090">
                <img src="fin.png" id="l1" >
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

            </header>
        
            <div id="popup" class="popup">

<div class="popup-content">

    <span class="close" onclick="document.getElementById('popup').style.display = 'none'">&times;</span>

    <form method="post" action="login.php" class="login">

    <h1>Sign in</h1>

    <input type="text" name="username" placeholder="Username" required><br><br>

    <input type="password" name="password" placeholder="Password" required><br><br>

    <div id="batt"><button type="submit">Sign in</button></div><br><br>
        
    </form>
    
</div>
</div>

<div id="popup-register" class="popup">

<div class="popup-content">

    <span class="close" onclick="document.getElementById('popup-register').style.display = 'none'">&times;</span>

    <form method="post" action="register.php" id="rej">

        <h1>Sign up</h1>

        <input type="text" name="username" placeholder="Username" required><br><br>
        <input type="text" name="name" placeholder="Name" required><br><br>
        <input type="text" name="surname" placeholder="Surname" required><br><br>
        <input type="text" name="email" placeholder="Email(optional)"><br><br>
        <input type="tel" name="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{3}" placeholder="Phone number(000-000-000)"><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <input type="password" name="password2" placeholder="Repeat Password"><br><br>

        <div id="batt"><button type="submit">Sign up</button></div><br><br>
    </form>
</div>
</div>

    <div id="left">
        <div id="left-content">
        <h2>
            <?php echo $itemDetails['product_name']; ?>
        </h2>

        <img src="<?php echo $itemDetails['image_path']; ?>" alt="<?php echo $itemDetails['product_name']; ?>">
    </div>
        
    </div>

    <div id="right">
    <div id="right-content">
        <h1><?php echo $itemDetails['product_name']; ?></h1>
        <h3>PRICE: <?php echo $itemDetails['price']; ?></h3>

        <div id="leaf"><button id="decrementQuantity" onclick="decrementQuantity()">-</button>
        <input type="number" id="quantity" value="1" min="1" max="<?php echo $itemDetails['quantity']; ?>" pattern="[0-8]">
        <button id="incrementQuantity" onclick="incrementQuantity()">+</button></div>

        <p>of <?php echo $itemDetails['quantity'];?> pieces</p>
        <br>
        <h4>Condition</h4>
        <p><?php echo $itemDetails['condition'];?></p>
        <br>
        <h4>DESCRIPTION</h4>
        <p><?php echo $itemDetails['description']; ?></p>
        

        <form method="post" action="add_to_cart.php">
            <input type="hidden" name="id" value="<?php echo $itemId; ?>">
            <input type="hidden" name="product_name" value="<?php echo $itemDetails['product_name']; ?>">
            <input type="hidden" name="price" value="<?php echo $itemDetails['price']; ?>">
            <input type="hidden" name="quantity" id="cartQuantity" value="1">
            <div id="lrr"><button type="submit" name="add_to_cart">Add to Cart</button></div>
        </form>
    </div>
</div>

<script>
    function decrementQuantity() {
        var quantityInput = document.getElementById('quantity');
        if (quantityInput.value > 1) {
            quantityInput.value--;
        }
        updateCartQuantity();
    }

    function incrementQuantity() {
        var quantityInput = document.getElementById('quantity');
        if (quantityInput.value < <?php echo $itemDetails['quantity']; ?>) {
            quantityInput.value++;
        }
        updateCartQuantity();
    }

    function updateCartQuantity() {
        var quantityInput = document.getElementById('quantity');
        var cartQuantityInput = document.getElementById('cartQuantity');
        cartQuantityInput.value = quantityInput.value;
    }
</script>

</body>
</html>