<?php
session_start(); // Start the session to access session variables
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="cont">
        <div class="form sign-in">
            <h2>Welcome back!</h2>
            <form action="login.php" method="POST">
                <label>
                    <span>Email</span>
                    <input type="email" name="email" required />
                </label>
                <label>
                    <span>Password</span>
                    <input type="password" name="password" required />
                </label>
                <p class="forgot-pass">Forgot password?</p>
                <button type="submit" class="submit">Sign In</button>
            </form>
        </div>

        <div class="sub-cont">
            <div class="img">
                <div class="img__text m--up">
                    <h2>New here?</h2>
                    <p>Sign up and book with us!</p>
                </div>
                <div class="img__text m--in">
                    <h2>One of us?</h2>
                    <p>If you already have an account, just sign in. We've missed you!</p>
                </div>
                <div class="img__btn">
                    <span class="m--up">Sign Up</span>
                    <span class="m--in">Sign In</span>
                </div>
            </div>
            
            <form action="submit.php" method="POST" class="form sign-up">
                <h2>Time to feel like home</h2>
                <label>
                    <span>Name</span>
                    <input type="text" name="name" required />
                </label>
                <label>
                    <span>Email</span>
                    <input type="email" name="email" required />
                </label>
                <label>
                    <span>Password</span>
                    <input type="password" name="password" required />
                </label>
                <button type="submit" class="submit">Sign Up</button>
            </form>
        </div>
    </div>

    <?php if (isset($_SESSION['popupMessage'])): ?>
        <div class="popup <?php echo $_SESSION['popupType']; ?>">
            <?php echo $_SESSION['popupMessage']; ?>
        </div>
        <?php
        // Unset session messages after showing them
        unset($_SESSION['popupMessage']);
        unset($_SESSION['popupType']);
        ?>
    <?php endif; ?>

    <script>
        document.querySelector('.img__btn').addEventListener('click', function() {
            document.querySelector('.cont').classList.toggle('s--signup');
        });
    </script>
</body>
</html>
