<?php
// Start the session to access logged-in user's data
session_start();

// Define the page title and current page for the header
$pageTitle = "My Listings - innDays";
$currentPage = "My Listings";

// Include the reusable header
include 'header.php';

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "inndays";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the user is logged in and fetch their name from the session
if (!isset($_SESSION['user_name'])) {
    die("You must be logged in to view this page.");
}

$user_name = $_SESSION['user_name']; // Get the logged-in user's name

// Fetch all listings based on filters and user's name
$whereClauses = ["property_owner = '$user_name'"]; // Restrict listings to the logged-in user by their name

// Apply filters for property type
if (!empty($_GET['property_type'])) {
    $property_type = trim($conn->real_escape_string($_GET['property_type']));
    // Convert both user input and database value to upper case for case-insensitive matching
    $whereClauses[] = "UPPER(property_type) = UPPER('$property_type')";
}

// Apply filters for price range
if (!empty($_GET['price_range'])) {
    switch ($_GET['price_range']) {
        case '0-5000':
            $whereClauses[] = "property_price BETWEEN 0 AND 5000";
            break;
        case '5000-10000':
            $whereClauses[] = "property_price BETWEEN 5000 AND 10000";
            break;
        case '10000-20000':
            $whereClauses[] = "property_price BETWEEN 10000 AND 20000";
            break;
        case '20000+':
            $whereClauses[] = "property_price > 20000";
            break;
    }
}

// Apply filters for availability
if (!empty($_GET['property_availability'])) {
    $property_availability = $conn->real_escape_string($_GET['property_availability']);
    $whereClauses[] = "property_availability = '$property_availability'";
}

// Build the WHERE clause
$whereSQL = count($whereClauses) > 0 ? "WHERE " . implode(" AND ", $whereClauses) : "";
$sql = "SELECT * FROM listings $whereSQL";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Listings</title>
    <link rel="stylesheet" href="css/my-listings.css">
</head>

<body>
    <h2>My Listings</h2>

    <form method="GET">
        <label for="property_type">Offer Type:</label>
        <select name="property_type" id="property_type">
            <option value="">All</option>
            <option value="MOUNTAIN SIDE">Mountain Side</option>
            <option value="BEACH FRONT">Beach Side</option>
            <option value="LAKE VIEW">Lake Side</option>
            <option value="RIVER SIDE">River Side</option>
        </select>

        <label for="price_range">Price Range:</label>
        <select name="price_range" id="price_range">
            <option value="">All</option>
            <option value="0-5000">₱0 - ₱5,000</option>
            <option value="5000-10000">₱5,000 - ₱10,000</option>
            <option value="10000-20000">₱10,000 - ₱20,000</option>
            <option value="20000+">₱20,000 and above</option>
        </select>

        <label for="property_availability">Availability:</label>
        <select name="property_availability" id="property_availability">
            <option value="">All</option>
            <option value="Today">Today</option>
            <option value="Next Week">Next Week</option>
            <option value="Next Month">Next Month</option>
        </select>

        <button type="submit">Filter</button>
    </form>

    <table border="1">
        <thead>
            <tr>
                <th>Thumbnail</th>
                <th>Title</th>
                <th>Price</th>
                <th>More Info</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $images = [];
                    for ($i = 1; $i <= 5; $i++) {
                        if (!empty($row["property_pic$i"])) {
                            $images[] = "data:image/jpeg;base64," . base64_encode($row["property_pic$i"]);
                        }
                    }
                    $thumbnail = !empty($images) ? $images[0] : "assets/no-image.png";
                    echo "<tr>";
                    echo "<td><img src='$thumbnail' width='100'></td>";
                    echo "<td>" . htmlspecialchars($row['property_title']) . "</td>";
                    echo "<td>₱" . number_format($row['property_price'], 2) . "</td>";
                    echo "<td><button class='infoButton' 
                        data-title='" . htmlspecialchars($row['property_title']) . "' 
                        data-desc='" . htmlspecialchars($row['property_desc']) . "' 
                        data-price='" . $row['property_price'] . "' 
                        data-type='" . htmlspecialchars($row['property_type']) . "' 
                        data-images='" . json_encode($images) . "' 
                        data-location='" . htmlspecialchars($row['property_barangay'] . ", " . $row['property_city'] . ", " . $row['property_province'] . ", " . $row['property_address']) . "'>More Info</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No listings added</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <div id="detailsDiv" style="display: none;">
        <h3 id="detailsTitle"></h3>
        <p id="detailsType"></p>
        <p id="detailsPrice"></p>
        <p id="detailsDesc"></p>
        <p id="detailsLocation"></p>
        <div id="detailsImages"></div>
        <button id="closeButton">Close</button>
    </div>

    <script>
        document.querySelectorAll(".infoButton").forEach(button => {
            button.addEventListener("click", function () {
                document.getElementById('detailsDiv').style.display = 'block';
                document.getElementById('detailsTitle').innerText = this.dataset.title;
                document.getElementById('detailsDesc').innerText = this.dataset.desc;
                document.getElementById('detailsPrice').innerText = "Price: ₱" + Number(this.dataset.price).toLocaleString();
                document.getElementById('detailsType').innerText = "Type: " + this.dataset.type;
                document.getElementById('detailsLocation').innerText = "Location: " + this.dataset.location;

                let imagesContainer = document.getElementById('detailsImages');
                imagesContainer.innerHTML = '';
                JSON.parse(this.dataset.images).forEach(imgSrc => {
                    let imgElement = document.createElement('img');
                    imgElement.src = imgSrc;
                    imgElement.width = 100;
                    imagesContainer.appendChild(imgElement);
                });
            });
        });

        document.getElementById("closeButton").addEventListener("click", function () {
            document.getElementById("detailsDiv").style.display = "none";
        });
    </script>
</body>

</html>

<?php
// Include the footer
include 'footer.php';

// Close the database connection
$conn->close();
?>
