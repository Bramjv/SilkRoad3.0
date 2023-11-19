<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "aukcja");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$categoryQuery = "SELECT * FROM categories";
$categoryResult = $conn->query($categoryQuery);

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : 'default';

$searchSql = "SELECT auction_items.*, categories.name AS category_name 
              FROM auction_items 
              LEFT JOIN categories ON auction_items.category = categories.id 
              WHERE auction_items.product_name LIKE ? OR auction_items.description LIKE ?";

$searchStmt = $conn->prepare($searchSql);

$searchPattern = "%{$searchTerm}%";
$searchStmt->bind_param("ss", $searchPattern, $searchPattern);

$searchStmt->execute();
$searchResult = $searchStmt->get_result();

$categorySql = "SELECT auction_items.*, categories.name AS category_name FROM auction_items LEFT JOIN categories ON auction_items.category = categories.id";

if ($categoryFilter !== 'default') {
    $categorySql .= " WHERE auction_items.category = ?";
}

$categoryStmt = $conn->prepare($categorySql);

if ($categoryFilter !== 'default') {
    $categoryStmt->bind_param("s", $categoryFilter);
}

$categoryStmt->execute();
$resultCategories = $categoryStmt->get_result();

$searchStmt->close();
$categoryStmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Hurricane' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <title>Main Site</title>
</head>
<body>
    <header id="header">

        <div id="logo">
            <a href="index.php"><img src="fin.png" id="l1"></a>
            <p><h2 style="font-family: 'Hurricane';">SilkRoad</h2></p>
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
            <form method="get" action="index.php">
                <input type="text" placeholder="If it exists we have it..." id="si" name="search">
                <button type="submit" id="sb">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <div id="cart">
            <a href="cart.php"><img src="cart.png" id="c1"></a>
        </div>

        <div id="lr">
            <?php
            if (isset($_SESSION["username"])) {
                echo "<button>Welcome, {$_SESSION['username']}</button>";
                echo "<br><br>";
                echo "<a href='add_product.php'>Add Product</a>";
                echo "<br><br>";
                echo "<a href='logout.php'>Logout</a>";
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
        <input type="text" name="email" placeholder="Email(optional)"><br><br>
        <input type="text" name="phone" placeholder="Phone number(optional)"><br><br>
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
                <a href='index.php'>All Categories</a>
                <?php
                while ($rowCategory = $categoryResult->fetch_assoc()) {
                    echo "<a href='index.php?category={$rowCategory['id']}'>{$rowCategory['name']}</a>";
                }
                ?>
            </div>
        </div>
    </aside>

    <section id="ss">
        <?php
        if ($resultCategories->num_rows > 0) {
            while ($row = $resultCategories->fetch_assoc()) {
                echo "<div id='p'>";
                echo "<a href='item.php?id={$row['id']}'>";
                echo "<img src='{$row['image_path']}' alt='{$row['product_name']}' id='pf'>";
                echo "<p>{$row['product_name']}</p>";
                echo "</a>";
                echo "<p>Price: {$row['price']}</p>";
                echo "<p>Quantity: {$row['quantity']}</p>";
                echo "<p>Category: {$row['category_name']}</p>";
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
