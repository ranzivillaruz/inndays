<?php
session_start(); // Start the session 
include("connection.php");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : null;
    $contact = isset($_POST['contact']) ? $conn->real_escape_string($_POST['contact']) : null;
    $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : null;
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    // Check if email already exists
    $emailCheck = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($emailCheck);

    if ($result->num_rows > 0) {
        $_SESSION['popupMessage'] = 'Account already exists with this email!';
        $_SESSION['popupType'] = 'error';
    } else {
        if ($name && $contact && $email && $password) {
            $sql = "INSERT INTO users (name, contact, email, password) VALUES ('$name', '$contact', '$email', '$password')";
            if ($conn->query($sql) === TRUE) {
                $_SESSION['popupMessage'] = 'Registration successful!';
                $_SESSION['popupType'] = 'success';
            } else {
                $_SESSION['popupMessage'] = 'Error: ' . $conn->error;
                $_SESSION['popupType'] = 'error';
            }
        } else {
            $_SESSION['popupMessage'] = 'Please fill all fields!';
            $_SESSION['popupType'] = 'error';
        }
    }
}

// Close connection
$conn->close();

// Redirect back to the loginreg.php page
header('Location: ../loginreg.php');
exit();
?>
