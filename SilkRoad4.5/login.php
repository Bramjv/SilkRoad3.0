<?php
session_start();
$conn = new mysqli("localhost", "root", "", "SilkRoad");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $passwordInput = $_POST["password"];

    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();

            $dbPassword = $row['password'];
            if (password_verify($passwordInput, $dbPassword)) {
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["username"] = $row["username"];

                header("Location: index.php");
                exit();
            } else {
                $login_error = "Invalid password";
                echo "<br>Redirecting to index.php in 5 seconds...";
            header("refresh:5;url=index.php");
            }
        } else {
            $login_error = "Invalid username";
            echo "<br>Redirecting to index.php in 5 seconds...";
            header("refresh:5;url=index.php");
        }
    } else {
        $login_error = "Error executing login query: " . $stmt->error;
        echo "<br>Redirecting to index.php in 5 seconds...";
            header("refresh:5;url=index.php");
    }    
}

$conn->close();
?>
