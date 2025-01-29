<?php
// Define the page title and current page for the header
$pageTitle = "Listings- innDays";
$currentPage = "home";

// Include the reusable header
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/listing.css">
</head>

<body>
    <div class="card-container">
        <div class="card">
            <img src="assets/nature.jpg">
            <div class="card-content">
                <h3>Card 1</h3>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Nullam nec purus feugiat, molestie ipsum
                </p>
            </div>
        </div>
        <div class="card">
            <img src="assets/nature.jpg">
            <div class="card-content">
                <h3>Card 2</h3>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Nullam nec purus feugiat, molestie ipsum
                </p>
            </div>
        </div>
        <div class="card">
            <img src="assets/nature.jpg">
            <div class="card-content">
                <h3>Card 3</h3>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Nullam nec purus feugiat, molestie ipsum
                </p>
            </div>
        </div>
    </div>
</body>

</html>