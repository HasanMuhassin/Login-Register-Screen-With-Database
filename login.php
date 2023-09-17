<?php

$username = $_POST['username'];
$password = $_POST['password'];

if (!empty($username) && !empty($password))
{
    $host = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "register";

    // Create connection
    $conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);

    if (mysqli_connect_error()) {
        die('Connect Error ('. mysqli_connect_errno() .') ' . mysqli_connect_error());
    }
    else {
        $SELECT = "SELECT username FROM login WHERE username = ?";
        $INSERT = "INSERT INTO login (username, password) VALUES (?, ?)";

        // Prepare statement
        $stmt = $conn->prepare($SELECT);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        // Checking if the username exists
        if ($stmt->num_rows == 0) {
            $stmt->close();
            
            // Hash the password before inserting it into the database
            //$hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $conn->prepare($INSERT);
            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
            echo "New record inserted successfully";
        } else {
            echo "Someone already registered using this username";
        }

        $stmt->close();
        $conn->close();
    }
} else {
    echo "Both username and password are required";
    die();
}
?>
