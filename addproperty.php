<?php
// Start session to fetch popup message
session_start();

// Define the page title and current page
$pageTitle = "Add Property - innDays";
$currentPage = "home";

// Include the reusable header
include 'header.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "User not logged in.";
    exit();
}

$user_id = $_SESSION['user_id'];
require_once 'server/connection.php';

// Fetch user data from the database
$stmt = $conn->prepare("SELECT name, email, contact FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_name = $row['name'];
    $user_email = $row['email'];
    $user_contact = $row['contact'];
} else {
    echo "User not found.";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" type="text/css" href="css/addproperty.css">
</head>
<body>

<!-- Popup Message Handling -->
<?php if (isset($_SESSION['popupMessage']) && isset($_SESSION['popupType'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            showPopup('<?= $_SESSION['popupType'] ?>', '<?= addslashes($_SESSION['popupMessage']) ?>');
        });
    </script>
    <?php unset($_SESSION['popupMessage'], $_SESSION['popupType']); ?>
<?php endif; ?>

<!-- Add Property Form -->
<form action="server/addproperty_controller.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="property_owner" value="<?php echo $user_name; ?>">

    <h2>What type of Property do you want to Offer?</h2>
    <p>Let's start with basic "typology" of the listing so that property seekers can find it under the right category on innDays.</p>

    <label for="property_type">Property Type Location</label><br>
    <div class="property-types">
        <button type="button" name="property_type" value="BEACH FRONT">Beach Front</button>
        <button type="button" name="property_type" value="LAKE VIEW">Lake View</button>
        <button type="button" name="property_type" value="MOUNTAIN SIDE">Mountain Side</button>
        <button type="button" name="property_type" value="RIVER SIDE">River Side</button>
        <input type="hidden" id="property_type_hidden" name="property_type" value="" required>
    </div><br><br>

    <label for="title">Title</label><br>
    <input type="text" id="title" name="title" placeholder="Eg. 2 Bedroom Apartment with SeaView" required><br><br>

    <label for="description">Description</label><br>
    <textarea id="description" name="description" required></textarea><br><br>

    <label for="price">Price (P)</label><br>
    <input type="number" id="price" name="price" required><br><br>

    <label for="availability">Availability</label><br>
    <select name="availability" id="availability" required>
        <option value="all">All</option>
        <option value="Today">Today</option>
        <option value="Next Week">Next Week</option>
        <option value="Next Month">Next Month</option>
    </select><br><br>

    <h2 for="photos">Upload your Photos!</h2><br>
    <p>Users looking for property ignore properties without photos. Make your property stand out by uploading photos.</p>
    <div id="photo-uploads">
        <input type="file" name="photo1" accept="image/*" required><br>
        <input type="file" name="photo2" accept="image/*" required><br>
        <input type="file" name="photo3" accept="image/*" required><br>
        <input type="file" name="photo4" accept="image/*" required><br>
        <input type="file" name="photo5" accept="image/*" required><br>
    </div><br>

    <div class="location-section">
        <h2>Where is your property located?</h2>
        <label for="province">Province</label><br>
        <input type="text" id="province" name="province" required><br><br>

        <label for="city">City</label><br>
        <input type="text" id="city" name="city" required><br><br>

        <label for="barangay">Barangay</label><br>
        <input type="text" id="barangay" name="barangay" required><br><br>

        <label for="full_address">Full Address</label><br>
        <input type="text" id="full_address" name="full_address" required><br><br>
    </div>

    <div class="contact-details">
        <h2>Please review your contact details</h2>
        <p>Make sure your details are updated so our users can easily contact you at the right channel.</p>

        <div class="contact-info">
            <div class="icon-text-container">
                <div class="icon-container"><img src="assets/user.png" alt="User Icon"></div>
                <div class="info-container">
                    <span class="contact-name"><?php echo $user_name; ?></span><br>
                    <span class="contact-email"><?php echo $user_email; ?></span><br>
                    <span class="contact-phone"><?php echo $user_contact; ?></span><br>
                </div>
            </div>
        </div>
    </div><br>

    <div class="buttons">
        <button type="button" class="cancel-button" onclick="window.location.href='index.php';">Cancel</button>
        <input type="submit" value="Publish your Property">
    </div>
</form>

<script>
    // Handling property type selection
    const propertyTypeButtons = document.querySelectorAll('.property-types button');
    const propertyTypeHidden = document.getElementById('property_type_hidden');

    propertyTypeButtons.forEach(button => {
        button.addEventListener('click', () => {
            propertyTypeButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            propertyTypeHidden.value = button.value;
        });
    });

    // Popup message function
    function showPopup(type, message) {
        let popup = document.createElement("div");
        popup.className = "popup " + type;
        popup.innerHTML = `${message}`;

        document.body.appendChild(popup);

        setTimeout(() => {
            popup.style.opacity = "0";
            setTimeout(() => popup.remove(), 500);
        }, 3000);
    }
</script>

</body>
</html>
