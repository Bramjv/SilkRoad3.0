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
$sqlConditions = "SELECT * FROM `condition`";
$resultConditions = $conn->query($sqlConditions);

if ($resultConditions === FALSE) {
    die("Error fetching conditions: " . $conn->error);
}

$conditions = [];
while ($rowCondition = $resultConditions->fetch_assoc()) {
    $conditions[] = $rowCondition;
}
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Hurricane' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <title>User Panel</title>
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

<fieldset>
<legend>User Panel</legend>


<section id="sss">
        <?php
        if (isset($resultUserProducts) && $resultUserProducts->num_rows > 0) {
            while ($rowProduct = $resultUserProducts->fetch_assoc()) {
                 echo "<div id='p'>";
            echo "<img src='{$rowProduct['image_path']}' alt='{$rowProduct['product_name']}' style='width: 100px; height: 100px;'>";
            echo "<p>{$rowProduct['product_name']}</p>";
            echo "<p>Price: {$rowProduct['price']}</p>";
            echo "<p>Quantity: {$rowProduct['quantity']}</p>";
            echo "<p>Description: {$rowProduct['description']}</p>";
            foreach ($conditions as $condition) {
                if ($condition['id'] == $rowProduct['condition']) {
                    $conditionName = $condition['condition'];
                    break;
                }
            }
            echo "<p>Condition: $conditionName</p>";
            echo "<a href='edit_product.php?id={$rowProduct['id']}'>Edit</a> | ";
            echo "<a href='delete_product.php?id={$rowProduct['id']}'>Delete</a>";
            echo "</div>";
            }
        } else {
            echo "<p>No products added by the user.</p>";
        }
        ?>
    </section>

</fieldset>

</body>
</html>
