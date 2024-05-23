<?php
include("db-conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the username and password fields are filled
    if (!isset($_POST['username'], $_POST['password'])) {
        exit('Please fill both the username and password fields!');
    }

    // Prepare SQL statement to prevent SQL injection
    if ($stmt = $con->prepare('SELECT id FROM accounts WHERE username = ?')) {
        $stmt->bind_param('s', $_POST['username']);
        $stmt->execute();
        $stmt->store_result();

        // Check if the username already exists
        if ($stmt->num_rows > 0) {
            echo 'Username already exists, please choose another!';
        } else {
            // Username doesn't exist, proceed with the registration
            if ($stmt = $con->prepare('INSERT INTO accounts (username, password) VALUES (?, ?)')) {
                // Hash the password
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $stmt->bind_param('ss', $_POST['username'], $password);
                $stmt->execute();
                echo 'You have successfully registered! You can now <a href="index.html">login</a>';
            } else {
                // SQL statement failed
                echo 'Could not prepare statement!';
            }
        }
        $stmt->close();
    } else {
        // SQL statement failed
        echo 'Could not prepare statement!';
    }
} else {
    // Show registration form
    header("Location: register.html");
    exit();
}
?>
