<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>innDays</title>
    <link rel="stylesheet" href="css/landing.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <header>
        <a href="landing.php"><img src="assets/innDays w text.png" alt="innDay's Logo" class="innDays-logo" height="20px" width="40px"></a>

        <div class="navigation">
            <div class="navigation-items">
                <a href="login.php">Login</a>
                <a href="signup.php">Sign Up</a>
            </div>
        </div>


    </header>

    <section class="home">
        
        <video class="video-slide active" src="assets/1.mp4" autoplay muted loop></video>
        <video class="video-slide" src="assets/2.mp4" autoplay muted loop></video>
        <video class="video-slide" src="assets/3.mp4" autoplay muted loop></video>
        <video class="video-slide" src="assets/4.mp4" autoplay muted loop></video>

        <div class="content active">
            <h1>Wow <br> <span>Galing</span> </h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                ex ea commodo consequat.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor
                incididunt ut labore et dolore magna aliqua.</p>
            <a href="#">Read More</a>
        </div>

        <div class="content">
            <h1>Galing <br> <span>Tangina</span> </h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                ex ea commodo consequat.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor
                incididunt ut labore et dolore magna aliqua.</p>
            <a href="#">Read More</a>
        </div>

        <div class="content">
            <h1>Bobo <br> <span>ka ba?</span> </h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                ex ea commodo consequat.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor
                incididunt ut labore et dolore magna aliqua.</p>
            <a href="#">Read More</a>
        </div>

        <div class="content">
            <h1>Pakyu <br> <span>Erika</span> </h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                ex ea commodo consequat.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor
                incididunt ut labore et dolore magna aliqua.</p>
            <a href="#">Read More</a>
        </div>

        <div class="media-icons">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>

        <div class="slider-navigation">
            <div class="nav-btn active"></div>
            <div class="nav-btn"></div>
            <div class="nav-btn"></div>
            <div class="nav-btn"></div>
        </div>

    </section>

    <script>
        const btns = document.querySelectorAll('.nav-btn');
        const slides = document.querySelectorAll('.video-slide');
        const contents = document.querySelectorAll('.content');

        var sliderNav = function(Manual){
            btns.forEach((btn) => {
                btn.classList.remove('active');
            });

            slides.forEach((slide) => {
                slide.classList.remove('active');
            });

            contents.forEach((content) => {
                content.classList.remove('active');
            });

            btns[Manual].classList.add('active');
            slides[Manual].classList.add('active');
            contents[Manual].classList.add('active');
        }

        btns.forEach((btn, i) => {
            btn.addEventListener('click', () => {
                sliderNav(i);
            });
        });
    </script>
</body>

</html>