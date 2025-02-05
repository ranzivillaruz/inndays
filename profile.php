<?php
session_start();
include("server/connection.php");



if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM users WHERE id = '$user_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        $_SESSION['popupMessage'] = 'User not found!';
        $_SESSION['popupType'] = 'error';
        header('Location: loginreg.php');
        exit();
    }
} else {
    $_SESSION['popupMessage'] = 'Please log in first!';
    $_SESSION['popupType'] = 'error';
    header('Location: loginreg.php');
    exit();
}
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
    <!-- Add this right after the opening <body> tag -->
<?php 
if (isset($_SESSION['popupMessage']) && isset($_SESSION['popupType'])): 
?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showPopup('<?= $_SESSION['popupType'] ?>', '<?= addslashes($_SESSION['popupMessage']) ?>');
    });
</script>
<?php
    unset($_SESSION['popupType']);
    unset($_SESSION['popupMessage']);
endif;
?>
    
<?php include 'header.php'; ?>  <!-- Header is placed correctly here -->
    <div class="main-content">
        <div class="profile-card">
         <h2>Profile</h2>
            <form action="server/profile_controller.php" method="POST">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>

                <label for="contact">Contact:</label>
                <input type="text" id="contact" name="contact" value="<?= htmlspecialchars($user['contact'] ?? '') ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>

                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" placeholder="Leave blank to keep current password">

            <button type="submit">Update Profile</button>
        </form>
        </div>
    </div>
  <!-- In your HTML -->
<div id="popupMessage" class="popup hidden">
  <p class="popup-message"></p>
  
</div>
  
</div>
<script src="js/profile_popup.js"></script>
</body>
</html>