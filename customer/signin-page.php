<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in - Shoplex</title>

    <link rel="icon" type="image/png" href="images/favicon/favicon-48x48.png" sizes="48x48" />
    <link rel="icon" type="image/svg+xml" href="images/favicon/favicon.svg" />
    <link rel="shortcut icon" href="images/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png" />
    <link rel="manifest" href="images/favicon/site.webmanifest" />
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="css/signin-register.css">
    <link rel="stylesheet" href="css/spinner.css">

</head>
<body>   
    
        <div class="main-box">
            <div class="logo-container">
                <div class="logo-box">
                    <a href="index.php"><img class="logo" src="images/logo/green-logo.png"></a>
                </div>
            </div>
            <div class="signin-container">
                <div class="signin-box">
                    <label for="signin-form">Sign in</label>
                    <form id="signin-form" class="signin-form" action="signin-process.php" method="POST" autocomplete="off">
                        <div class="input-box">
                            <input id="email" type="text" name="email" placeholder="Enter email">
                            <div id="email-msg" class="message"></div>
                        </div>
                        <div class="password-box">
                            <div class="input-box">
                                <input id="password" type="password" name="password" placeholder="Enter password" />
                                <span class="eye" id="toggle-password"><i id="see" class="far fa-eye"></i></span>

                                <div id="psw-msg" class="message"></div>
                            </div>
                        </div>



                        <!-- <div class="remember-box">
                            <input type="checkbox" name="remember_me" id="remember_me">
                            <p class="remember" for="remember_me">Remember Me</p>
                        </div> -->
                        <button id="signin-btn" class="signin-btn" type="submit">Sign in</button>
                    </form>
                    <div class="signin-text">New to Shoplex?<a href="register-page.html">Register</a></div>
                </div>
            </div>
            <div class="rights">2024 All Rights Reserved</div>

            <div id="loading-spinner" class="dot-spinner" style="display: none;">
                <div class="dot-spinner__dot"></div>
                <div class="dot-spinner__dot"></div>
                <div class="dot-spinner__dot"></div>
                <div class="dot-spinner__dot"></div>
                <div class="dot-spinner__dot"></div>
                <div class="dot-spinner__dot"></div>
                <div class="dot-spinner__dot"></div>
                <div class="dot-spinner__dot"></div>
            </div>
            
        </div>
        

        <!-- <div id="popup" class="popup">Successfully Signed in!</div> -->

        <!-- <div id="user-info">
            <span id="user-name-display"></span>
            <button id="logout-btn" style="display: none;">Logout</button>
        </div> -->
    

    <script type="module" src="javascript/signin-validation.js"></script>
    

    
</body>
</html>
