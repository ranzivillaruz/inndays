<?php
require_once '../server/connection.php';
session_start();

// Ensure owner is logged in
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['status'])) {
    $bookingId = $_POST['booking_id'];
    $status = $_POST['status'];

    $update_stmt = $conn->prepare("UPDATE booking SET status = ? WHERE booking_id = ?");
$update_stmt->bind_param("si", $status, $bookingId);


    if ($update_stmt->execute()) {
        $_SESSION['popupMessage'] = 'Booking status updated successfully!';
        $_SESSION['popupType'] = 'success';
    } else {
        $_SESSION['popupMessage'] = 'Error updating booking status.';
        $_SESSION['popupType'] = 'error';
    }

    $update_stmt->close();
    header("Location: ../profile.php");
    exit();
}

$conn->close();
?>
