<?php
session_start();
include("server/connection.php");

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['popupMessage'] = 'Please log in first!';
    $_SESSION['popupType'] = 'error';
    header('Location: loginreg.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    $_SESSION['popupMessage'] = 'User not found!';
    $_SESSION['popupType'] = 'error';
    header('Location: loginreg.php');
    exit();
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/profile_update.css">
    <link rel="icon" type="image/x-icon" href="assets/innDays icon.png">
    <title>Profile - innDays</title>
</head>

<body>

    <?php
    if (isset($_SESSION['popupMessage']) && isset($_SESSION['popupType'])):
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                showPopup('<?= $_SESSION['popupType'] ?>', '<?= addslashes($_SESSION['popupMessage']) ?>');
            });
        </script>
        <?php
        unset($_SESSION['popupMessage']);
        unset($_SESSION['popupType']);
    endif;
    ?>

    <?php include 'header.php'; ?>

    <div class="main-content">
        <div class="profile-card">

            <!-- Navbar for Switching Sections -->
            <div class="profile-navbar">
                <button class="tab-btn active" onclick="showSection('profile-section')">Profile</button>
                <button class="tab-btn" onclick="showSection('booking-requests-section')">Booking Requests</button>
                <button class="tab-btn" onclick="showSection('rented-properties-section')">Rented
                    Properties</button>
            </div>

            <!-- Profile Section -->
            <div id="profile-section" class="tab-content">
                <h2>Update your profile</h2>
                <form action="server/profile_controller.php" method="POST">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

                    <label for="contact">Contact:</label>
                    <input type="text" id="contact" name="contact" value="<?= htmlspecialchars($user['contact']) ?>"
                        required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>"
                        required>

                    <label for="password">New Password:</label>
                    <input type="password" id="password" name="password"
                        placeholder="Leave blank to keep current password">

                    <button type="submit" class="update-profile-btn">Update Profile</button>
                </form>
            </div>

            <!-- Booking Requests Section -->
            <div id="booking-requests-section" class="tab-content hidden">
                <h2>Booking Requests</h2>
                <?php
                $booking_sql = "SELECT b.booking_id AS booking_id, b.status, u.name AS requester_name, l.property_title 
                                FROM booking b
                                JOIN users u ON b.user_id = u.id
                                JOIN listings l ON b.property_id = l.property_id
                                WHERE b.owner_id = ?";
                $booking_stmt = $conn->prepare($booking_sql);
                $booking_stmt->bind_param("i", $user_id);
                $booking_stmt->execute();
                $booking_result = $booking_stmt->get_result();
                ?>

                <?php if ($booking_result->num_rows > 0): ?>
                    <table>
                        <tr>
                            <th>Requester</th>
                            <th>Property</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        <?php while ($booking = $booking_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($booking['requester_name']) ?></td>
                                <td><?= htmlspecialchars($booking['property_title']) ?></td>
                                <td><?= ucfirst($booking['status']) ?></td>
                                <td>
                                    <form action="server/update_booking.php" method="POST" class="booking-requests-form">
                                        <input type="hidden" name="booking_id" value="<?= $booking['booking_id'] ?>">
                                        <button type="submit" name="status" value="accepted" class="request-accepted">Accept</button>
                                        <button type="submit" name="status" value="declined">Decline</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                <?php else: ?>
                    <p>No booking requests found.</p>
                <?php endif; ?>
            </div>

            <!-- Rented Properties Section -->
            <div id="rented-properties-section" class="tab-content hidden">
                <h2>Rented Properties</h2>
                <?php
                $rented_sql = "SELECT b.booking_id AS booking_id, b.status, l.property_title 
                            FROM booking b
                            JOIN listings l ON b.property_id = l.property_id
                            WHERE b.user_id = ?";
                $rented_stmt = $conn->prepare($rented_sql);
                $rented_stmt->bind_param("i", $user_id);
                $rented_stmt->execute();
                $rented_result = $rented_stmt->get_result();
                ?>

                <?php if ($rented_result->num_rows > 0): ?>
                    <table>
                        <tr>
                            <th>Property</th>
                            <th>Status</th>
                        </tr>
                        <?php while ($rented = $rented_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($rented['property_title']) ?></td>
                                <td><?= ucfirst($rented['status']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                <?php else: ?>
                    <p>You have not rented any properties.</p>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <script src="js/profile_popup.js"></script>
    <script>
        function showSection(sectionId) {
            document.querySelectorAll('.tab-content').forEach(section => section.classList.add('hidden'));
            document.getElementById(sectionId).classList.remove('hidden');

            document.querySelectorAll('.tab-btn').forEach(button => button.classList.remove('active'));
            document.querySelector(`[onclick="showSection('${sectionId}')"]`).classList.add('active');
        }
    </script>

</body>

</html>

<?php
// Fetch booking requests for properties owned by the logged-in user
$booking_sql = "SELECT b.booking_id AS booking_id, b.status, u.name AS requester_name, l.property_title 
                FROM booking b
                JOIN users u ON b.user_id = u.id
                JOIN listings l ON b.property_id = l.property_id
                WHERE b.owner_id = ?";

$booking_stmt = $conn->prepare($booking_sql);
$booking_stmt->bind_param("i", $user_id);
$booking_stmt->execute();
$booking_result = $booking_stmt->get_result();
?>

<!-- <div class="booking-requests">
    <h2>Booking Requests</h2>
    <?php if ($booking_result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Requester</th>
                <th>Property</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($booking = $booking_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($booking['requester_name']) ?></td>
                    <td><?= htmlspecialchars($booking['property_title']) ?></td>
                    <td><?= ucfirst($booking['status']) ?></td>
                    <td>
                        <form action="server/update_booking.php" method="POST">
                            <input type="hidden" name="booking_id" value="<?= $booking['booking_id'] ?>">
                            <button type="submit" name="status" value="accepted">Accept</button>
                            <button type="submit" name="status" value="declined">Decline</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No booking requests found.</p>
    <?php endif; ?>
</div>

<?php
// Fetch rented properties for the logged-in user and show booking status
$rented_sql = "SELECT b.booking_id AS booking_id, b.status, l.property_title 
               FROM booking b
               JOIN listings l ON b.property_id = l.property_id
               WHERE b.user_id = ?";

$rented_stmt = $conn->prepare($rented_sql);
$rented_stmt->bind_param("i", $user_id);
$rented_stmt->execute();
$rented_result = $rented_stmt->get_result();
?>

<div class="rented-properties">
    <h2>Your Rented Properties</h2>
    <?php if ($rented_result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Property</th>
                <th>Status</th>
            </tr>
            <?php while ($rented = $rented_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($rented['property_title']) ?></td>
                    <td><?= ucfirst($rented['status']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>You have not rented any properties.</p>
    <?php endif; ?>
</div> -->

<?php $rented_stmt->close(); ?>