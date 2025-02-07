<?php
$pageTitle = "Listings - innDays";
$currentPage = "home";
include 'header.php';
require_once 'server/connection.php';

$sql = "SELECT * FROM listings WHERE 1"; // Start with 'WHERE 1' as a base condition

if (!empty($_GET['property_type'])) {
    $property_type = trim($conn->real_escape_string($_GET['property_type']));
    $sql .= " AND UPPER(property_type) = UPPER('$property_type')";
}

if (isset($_GET['prices']) && $_GET['prices'] != 'all') {
    switch ($_GET['prices']) {
        case 'low':
            $sql .= " AND property_price BETWEEN 0 AND 5000";
            break;
        case 'medium':
            $sql .= " AND property_price BETWEEN 5001 AND 10000";
            break;
        case 'high':
            $sql .= " AND property_price BETWEEN 10001 AND 20000";
            break;
        case 'high2': // Corrected case for "20000 and above"
            $sql .= " AND property_price > 20000";
            break;
    }
}

if (isset($_GET['property_availability']) && $_GET['property_availability'] != 'all') {
    $property_availability = $conn->real_escape_string($_GET['property_availability']);
    $sql .= " AND property_availability = '$property_availability'";
}

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
        <form action="" method="get">
            <div class="header_container">
            <div class="header_items">
                <div class="header_item">
                    <h3>OFFER TYPE</h3>
                    <select name="property_type" id="property_type">
                        <option value="">All</option>
                        <option value="MOUNTAIN SIDE">Mountain Side</option>
                        <option value="BEACH FRONT">Beach Front</option>
                        <option value="LAKE VIEW">Lake View</option>
                        <option value="RIVER SIDE">River Side</option>
                    </select>
                </div>
                <div class="header_item">
                    <h3>PRICES</h3>
                    <select name="prices" id="prices">
                        <option value="all">All</option>
                        <option value="low" <?php if (isset($_GET['prices']) && $_GET['prices'] == 'low')
                            echo 'selected'; ?>>₱0 - ₱5 000</option>
                        <option value="medium" <?php if (isset($_GET['prices']) && $_GET['prices'] == 'medium')
                            echo 'selected'; ?>>₱5 001 - ₱10 000</option>
                        <option value="high" <?php if (isset($_GET['prices']) && $_GET['prices'] == 'high')
                            echo 'selected'; ?>>₱10 001 - ₱20 000</option>
                        <option value="high2" <?php if (isset($_GET['prices']) && $_GET['prices'] == 'high' && $_GET['prices'] != 'medium' && $_GET['prices'] != 'low')
                            echo 'selected'; ?>>₱20 000 and above
                        </option>
                    </select>
                </div>
                <div class="header_item">
                    <h3>AVAILABILITY</h3>
                    <select name="property_availability" id="property_availability">
                        <option value="all">All</option>
                        <option value="today" <?php if (isset($_GET['property_availability']) && $_GET['property_availability'] == 'today')
                            echo 'selected'; ?>>Today</option>
                        <option value="week" <?php if (isset($_GET['property_availability']) && $_GET['property_availability'] == 'week')
                            echo 'selected'; ?>>Next Week</option>
                        <option value="month" <?php if (isset($_GET['property_availability']) && $_GET['property_availability'] == 'month')
                            echo 'selected'; ?>>Next Month</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="add_listing">Filter</button>
            </div>
        </form>
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
                echo '<button class="more-info" data-property-id="' . $row['property_id'] . '">More Info</button>';
                echo '<button class="arrow arrow-left">&lt;</button>';
                echo '<button class="arrow arrow-right">&gt;</button>';

                echo '<div class="card-content">';
                echo '<h3>' . $row['property_title'] . '</h3>';
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
            const images = imageContainer.querySelectorAll('img');
            const moreInfoButton = card.querySelector('.more-info');
            const arrowLeft = card.querySelector('.arrow-left');
            const arrowRight = card.querySelector('.arrow-right');
            const imageCount = images.length;
            let currentImage = 0;
            const imageWidth = imageContainer.offsetWidth; // Use container width for image width calculation

            moreInfoButton.addEventListener('click', () => {
            const propertyId = moreInfoButton.dataset.propertyId; // Get the property ID

            // Construct the URL for the details page
            const detailsURL = `property_details.php?id=${propertyId}`; // Adjust filename and parameter name as needed

            // Redirect to the details page
            window.location.href = detailsURL;
            });

            arrowLeft.addEventListener('click', () => {
                currentImage = (currentImage - 1 + imageCount) % imageCount;
                scrollToImage(currentImage);
            });

            arrowRight.addEventListener('click', () => {
                currentImage = (currentImage + 1) % imageCount;
                scrollToImage(currentImage);
            });

            function scrollToImage(index) {
                imageContainer.scrollTo({
                    left: index * imageWidth, // Scroll to the exact image position
                    behavior: 'smooth' // Use smooth scrolling
                });
            }

            // Optional: Center images on scroll end (improved snapScroll)
            imageContainer.addEventListener('scrollend', () => {
                snapScroll(imageContainer);
            });

            function snapScroll(element) {
                const scrollLeft = element.scrollLeft;
                const imageWidth = element.offsetWidth;
                const index = Math.round(scrollLeft / imageWidth);
                element.scrollTo({
                    left: index * imageWidth,
                    behavior: 'smooth'
                });
            }
        });
    </script>
</body>

</html>