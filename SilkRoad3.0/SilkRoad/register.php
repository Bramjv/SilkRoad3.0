<?php
$conn = new mysqli("localhost", "root", "", "aukcja");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = htmlspecialchars($_POST['username']);
    $name = htmlspecialchars($_POST['name']);
    $surname = htmlspecialchars($_POST['surname']);
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : null;
    $phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : null;
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    if ($password !== $password2) {
        echo "Passwords do not match.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, name, surname, email, phone, password) 
                VALUES ('$username', '$name', '$surname', '$email', '$phone', '$hashedPassword')";

        if ($conn->query($sql) === TRUE) {
            echo "Registration successful!<br>";
            echo "Redirecting to index.php in 5 seconds...";
            header("refresh:5;url=index.php");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
            echo "<br>Redirecting to index.php in 5 seconds...";
            header("refresh:5;url=index.php");
        }
    }
}

$conn->close();
?>
