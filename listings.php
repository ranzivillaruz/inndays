<?php
// Define the page title and current page for the header
$pageTitle = "Listings - innDays";
$currentPage = "home";

// Include the reusable header
include 'header.php';

// Include the database connection
require_once 'server/connection.php';

// Fetch all listings from the database
$sql = "SELECT * FROM Listings";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listings - innDays</title>
    <link rel="stylesheet" href="css/listing.css">
</head>

<body>
    <div class="listing_header">
        <div class="header_items">
            <div class="header_item">
                <h3>OFFER TYPE</h3>
                <select name="offer-type" id="offer-type">
                    <option value="all">All</option>
                    <option value="beach_front">Beach Front</option>
                    <option value="lake_view">Lake View</option>
                    <option value="mountain_side">Mountain Side</option>
                </select>
            </div>
            <div class="header_item">
                <h3>PRICES</h3>
                <select name="prices" id="prices">
                    <option value="all">All</option>
                    <option value="low">₱5 000 - ₱10 000</option>
                    <option value="medium">₱11 000 - ₱15 000</option>
                    <option value="high">₱16 000 up</option>
                </select>
            </div>
            <div class="header_item">
                <h3>AVAILABILITY</h3>
                <select name="availability" id="availability">
                    <option value="all">All</option>
                    <option value="today">Today</option>
                    <option value="week">Next Week</option>
                    <option value="month">Next Month</option>
                </select>
            </div>
        </div>
        <button class="add_listing" onclick="window.location.href='addproperty.php';">Filter</button>
    </div>

    <div class="card-container">

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="card">';

                echo '<div class="card-image-container">'; // Start of image container
        
                // Array to hold all image data URIs for this card
                $imageURIs = [];

                // Display the first image (property_pic1) using BLOB
                if (!empty($row['property_pic1'])) {
                    $imageData = $row['property_pic1'];
                    $imageType = 'image/jpeg'; // Default, but we'll try to detect
        
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = finfo_file($finfo, 'data:image/jpeg;base64,' . base64_encode($imageData));
                    finfo_close($finfo);

                    if ($mime !== false) {
                        $imageType = $mime;
                    }

                    $dataUri = 'data:' . $imageType . ';base64,' . base64_encode($imageData); // Correct Data URI
                    $imageURIs[] = $dataUri; // Add to the array
                    echo '<img src="' . $dataUri . '" alt="Property Image">';

                } else {
                    // Placeholder image
                    echo '<img src="assets/placeholder.jpg" alt="No Image Available">';
                }

                // Display other images (property_pic2 to property_pic5)
                for ($i = 2; $i <= 5; $i++) {
                    $picColumn = 'property_pic' . $i;
                    if (!empty($row[$picColumn])) {
                        $imageData = $row[$picColumn];
                        $imageType = 'image/jpeg'; // Default
        
                        // Use the provided MIME type directly
                        $mime = 'data:image/jpeg;base64';

                        $dataUri = $mime . ',' . base64_encode($imageData);
                        $imageURIs[] = $dataUri; // Add to the array
                        echo '<img src="' . $dataUri . '" alt="Property Image ' . $i . '" class="additional-image">'; // Add class
                    }
                }

                echo '</div>'; // End of image container
        
                echo '<div class="card-content">';
                echo '<h3>' . $row['property_title'] . '</h4>';
                echo '<p>Price: ₱' . $row['property_price'] . '</p>';
                echo '<p>' . $row['property_desc'] . '</p>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "No listings found.";
        }
        ?>

    </div>
    <script>
        const cards = document.querySelectorAll('.card');

        cards.forEach(card => {
            const imageContainer = card.querySelector('.card-image-container');
            // No Arrow functionality for now
        });
    </script>
</body>

</html>