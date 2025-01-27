<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Default Title'; ?></title>
    <link rel="stylesheet" href="css/header.css"> <!-- Link your stylesheet -->
    <link rel="icon" type="image/x-icon" href="assets/icon.png">
</head>

<body>
    <header class="site-header">
        <div class="logo">
            <img src="assets/innDays w text.png" alt="innDays Logo" height="75px" width="150px">
        </div>

        <div class="nav-profile">
            <nav class="navbar">
                <a href="index.php" class="<?php echo ($currentPage === 'home') ? 'active' : ''; ?>">Home</a>
                <a href="listings.php" class="<?php echo ($currentPage === 'listings') ? 'active' : ''; ?>">Listings</a>
                <a href="faq.php" class="<?php echo ($currentPage === 'faq') ? 'active' : ''; ?>">FAQ</a>
            </nav>

            <div class="profile">
                <a href="javascript:void(0)" class="profile-link">
                    <img src="assets/placeholderProfile.png" alt="Profile Picture" height="50px" width="50px">
                </a>
                <ul class="profile-dropdown-menu" id="dropdownMenu">
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="my-listings.php">My Listings</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </header>

    <!-- JavaScript to toggle dropdown visibility -->
    <script>
        // Wait for the DOM to fully load
        document.addEventListener('DOMContentLoaded', function() {
            const profileLink = document.querySelector('.profile-link');
            const dropdownMenu = document.getElementById('dropdownMenu');

            // Ensure the dropdown is hidden on page load
            dropdownMenu.style.display = 'none';

            // Function to toggle dropdown visibility
            function toggleDropdown(event) {
                event.preventDefault(); // Prevent default anchor behavior
                dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
            }

            // Add event listener to toggle dropdown on click
            profileLink.addEventListener('click', toggleDropdown);

            // Close the dropdown if clicked outside
            document.addEventListener('click', function(event) {
                if (!profileLink.contains(event.target)) {
                    dropdownMenu.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>
