<?php 
session_start();
include("connection.php");

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['popupMessage'] = 'Please log in first!';
    $_SESSION['popupType'] = 'error';
    header('Location: ../loginreg.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$old_user_name = $_SESSION['user_name']; // Store the old username before update

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($name) || empty($contact) || empty($email)) {
        $_SESSION['popupMessage'] = 'Please fill in all required fields!';
        $_SESSION['popupType'] = 'error';
        header('Location: ../profile.php');
        exit();
    }

    // Update users table
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET name = ?, contact = ?, email = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $contact, $email, $hashed_password, $user_id);
    } else {
        $sql = "UPDATE users SET name = ?, contact = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $contact, $email, $user_id);
    }

    if ($stmt->execute()) {
        // Update listings table only if the username has changed
        if ($old_user_name !== $name) {
            $updateListingsSql = "UPDATE listings SET property_owner = ? WHERE property_owner = ?";
            $updateListingsStmt = $conn->prepare($updateListingsSql);
            $updateListingsStmt->bind_param("ss", $name, $old_user_name);
            $updateListingsStmt->execute();
            $updateListingsStmt->close();
        }

        // Update session variables
        $_SESSION['user_name'] = $name;

        $_SESSION['popupMessage'] = 'Profile updated successfully!';
        $_SESSION['popupType'] = 'success';
    } else {
        $_SESSION['popupMessage'] = 'Error updating profile: ' . $stmt->error;
        $_SESSION['popupType'] = 'error';
    }

    $stmt->close();
    $conn->close();

    // Redirect to profile page
    header('Location: ../profile.php');
    exit();
}
?>
