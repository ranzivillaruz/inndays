<?php
session_start();
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;

    if ($email && $password) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header('Location: /inndays/index.php');
                exit();
            } else {
                $_SESSION['popupMessage'] = "Invalid password! Please try again.";
                $_SESSION['popupType'] = "error";
            }
        } else {
            $_SESSION['popupMessage'] = "No user found with that email! Please register first.";
            $_SESSION['popupType'] = "error";
        }
        $stmt->close();
    } else {
        $_SESSION['popupMessage'] = "Please fill all fields!";
        $_SESSION['popupType'] = "error";
    }
}
$conn->close();
header("Location: /inndays/loginreg.php");
exit();
