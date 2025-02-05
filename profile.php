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
    <link rel="stylesheet" href="./css/profile_update.css" />
    <link rel="icon" type="image/x-icon" href="assets/icon.png">
    <title>Profile</title>
</head>
<body>

<?php 
if (isset($_SESSION['popupMessage']) && isset($_SESSION['popupType'])): 
?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
        <h2>Profile</h2>
        <form action="server/profile_controller.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

            <label for="contact">Contact:</label>
            <input type="text" id="contact" name="contact" value="<?= htmlspecialchars($user['contact']) ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" placeholder="Leave blank to keep current password">

            <button type="submit">Update Profile</button>
        </form>
    </div>
</div>

<div id="popupMessage" class="popup hidden">
  <p class="popup-message"></p>
</div>

<script src="js/profile_popup.js"></script>
</body>
</html>
