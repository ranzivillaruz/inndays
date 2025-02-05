<?php
// Define the page title and current page for the header
$pageTitle = "FAQ - innDays";
$currentPage = "home";

// Check if the user is logged in; if not, redirect to landing.php
if (!isset($_SESSION['user_name'])) {
    header("Location: landing.php");
    exit(); // Stop further script execution
}

// Include the reusable header
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/innDays icon.png">
    <title></title>
    <link rel="stylesheet" href="css/faq.css">
</head>

<body>

    <div class="accordion-container">

        <h1>Frequently Asked Questions (FAQ)</h1>

        <ul class="accordion">
            <li>
                <input type="radio" name="accordion" id="first">
                <label for="first">What is innDays?</label>
                <div class="content">
                    <p>
                        Inndays is a platform that connects travelers with unique and comfortable short-term rental
                        accommodations. Whether you're looking for a cozy apartment, a luxurious villa, or a
                        budget-friendly stay, Inndays helps you find the perfect place for your trip.
                    </p>
                </div>
            </li>

            <li>
                <input type="radio" name="accordion" id="second">
                <label for="second">How do I book a stay on Inndays?</label>
                <div class="content">
                    <p>
                        Booking a stay is simple! Browse through our listings, select your preferred accommodation,
                        check availability, and complete the reservation process by following the payment and
                        confirmation steps. You'll receive a confirmation email with all the details of your stay.
                    </p>
                </div>
            </li>

            <li>
                <input type="radio" name="accordion" id="third">
                <label for="third">Is it safe to book through Inndays?</label>
                <div class="content">
                    <p>
                        Yes! We take security seriously. Our platform verifies hosts, ensures secure payment processing,
                        and provides reviews from past guests to help you make informed decisions. If you ever encounter
                        an issue, our customer support team is ready to assist.
                    </p>
                </div>
            </li>

            <li>
                <input type="radio" name="accordion" id="fourth">
                <label for="fourth">What is the cancellation policy?</label>
                <div class="content">
                    <p>
                        Cancellation policies vary depending on the host and property. When booking, you’ll see the
                        specific cancellation terms for each listing. Some may offer free cancellations within a certain
                        timeframe, while others may have stricter policies. Be sure to review the details before
                        confirming your stay.
                    </p>
                </div>
            </li>
        </ul>
    </div>



    <script>
        document.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('click', function () {
                if (this.dataset.checked === "true") {
                    this.checked = false;
                    this.dataset.checked = "false";
                } else {
                    this.dataset.checked = "true";
                }
            });
        });
    </script>
</body>

</html>

<?php include 'footer.php'; ?>