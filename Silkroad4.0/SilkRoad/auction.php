<?php
$conn = new mysqli("localhost", "root", "", "aukcja");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            echo "<br> File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "<br> File is not an image.";
            $uploadOk = 0;
        }
    }

    if ($_FILES["image"]["size"] > 50000000) {
        echo "<br> Sorry, your file is too large.";
        $uploadOk = 0;
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        echo "<br> Sorry, only JPG, JPEG and PNG files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "<br> Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "<br> The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";
            $image_path = $target_file;

            $product_name = $_POST['product_name'];
            $price = $_POST['price'];
            $quantity = $_POST['quantity'];
            $description = $_POST['description'];
            $user_id = 1;

            $sql = "INSERT INTO auction_items (product_name, price, quantity, description, image_path, user_id) 
                    VALUES ('$product_name', $price, $quantity, '$description', '$image_path', $user_id)";

            if ($conn->query($sql) === TRUE) {
                echo "<br> Auction item added successfully!";
            } else {
                echo "<br> Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "<br> Sorry, there was an error uploading your file.";
        }
    }
}

$conn->close();
?>