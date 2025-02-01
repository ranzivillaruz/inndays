<?php
session_start(); // Start the session 
include("connection.php");


// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    if ($email && $password) {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
           
            if (password_verify($password, $user['password'])) {
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                
    
                header('Location: /inndays/index.php');
                exit(); 
            } else {
                echo 'Invalid password!';
            }
        } else {
            echo 'No user found with that email!';
        }
    } else {
        echo 'Please fill all fields!';
    }
}


$conn->close();
?>
