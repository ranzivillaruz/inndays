<?php
$pageTitle = "Listings - innDays";
$currentPage = "home";
include 'header.php';
require_once 'server/connection.php';

if (isset($_GET['id'])) {
    $propertyId = $_GET['id'];

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
    } else {
        echo "Property not found.";
        exit;
    }
} else {
    echo "No property ID specified.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Property Details</title>
    <link rel="stylesheet" href="css/property_details.css">
</head>

<body>
    <div class="container">
        <a href="listings.php" class="go-back">&lt; Go Back</a>

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
                <h1><?= htmlspecialchars($row['property_title']) ?></h1>
                <p class="price">₱<?= htmlspecialchars($row['property_price']) ?></p>
                <p class="description"><?= htmlspecialchars($row['property_desc']) ?></p>
                <p class="location">
                    <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($row['property_address']) ?>
                </p>

                <div class="contact-info">
                    <h2>Contact Information</h2>
                    <div class="contact-details">
                        <span class="contact-name">Owner: <?= htmlspecialchars($row['property_owner']) ?></span><br>
                        <span class="contact-email">Email: <?= htmlspecialchars($propertyEmail) ?></span><br>
                        <span class="contact-phone">Phone: <?= htmlspecialchars($propertyContact) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>