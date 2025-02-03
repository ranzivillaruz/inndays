<?php
session_start(); // Start the session to access session variables

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
  <link rel="stylesheet" href="css/loginreg.css" />
  <link rel="icon" type="image/x-icon" href="assets/icon.png">
  <title>Login and Register-innDays</title>
</head>

<body>
  <div class="container">
    <div class="forms-container">
      <div class="signin-signup">
        
      <form action="/inndays/server/login_controller.php" method="POST" class="sign-in-form">
    <h2 class="title">Sign in</h2>
    <div class="input-field">
        <i class="fas fa-envelope"></i>
        <input type="text" name="email" placeholder="Email or Username" required />
    </div>
    <div class="input-field">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" placeholder="Password" required />
    </div>
    <input type="submit" value="Login" class="btn solid" />
</form>


          <form action="/inndays/server/register_controller.php" method="POST" class="sign-up-form">
            <h2 class="title">Sign up</h2>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text" name="name" placeholder="Name" required />
            </div>
            <div class="input-field">
  <i class="fas fa-phone"></i>
  <input type="tel" name="contact" placeholder="Contact Number" pattern="^[0-9]{11}$" 
  maxlength="11" inputmode="numeric" />
</div>


            <div class="input-field">
              <i class="fas fa-envelope"></i>
              <input type="email" name="email" placeholder="Email" required />
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" name="password" placeholder="Password" required />
            </div>
            <input type="submit" class="btn" value="Sign up" />
          </form>

      </div>
    </div>

    <div class="panels-container">
      <div class="panel left-panel">
        <div class="content">
          <h3>New here ?</h3>
          <p>
            Sign up and book with us!
          </p>
          <button class="btn transparent" id="sign-up-btn">
            Sign up
          </button>
        </div>
        <img src="assets/book.svg" class="image" alt="" />
      </div>
      <div class="panel right-panel">
        <div class="content">
          <h3>One of us ?</h3>
          <p>
            If you already have an account, just sign in. We've missed you!
          </p>
          <button class="btn transparent" id="sign-in-btn">
            Sign in
          </button>
        </div>
        <img src="assets/register.svg" class="image" alt="" />
      </div>
    </div>
  </div>
  <?php if (isset($_SESSION['popupMessage'])): ?>
    <div class="popup <?php echo $_SESSION['popupType']; ?>" id="popupMessage">
        <span><?php echo $_SESSION['popupMessage']; ?></span>
    </div>
    <?php
    unset($_SESSION['popupMessage']);
    unset($_SESSION['popupType']);
    ?>
<?php endif; ?>


  <script src="js/script.js"></script>
</body>

</html>