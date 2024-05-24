<?php
include("db-conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['username'], $_POST['password'])) {
        exit('Please fill both the username and password fields!');
    }

    if ($stmt = $con->prepare('SELECT id FROM accounts WHERE username = ?')) {
        $stmt->bind_param('s', $_POST['username']);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo 'Username already exists, please choose another!';
        } else {
            if ($stmt = $con->prepare('INSERT INTO accounts (username, password) VALUES (?, ?)')) {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $stmt->bind_param('ss', $_POST['username'], $password);
                $stmt->execute();
                echo 'You have successfully registered! You can now <a href="index.html">login</a>';
            } else {
                echo 'Could not prepare statement!';
            }
        }
        $stmt->close();
    } else {
        echo 'Could not prepare statement!';
    }
} else {
    header("Location: register.html");
    exit();
}
?>
