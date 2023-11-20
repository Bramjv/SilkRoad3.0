<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
$totalPrice=0;
function displayCartItems() {
    global $totalPrice;
    foreach ($_SESSION['cart'] as $index => $item) {
        echo "<tr id='bld'>";
        echo "<td id='bld'>{$item['product_name']}</td>";
        echo "<td id='bld'>{$item['price']}</td>";
        echo "<td id='bld'>{$item['quantity']}</td>";
        echo "<td id='bld'><form method='post' action='cart.php'>
                    <input type='hidden' name='item_index' value='{$index}'>
                    <button type='submit' name='remove_from_cart'>Delete</button>
                  </form></td>";
        echo "</tr>";

        $totalPrice += ($item['price'] * $item['quantity']);
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
    <title>Document</title>
</head>
<body>
    <header id="header">

        <div id="logo">
        <a href="index.php"><img src="fin.png" id="l1"></a>
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
<div id="popup" class="popup">
        <div class="popup-content">
            <span class="close" onclick="document.getElementById('popup').style.display = 'none'">&times;</span>
            <p>You must be logged in to access the cart.</p>
        </div>
    </div>

    <div id="cart">
        <div id="cart-content">
            <h1 id="crtt">Cart</h1>
            <table id="tc">
                    
                <tr id="bld">
                    <td id="blde">NAME</td>
                    <td id="blde">PRICE</td>
                    <td id="blde">QUANTITY</td>
                    <td id="blde">Delete</td>
                </tr>
                <?php
                displayCartItems();
                ?>
            </table>
            <div id="vall">
            <div id="lerf">
            <span id="pwl">Value:
            <?php echo number_format($totalPrice, 2); ?>
            </span>
            &nbsp
            <a href="transaction.html"><button>buy</button></a>
            </div>
            </div>
        </div>
    </div>
</body>
</html>