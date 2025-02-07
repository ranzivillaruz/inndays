<?php
session_start(); // Ensure the session is started

$pageTitle = "Listings - innDays";
$currentPage = "home";
include 'header.php';
require_once 'server/connection.php';

$row = null; // Initialize variable

// Ensure the user is logged in and has a name (or ID) in the session
$currentUser = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;


if (isset($_GET['id'])) {
    $propertyId = $_GET['id'];

    // Check if the property is already booked
    $bookingStatusQuery = "SELECT status FROM booking WHERE property_id = ? AND status = 'accepted' LIMIT 1";
    $statusStmt = $conn->prepare($bookingStatusQuery);
    $statusStmt->bind_param("i", $propertyId);
    $statusStmt->execute();
    $statusResult = $statusStmt->get_result();

    $isBooked = $statusResult->num_rows > 0; // Property is booked if a matching record is found

    // Check if the user has a pending booking
    $pendingBookingQuery = "SELECT status FROM booking WHERE property_id = ? AND user_id = ? AND status = 'pending' LIMIT 1";

    $pendingStmt = $conn->prepare($pendingBookingQuery);
    $pendingStmt->bind_param("is", $propertyId, $currentUser);
    $pendingStmt->execute();
    $pendingResult = $pendingStmt->get_result();
    
    $hasPendingBooking = $pendingResult->num_rows > 0; // User has a pending booking

    // Fetch property details
    $sql = "SELECT Listings.*, Users.email AS property_email, Users.contact AS property_contact 
            FROM Listings 
            LEFT JOIN Users ON Listings.property_owner = Users.name 
            WHERE Listings.property_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $propertyId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Ensure email and contact are set
        $propertyEmail = !empty($row['property_email']) ? $row['property_email'] : 'Not available';
        $propertyContact = !empty($row['property_contact']) ? $row['property_contact'] : 'Not available';
         // Add MIME types for images:
            for ($i = 1; $i <= 5; $i++) {
                $picField = "property_pic" . $i;
                $picTypeField = "property_pic" . $i . "_type";
    
                $row[$picTypeField] = ''; // Initialize
    
                if (!empty($row[$picField])) {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = finfo_file($finfo, 'data:image/jpeg;base64,' . base64_encode($row[$picField]));
                    finfo_close($finfo);
                    $row[$picTypeField] = $mime;
                }
            }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Property Details</title>
    <link rel="stylesheet" href="css/property_details.css">
    <script>
        function openModal() {
            document.getElementById('bookingModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('bookingModal').style.display = 'none';
        }
    </script>
</head>

<body>
    <div class="container">
        <a href="listings.php" class="go-back">&lt; Go Back</a>

        <?php if ($row): ?>
            <div class="property-details">
            <div class="image-gallery">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <?php if (!empty($row['property_pic' . $i])): ?>
                            <img src="data:<?= $row['property_pic' . $i . '_type'] ?>;base64,<?= base64_encode($row['property_pic' . $i]) ?>"
                                alt="Property Image <?= $i ?>">
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
                <div class="details-content">
                    <div class="book-container">
                        <h1><?= htmlspecialchars($row['property_title']) ?></h1>

                        <?php if ($currentUser && $currentUser !== $row['property_owner']): ?>
                            <?php if ($isBooked): ?>
                                <p class="booked-warning">This property is already booked.</p>
                            <?php elseif ($hasPendingBooking): ?>
                                <p class="pending-warning">Your booking request is pending.</p>
                            <?php else: ?>
                                <button class="book-now-btn" onclick="openModal()">Book Now</button>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="owner-warning">You cannot book your own property.</p>
                        <?php endif; ?>
                    </div>
                    <p class="price">₱<?= htmlspecialchars($row['property_price']) ?></p>
                    <p class="description"><?= htmlspecialchars($row['property_desc']) ?></p>
                    <p class="location">
                        <img src="assets/loc.svg" alt="Location Icon">
                        <?= htmlspecialchars($row['property_address']) ?>
                    </p>
                </div>
            </div>

            <div id="bookingModal" class="modal">
                <div class="modal-content">
                    <h2>Confirm Booking</h2>
                    <p>Are you sure you want to book:</p>
                    <p><strong><?= htmlspecialchars($row['property_title']) ?></strong></p>
                    <p>Price: ₱<?= htmlspecialchars($row['property_price']) ?></p>
                    <p>Location: <?= htmlspecialchars($row['property_address']) ?></p>
                    <form action="book_property.php" method="POST">
                        <input type="hidden" name="property_id" value="<?= $propertyId ?>">
                        <input type="hidden" name="owner_id" value="<?= $row['property_owner'] ?>">
                        <div class="modal-buttons">
                            <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
                            <button type="submit" class="confirm-btn">Confirm Booking</button>
                        </div>
                    </form>
                </div>
            </div>

        <?php else: ?>
            <p style="color: red; text-align: center; font-size: 20px;">Property not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
