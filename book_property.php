<?php
session_start();
require_once 'server/connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['popupMessage'] = 'Please log in first!';
    $_SESSION['popupType'] = 'error';
    header('Location: loginreg.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$property_id = $_POST['property_id'];

// Get the property owner ID using the name stored in listings
$owner_query = "SELECT Users.id AS owner_id FROM Listings 
                JOIN Users ON Listings.property_owner = Users.name
                WHERE Listings.property_id = ?";
$stmt = $conn->prepare($owner_query);
$stmt->bind_param("i", $property_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $owner_id = $row['owner_id'];  // This gets the correct ID

    // Insert the booking request
    $insert_query = "INSERT INTO booking (property_id, user_id, owner_id, status) VALUES (?, ?, ?, 'pending')";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("iii", $property_id, $user_id, $owner_id);

    if ($stmt->execute()) {
        $_SESSION['popupMessage'] = 'Booking request sent successfully!';
        $_SESSION['popupType'] = 'success';
    } else {
        $_SESSION['popupMessage'] = 'Booking failed. Please try again.';
        $_SESSION['popupType'] = 'error';
    }
    $stmt->close();
} else {
    $_SESSION['popupMessage'] = 'Property owner not found!';
    $_SESSION['popupType'] = 'error';
}


$conn->close();
header("Location: property_details.php?id=" . $property_id);
exit();
?>