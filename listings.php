<?php
// Define the page title and current page for the header
$pageTitle = "Listings - innDays";
$currentPage = "home";

// Include the reusable header
include 'header.php';
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
    <!-- Listing Header -->
    <div class="listing_header">
        <div class="header_items">
            <div class="header_item">
                <h3>OFFER TYPE</h3>
                <select name="offer-type" id="offer-type">
                    <option value="all">All</option>
                    <option value="room">Beach Front</option>
                    <option value="house">Lake View</option>
                    <option value="apartment">Mountain Side</option>
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
        <button class="add_listing" onclick="window.location.href='addproperty.php';">List your Property</button>
    </div>

    <!-- Card Container -->
    <div class="card-container">
        <div class="card">
            <img src="assets/mainbg.jpg" alt="Room 1">
            <div class="card-content">
                <h4>Room 1</h4>
                <p>Lorem ipsum dolor sit amet.</p>
            </div>
        </div>
        <div class="card">
            <img src="assets/footerbg.jpg" alt="Room 2">
            <div class="card-content">
                <h4>Room 2</h4>
                <p>Lorem ipsum dolor sit amet.</p>
            </div>
        </div>
        <div class="card">
            <img src="assets/gif-1.gif" alt="Room 3">
            <div class="card-content">
                <h4>Room 3</h4>
                <p>Lorem ipsum dolor sit amet.</p>
            </div>
        </div>
        <div class="card">
            <img src="assets/gif-2.gif" alt="Room 4">
            <div class="card-content">
                <h4>Room 4</h4>
                <p>Lorem ipsum dolor sit amet.</p>
            </div>
        </div>
        <div class="card">
            <img src="assets/gif-3.gif" alt="Room 5">
            <div class="card-content">
                <h4>Room 5</h4>
                <p>Lorem ipsum dolor sit amet.</p>
            </div>
        </div>
        <div class="card">
            <img src="assets/carousel1.jpg" alt="Room 6">
            <div class="card-content">
                <h4>Room 6</h4>
                <p>Lorem ipsum dolor sit amet.</p>
            </div>
        </div>
    </div>
</body>

</html>