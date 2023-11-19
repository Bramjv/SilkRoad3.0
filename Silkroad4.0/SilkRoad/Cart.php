<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function displayCartItems() {
    foreach ($_SESSION['cart'] as $index => $item) {
        echo "<tr>";
        echo "<td>{$item['product_name']}</td>";
        echo "<td>{$item['price']}</td>";
        echo "<td>{$item['quantity']}</td>";
        echo "<td><form method='post' action='cart.php'>
                    <input type='hidden' name='item_index' value='{$index}'>
                    <button type='submit' name='remove_from_cart'>Delete</button>
                  </form></td>";
        echo "</tr>";
    }
}

if (isset($_POST['remove_from_cart'])) {
    $itemIndex = $_POST['item_index'];

    if (isset($_SESSION['cart'][$itemIndex])) {
        unset($_SESSION['cart'][$itemIndex]);

        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
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
        
            <div id="popup" class="popup">

<div class="popup-content">

    <span class="close" onclick="document.getElementById('popup').style.display = 'none'">&times;</span>

    <form method="post" action="login.php" class="login">

    <h1>Sign in</h1>

    <input type="text" name="username" placeholder="Username" required><br><br>

    <input type="password" name="password" placeholder="Password" required><br><br>

    <input type="submit">
        
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
        <input type="text" name="email" placeholder="Email"><br><br>
        <input type="text" name="phone" placeholder="Phone number"><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <input type="password" name="password2" placeholder="Repeat Password"><br><br>

        <button type="submit">Zarejestruj</button><br><br>
    </form>
</div>
</div>
<div id="popup" class="popup">
        <div class="popup-content">
            <span class="close" onclick="document.getElementById('popup').style.display = 'none'">&times;</span>
            <p>You must be logged in to access the cart.</p>
        </div>
    </div>

    <div id="cart">
        <div id="cart-content">
            <table id="tc">
                <th>
                    <h1>Cart</h1>
                </th>
                <tr>
                    <td>NAME</td>
                    <td>PRICE</td>
                    <td>QUANTITY</td>
                    <td>Delete</td>
                </tr>
                <?php
                displayCartItems();
                ?>
            </table>
        </div>
    </div>
</body>
</html>