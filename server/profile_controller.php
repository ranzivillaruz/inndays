<?php
session_start();
include("connection.php");

if (!isset($_SESSION['user_id'])) {
    $_SESSION['popupMessage'] = 'Please log in first!';
    $_SESSION['popupType'] = 'error';
    header('Location: ../loginreg.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $name = isset($_POST['name']) ? $conn->real_escape_string(trim($_POST['name'])) : '';
    $contact = isset($_POST['contact']) ? $conn->real_escape_string(trim($_POST['contact'])) : '';
    $email = isset($_POST['email']) ? $conn->real_escape_string(trim($_POST['email'])) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Basic validation
    if (empty($name) || empty($contact) || empty($email)) {
        $_SESSION['popupMessage'] = 'Please fill in all required fields!';
        $_SESSION['popupType'] = 'error';
        header('Location: ../profile.php');
        exit();
    }

    // Build update query
    $updateSql = "UPDATE users SET 
        name = '$name', 
        contact = '$contact', 
        email = '$email'";

    // Add password update only if provided
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $updateSql .= ", password = '$hashed_password'";
    }

    $updateSql .= " WHERE id = '$user_id'";

    // Execute query
    if ($conn->query($updateSql)) {
        $_SESSION['popupMessage'] = 'Profile updated successfully!';
        $_SESSION['popupType'] = 'success';
    } else {
        $_SESSION['popupMessage'] = 'Error updating profile: ' . $conn->error;
        $_SESSION['popupType'] = 'error';
    }

    // Redirect back to profile page with correct path
    header('Location: ../profile.php');
    exit();
}

$conn->close();