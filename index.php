<?php 
// Ensure session is started only once
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in; if not, redirect to landing.php
if (!isset($_SESSION['user_name'])) {
    header("Location: landing.php");
    exit(); // Stop further script execution
}

// Define the page title and current page for the header
$pageTitle = "Home - innDays";
$currentPage = "home";

// Sanitize username (security best practice)
$userName = htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8');

// Include the reusable header
include_once 'header.php'; 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="css/parallax.css">
    <link rel="stylesheet" href="css/carousel.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    
</head>
<body>

   

<section class="parallax-section" style="background-image: url('assets/freepik__expand__85678.jpeg');">
    <div class="overlay1">
        <header>
            <h2>Welcome to innDays , <?php echo htmlspecialchars($userName); ?>!</h2>
            <p>Your ultimate destination for listings by the beach, mountains, rivers, and lakes.</p>
        </header>
    </div>
</section>


    <section class="parallax-section">
        <div class="overlay">
            <div class="carousel-container">
                <div class="carousel">
                    <div><img src="assets/carousel1.jpg" alt="Carousel Image 1"></div>
                    <div><img src="assets/carousel2.jpg" alt="Carousel Image 2"></div>
                    <div><img src="assets/carousel3.jpg" alt="Carousel Image 3"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="parallax-section" style="background-image: url('assets/freepik__upload__30746.png');">
        <div class="overlay">
            <h3>Discover Our Features</h3>
            <ul>
                <li>Register - Erika</li>
                <li>Login - Erika</li>
                <li>Footer (Contact Us)</li>
            </ul>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script>
        document.addEventListener("scroll", function() {
            let scrollPosition = window.scrollY;
            document.querySelectorAll(".parallax-section").forEach((section, index) => {
                let speed = (index + 1) * 0.5;
                section.style.backgroundPositionY = `${scrollPosition * speed}px`;
            });
        });

        $(document).ready(function(){
            $('.carousel').slick({
                infinite: true,
                speed: 800,
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 3000,
            });
        });

        /* ✅ JavaScript Auto-Resize Fix (Ensures True Dynamic Sizing) */

        document.addEventListener("DOMContentLoaded", function() {
            function adjustParallaxHeight() {
            let screenHeight = window.innerHeight;
            document.querySelectorAll(".parallax-section").forEach(section => {
            section.style.height = screenHeight + "px";
        });
    }

    // Call function on load & resize
    adjustParallaxHeight();
    window.addEventListener("resize", adjustParallaxHeight);
});
</script>

    </script>

</body>
</html>

<!-- Include footer -->
<?php include 'footer.php'; ?>
