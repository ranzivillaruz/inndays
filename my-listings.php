<?php
ob_start();
session_start();

$pageTitle = "My Listings - innDays";
$currentPage = "My Listings";

include 'header.php';

$host = "localhost";
$username = "root";
$password = "";
$database = "inndays";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_name'])) {
    die("You must be logged in to view this page.");
}

$user_name = $_SESSION['user_name'];

// Handle Delete Action
if (isset($_GET['delete_id'])) {
    $delete_id = $conn->real_escape_string($_GET['delete_id']);

    $check_ownership_sql = "SELECT property_id FROM listings WHERE property_id = '$delete_id' AND property_owner = '$user_name'";
    $ownership_result = $conn->query($check_ownership_sql);

    if ($ownership_result->num_rows > 0) {
        $delete_sql = "DELETE FROM listings WHERE property_id = '$delete_id'";
        if ($conn->query($delete_sql)) {
            $_SESSION['popupMessage'] = "Listing deleted successfully!";
            $_SESSION['popupType'] = "success"; // You can add "error" for errors
            header("Location: my-listings.php");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        echo "You do not have permission to delete this listing.";
    }
}


// Handle Edit Action (Save Changes)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editPropertyId'])) {
    $property_id = $conn->real_escape_string($_POST['editPropertyId']);
    $title = $conn->real_escape_string($_POST['editTitle']);
    $desc = $conn->real_escape_string($_POST['editDesc']);
    $price = $conn->real_escape_string($_POST['editPrice']);
    $type = $conn->real_escape_string($_POST['editType']);
    $availability = $conn->real_escape_string($_POST['editAvailability']);

    $check_ownership_sql = "SELECT property_id FROM listings WHERE property_id = '$property_id' AND property_owner = '$user_name'";
    $ownership_result = $conn->query($check_ownership_sql);

    if ($ownership_result->num_rows > 0) {
        $update_sql = "UPDATE listings SET 
            property_title = '$title',
            property_desc = '$desc',
            property_price = '$price',
            property_type = '$type',
            property_availability = '$availability'
            WHERE property_id = '$property_id'";

        if ($conn->query($update_sql)) {
            $_SESSION['popupMessage'] = "Listing updated successfully!";
            $_SESSION['popupType'] = "success";
            header("Location: my-listings.php");
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "You do not have permission to edit this listing.";
    }
}



// Fetch all listings 
$whereClauses = ["property_owner = '$user_name'"];

if (!empty($_GET['property_type'])) {
    $property_type = trim($conn->real_escape_string($_GET['property_type']));
    $whereClauses[] = "UPPER(property_type) = UPPER('$property_type')";
}

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

if (!empty($_GET['property_availability'])) {
    $property_availability = $conn->real_escape_string($_GET['property_availability']);
    $whereClauses[] = "property_availability = '$property_availability'";
}

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
    <div class="my-listings">
        <h2>My Listings</h2>
        <div class="filters">
            <form method="GET">
                <div class="my_listing_header">
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
                            <select name="price_range" id="price_range">
                                <option value="">All</option>
                                <option value="0-5000">₱0 - ₱5,000</option>
                                <option value="5000-10000">₱5,000 - ₱10,000</option>
                                <option value="10000-20000">₱10,000 - ₱20,000</option>
                                <option value="20000+">₱20,000 and above</option>
                            </select>
                        </div>
                        <div class="header_item">
                            <h3>AVAILABILITY</h3>
                            <select name="property_availability" id="property_availability">
                                <option value="">All</option>
                                <option value="Today">Today</option>
                                <option value="Next Week">Next Week</option>
                                <option value="Next Month">Next Month</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="filter_button">Filter</button>
                </div>


            </form>
        </div>


        <table class="listings-table" border="1">
            <thead class="listings-table-header">
                <tr>
                    <th>Preview</th>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Actions</th> <!-- Unified actions column -->
                </tr>
            </thead>
            <tbody class="listings-table-body">
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

                        // Actions Column (More Info, Edit, Delete)
                        echo "<td>
                    <button class='infoButton' 
                        data-title='" . htmlspecialchars($row['property_title']) . "' 
                        data-desc='" . htmlspecialchars($row['property_desc']) . "' 
                        data-price='" . $row['property_price'] . "' 
                        data-type='" . htmlspecialchars($row['property_type']) . "' 
                        data-images='" . json_encode($images) . "' 
                        data-location='" . htmlspecialchars($row['property_barangay'] . ", " . $row['property_city'] . ", " . $row['property_province'] . ", " . $row['property_address']) . "'>
                        More Info
                    </button>

                    <button class='edit-button' 
                        data-id='" . $row['property_id'] . "'
                        data-title='" . htmlspecialchars($row['property_title']) . "' 
                        data-desc='" . htmlspecialchars($row['property_desc']) . "' 
                        data-price='" . $row['property_price'] . "' 
                        data-type='" . htmlspecialchars($row['property_type']) . "' 
                        data-availability='" . htmlspecialchars($row['property_availability']) . "'>
                        Edit
                    </button>

                    <a href='my-listings.php?delete_id=" . $row['property_id'] . "' class='delete-button' 
                    onclick='return confirm(\"Are you sure you want to delete this listing?\");'>
                    Delete
                    </a>
                </td>";

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No listings added</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>




    <div id="detailsDiv" style="display: none;">
        <svg xmlns="http://www.w3.org/2000/svg" id="closeButton" height="24px" viewBox="0 -960 960 960" width="24px">
            <path
                d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z" />
        </svg>
        <h3 id="detailsTitle"></h3>
        <p id="detailsType"></p>
        <p id="detailsPrice"></p>
        <p id="detailsDesc"></p>
        <p id="detailsLocation"></p>
        <div id="detailsImages"></div>
    </div>

    <div id="editModal">
        <span class="close-editModal-button" onclick="closeEditModal()">&times;</span>
        <h2>Edit Listing</h2>
        <form id="editForm" method="POST" action=""> <input type="hidden" name="editPropertyId" id="editPropertyId">
            <label for="editTitle">Title:</label>
            <input type="text" name="editTitle" id="editTitle" required><br>

            <label for="editDesc">Description:</label>
            <textarea name="editDesc" id="editDesc" rows="4" required></textarea><br>

            <label for="editPrice">Price:</label>
            <input type="number" name="editPrice" id="editPrice" required><br>

            <label for="editType">Type:</label>
            <select name="editType" id="editType">
                <option value="MOUNTAIN SIDE">Mountain Side</option>
                <option value="BEACH FRONT">Beach Front</option>
                <option value="LAKE VIEW">Lake View</option>
                <option value="RIVER SIDE">River Side</option>
            </select><br>

            <label for="editAvailability">Availability:</label>
            <select name="editAvailability" id="editAvailability">
                <option value="Today">Today</option>
                <option value="Next Week">Next Week</option>
                <option value="Next Month">Next Month</option>
            </select><br><br>

            <button type="submit" class="editModal-submit-button">Save Changes</button>
        </form>
    </div>

    <script>
        // Function to disable background scrolling
        function disableScroll() {
            document.body.style.overflow = 'hidden';
            document.body.style.paddingRight = 'var(--scrollbar-width)';
        }

        // Function to enable background scrolling
        function enableScroll() {
            document.body.style.overflow = 'auto';
            document.body.style.paddingRight = 0;
        }

        // Prevent default scrolling behavior (wheel and touch)
        function preventScroll(event) {
            event.preventDefault();
        }

        // Prevent scrolling with arrow keys and spacebar
        function preventKeyScroll(event) {
            const keys = [32, 37, 38, 39, 40]; // Space, Left, Up, Right, Down
            if (keys.includes(event.keyCode)) {
                event.preventDefault();
            }
        }


        // Details Modal Scroll Handling
        const detailsDiv = document.getElementById("detailsDiv");
        detailsDiv.addEventListener('wheel', function (event) {
            event.stopPropagation();
        });
        detailsDiv.addEventListener('touchmove', function (event) {
            event.stopPropagation();
        });

        // Edit Modal Scroll Handling
        const editModal = document.getElementById("editModal");
        editModal.addEventListener('wheel', function (event) {
            event.stopPropagation();
        });
        editModal.addEventListener('touchmove', function (event) {
            event.stopPropagation();
        });


        // Open Edit Modal
        function openEditModal(property_id, title, desc, price, type, availability) {
            editModal.style.display = 'block';
            disableScroll();

            document.getElementById('editPropertyId').value = property_id;
            document.getElementById('editTitle').value = title;
            document.getElementById('editDesc').value = desc;
            document.getElementById('editPrice').value = price;
            document.getElementById('editType').value = type;
            document.getElementById('editAvailability').value = availability;
        }

        // Close Edit Modal
        function closeEditModal() {
            editModal.style.display = 'none';
            enableScroll();
        }


        // Open Details Modal
        document.querySelectorAll(".infoButton").forEach(button => {
            button.addEventListener("click", function () {
                detailsDiv.style.display = 'block';
                disableScroll();

                document.getElementById('detailsTitle').innerText = "Property Name: " + this.dataset.title;
                document.getElementById('detailsDesc').innerText = "Description: " + this.dataset.desc;
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

        // Close Details Modal
        document.getElementById("closeButton").addEventListener("click", function () {
            detailsDiv.style.display = "none";
            enableScroll();
        });


        // Event listeners for edit buttons
        document.querySelectorAll('.edit-button').forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault();

                const property_id = this.dataset.id;
                const title = this.dataset.title;
                const desc = this.dataset.desc;
                const price = this.dataset.price;
                const type = this.dataset.type;
                const availability = this.dataset.availability;

                openEditModal(property_id, title, desc, price, type, availability);
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            let popup = document.getElementById("popupMessage");
            if (popup) {
                setTimeout(() => {
                    popup.style.opacity = "0";
                    setTimeout(() => {
                        popup.style.display = "none";
                    }, 500); // Adjust time for fade out
                }, 3000); // Message disappears after 3 seconds
            }
        });


    </script>
</body>

<?php if (isset($_SESSION['popupMessage'])): ?>
    <div class="popup <?php echo $_SESSION['popupType']; ?>" id="popupMessage">
        <span><?php echo $_SESSION['popupMessage']; ?></span>
    </div>
    <?php
    unset($_SESSION['popupMessage']);
    unset($_SESSION['popupType']);
?>
<?php endif; ?>


</html>

<?php
// Include the footer
include 'footer.php';

// Close the database connection
$conn->close();
?>