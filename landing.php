<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>innDays</title>
    <link rel="stylesheet" href="css/landing.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="assets/innDays icon.png">
</head>

<body>
    <header>
        <a href="landing.php"><img src="assets/innDays w text no bg.png" alt="innDay's Logo" class="innDays-logo" ></a>

        <div class="navigation">
            <div class="navigation-items">
                <a href="loginreg.php">SIGN IN/ SIGN UP</a>
            </div>
        </div>


    </header>

    <section class="home">
        
        <video class="video-slide active" src="assets/mountain.mp4" autoplay muted loop></video>
        <video class="video-slide" src="assets/river.mp4" autoplay muted loop></video>
        <video class="video-slide" src="assets/lake.mp4" autoplay muted loop></video>
        <video class="video-slide" src="assets/beach.mp4" autoplay muted loop></video>

        <div class="content active">
            <h1>MOUNTAIN <br> <span>Side</span> </h1>
            <p>Experience serene mountain living with breathtaking views, fresh air, and endless outdoor adventures just steps from your door. Escape the hustle and embrace tranquility in a home nestled in nature’s embrace..</p>
            <a href="loginreg.php">Read More</a>
        </div>

        <div class="content">
            <h1>RIVER <br> <span>Side</span> </h1>
            <p>Live by the gentle flow of the river, where nature’s beauty meets peaceful waterfront living. Enjoy fishing, kayaking, and stunning sunsets from your own private retreat.</p>
            <a href="loginreg.php">Read More</a>
        </div>

        <div class="content">
            <h1>LAKE <br> <span>Side</span> </h1>
            <p>Discover the perfect lakeside escape with panoramic water views and endless recreational activities. Whether for year-round living or a weekend getaway, these homes offer a peaceful retreat by the water.</p>
            <a href="loginreg.php">Read More</a>
        </div>

        <div class="content">
            <h1>BEACH<br> <span>Side</span> </h1>
            <p>Wake up to the soothing sound of waves and enjoy sun-kissed shores right outside your doorstep. These stunning beachfront homes offer the perfect blend of luxury, relaxation, and coastal charm.</p>
            <a href="loginreg.php">Read More</a>
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