<?php
$conn = new mysqli("localhost", "root", "", "aukcja");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM auction_items";
$result = $conn->query($sql);

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
    <title>Document</title>
</head>
<body>
    <header id="header">

        <div id="logo">
            <a href="index.html" id="p090">
            <img src="fin.png" id="l1" >
            <p><h2 style="font-family: 'Hurricane';">SilkRoad</h2></p>
        </a>
        </div>

        <div id="p1">
            <table id="t1">
                <tr id="h">
                    <td colspan="2"><b>You can pay with:</b></td>
                </tr>
                <tr>
                    <td><p>Bitcone</p></td>
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
            <img src="cart.png" id="c1">
        </div>

     

        <div id="lr">
<button type="submit" id="b1" onclick="document.getElementById('popup').style.display = 'block'">Sign in<div><img src="login.png"></div> </button>
<br><br>
<button type="submit" id="b2" onclick="document.getElementById('popup-register').style.display = 'block'">Sign up<div><img src="register.png" ></div></button>
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

                <input type="text" name="Imie" placeholder="Name" required><br><br>
                <input type="text" name="Nazwisko" placeholder="Surname" required><br><br>
                <input type="text" name="Email" placeholder="Email(optional)"><br><br>
                <input type="text" name="numer_telefonu" placeholder="Phone number(optional)"><br><br>
                <input type="password" name="password" placeholder="Password" required><br><br>
                <input type="password" name="password2" placeholder="Repeat Password"><br><br>

                <button type="submit">Zarejestruj</button><br><br>
            </form>
        </div>
    </div>

<aside id="Categories">
    <div class="dropdown">
        <button class="dropbtn"><h1>Categories</h1></button>

        <div class="dropdown-content">

            <a href="#">Categories</a>
            <a href="#">Categories</a>

            <div class="sub-dropdown">
        <button class="sub-dropbtn"><h3>T-Shirt</h3></button>

                <div class="sub-dropdown-content">
                    <a href="#">Black 50% off</a>
                    <a href="#">Normal</a>
                </div>
            </div>
            
        </div>
    </div>
</aside>
<section id="ss">

<?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div id='p'>";
            echo "<img src='{$row['image_path']}' alt='{$row['product_name']}' id='pf'>";
            echo "<p>{$row['product_name']}</p>";
            echo "<p>Price: {$row['price']}</p>";
            echo "<p>Quantity: {$row['quantity']}</p>";
            echo "</div>";
        }
    } else {
        echo "<p>No auction items available.</p>";
    }
    ?>

</section>
<footer id="footer">

</footer>
</body>
</html>