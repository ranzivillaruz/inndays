<?php
// Database configuration
$host = 'localhost'; // Database host
$user = 'root'; // Database username
$password = ''; // Database password
$dbname = 'inndays'; // Database name

// Start a session
session_start();

// Create a database connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    // Check if user exists
    if ($email && $password) {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                echo 'Login successful! Welcome, ' . htmlspecialchars($user['name']) . '.';
                // Redirect to a dashboard or home page (optional)
                // header('Location: dashboard.php');
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

// Close connection
$conn->close();
?>