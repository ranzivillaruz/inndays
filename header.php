<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Default Title'; ?></title>
    <link rel="stylesheet" href="css/header.css">
    <link rel="icon" type="image/x-icon" href="assets/icon.png">
</head>

<body>
    <nav>
        <ul class="sidebar">
            <div class="sidebarFirstLine">
                <li class="user-profile" onclick="toggleUserProfileOptions()">
                    <img src="assets/placeholderProfile.png" alt="User Profile" height="40px" width="40px">
                </li>
                <li onclick="hideHeaderSidebar()" class="closeSidebar">
                    <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px">
                        <path
                            d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z" />
                    </svg>
                </li>
            </div>

            <div id="userProfileOptions" class="user-profile-options">
                <li><a href="profile.php">&nbsp;&nbsp;&nbsp;Profile</a></li>
                <li><a href="my-listings.php">&nbsp;&nbsp;&nbsp;My Listings</a></li>
                <li><a href="logout.php">&nbsp;&nbsp;&nbsp;Logout</a></li>
            </div>

            <li><a href="index.php" class="<?php echo ($currentPage === 'home') ? 'active' : ''; ?>">Home</a></li>
            <li><a href="listings.php" class="<?php echo ($currentPage === 'listings') ? 'active' : ''; ?>">Listings</a>
            </li>
            <li><a href="faq.php" class="<?php echo ($currentPage === 'faq') ? 'active' : ''; ?>">FAQ</a></li>
        </ul>

        <ul>
            <li><img src="assets/inndays w text.png" class="innDays-logo" alt="innDays Logo"></li>
            <li><a href="index.php"
                    class="hideOnSmallScreens <?php echo ($currentPage === 'home') ? 'active' : ''; ?>">Home</a></li>
            <li><a href="listings.php"
                    class="hideOnSmallScreens <?php echo ($currentPage === 'listings') ? 'active' : ''; ?>">Listings</a>
            </li>
            <li><a href="faq.php"
                    class="hideOnSmallScreens <?php echo ($currentPage === 'faq') ? 'active' : ''; ?>">FAQ</a></li>

            <!-- Updated User Profile Wrapper -->
            <div class="user-profile-wrapper">
                <li class="user-profile-show" onclick="toggleUserProfileOptionsBigScreen()">
                    <img src="assets/placeholderProfile.png" alt="User Profile" height="40px" width="40px"
                        class="hideOnSmallScreens">
                </li>
                <div id="userProfileOptionsBigScreen" class="user-profile-options-big-screen">
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="my-listings.php">My Listings</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </div>
            </div>

            <li class="headerMenu" onclick="showHeaderSidebar()">
                <a href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" height="26px" viewBox="0 -960 960 960" width="26px">
                        <path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z" />
                    </svg>
                </a>
            </li>
        </ul>
    </nav>

    <!-- JavaScript -->
    <script>
        function showHeaderSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.add('active');
            sidebar.style.right = '0'; // Slide in the sidebar
        }

        function hideHeaderSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.remove('active');
            sidebar.style.right = '-100%'; // Slide out the sidebar
        }

        function toggleUserProfileOptions() {
            const userProfileOptions = document.getElementById('userProfileOptions');
            userProfileOptions.classList.toggle('active');
        }

        function toggleUserProfileOptionsBigScreen() {
            const userProfileOptionsBigScreen = document.getElementById('userProfileOptionsBigScreen');
            userProfileOptionsBigScreen.classList.toggle('visible');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.querySelector('.sidebar');
            const toggleButton = document.querySelector('.sidebar-toggle'); // Replace with actual toggle button selector

            // Ensure the sidebar is hidden on load for the specified width range
            if (window.innerWidth >= 251 && window.innerWidth <= 404) {
                sidebar.style.right = '-100%';
            }

            // Handle toggle button click to open/close sidebar
            if (toggleButton) {
                toggleButton.addEventListener('click', function () {
                    if (sidebar.classList.contains('active')) {
                        sidebar.classList.remove('active');
                        sidebar.style.right = '-100%'; // Slide sidebar out
                    } else {
                        sidebar.classList.add('active');
                        sidebar.style.right = '0'; // Slide sidebar in
                    }
                });
            }

            // Close dropdowns and adjust layout on screen resize
            window.addEventListener('resize', function () {
                const userProfileOptionsBigScreen = document.getElementById('userProfileOptionsBigScreen');

                // Ensure the sidebar is hidden for smaller screens between 251px and 404px
                if (window.innerWidth >= 251 && window.innerWidth <= 404) {
                    if (!sidebar.classList.contains('active')) {
                        sidebar.style.right = '-100%'; // Keep it hidden
                    }
                }

                // Remove "active" class from sidebar for screens <= 768px
                if (window.innerWidth <= 768 && sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                    sidebar.style.right = '-100%'; // Ensure it is hidden
                }

                // Close user profile options on big screens when resized below 768px
                if (window.innerWidth < 768 && userProfileOptionsBigScreen && userProfileOptionsBigScreen.classList.contains('visible')) {
                    userProfileOptionsBigScreen.classList.remove('visible');
                }
            });
        });
    </script>


</body>

</html>