<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoplex - All Your Favourites in One Place</title>

    <link rel="icon" type="image/png" href="images/favicon/favicon-48x48.png" sizes="48x48" />
    <link rel="icon" type="image/svg+xml" href="images/favicon/favicon.svg" />
    <link rel="shortcut icon" href="images/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png" />
    <link rel="manifest" href="images/favicon/site.webmanifest" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="css/styles.css">

</head>

<body>
    <nav>
        <div class="side-navbar" id="sideNavBar">
            <!-- <span class="close-btn" id="closeBtn">&times;</span> -->
            <img src="images/icons/close.svg" class="close-btn" id="closeBtn">
            <a href="home-page.php">
                <img class="sidebar-logo" src="images/logo/white-logo.png">
            </a>
            <div class="sidenav-category-section">
                <label for="categories" class="dropdown-label">Categories</label>
                <div class="dropdown-content">
                    <a href="#" class="subject">
                        <div>All</div>
                    </a>
                    <a href="#" class="subject">
                        <div>Electrical</div>
                    </a>
                    <a href="#" class="subject">
                        <div>Home ware</div>
                    </a>
                    <a href="#" class="subject">
                        <div>Fashion</div>
                    </a>
                </div>
            </div>
            
            <div class="sidenav-program-section">
                <label for="Programs & Events">Programs & Events</label>
                <a href="#" class="subject">
                    <div>All</div>
                </a>
                <a href="#" class="subject">
                    <div>Electrical</div>
                </a>
                <a href="#" class="subject">
                    <div>Home ware</div>
                </a>
                <a href="#" class="subject">
                    <div>Fashion</div>
                </a>
            </div>
            
            <div class="sidenav-setting-section">
                <label for="categories">Settings & Helps</label>
                <a href="#" class="subject" id="greeting">
                    <p class="hi-txt">Hi,&#xA0;</p>
                    <p class="user-name"> Abdul Azeez</p>
                </a>
                <a href="#" class="subject">
                    <div>Profile</div>
                </a>
                <a href="#" class="subject">
                    <div>Cart</div>
                </a>
                <a href="#" class="subject">
                    <div>Orders</div>
                </a>
                <a href="#" class="subject">
                    <div>Log out</div>
                </a>
            </div>
        </div>

        <div id="overlay" class="overlay"></div>


        <div class="nav-bar">
            <div class="top-bar">
                <div class="left-section">
                    <button class="hamburger-button">
                        <img class="hamburger-menu" src="images/icons/hamburger-menu.png">
                    </button>

                    <a href="home-page.php">
                        <img class="comp-logo" src="images/logo/green-logo.png">
                    </a>
                </div>

                <div class="mid-section">
                    <input class="search-bar" type="text" placeholder="Search" />
                    <button class="search-button">
                        <img src="images/icons/search.png">
                        <div class="tooltip">Search</div>
                    </button>

                    <div class="image-search-button" onclick="toggleUploadBox()">
                        <img src="images/icons/image-search.png" alt="Upload Icon">
                        <div class="image-upload-box" id="uploadBox">
                            <p>Search by image</p>
                            <input type="file" accept="image/*">
                        </div>
                        <!-- <div class="tooltip">Search by image</div> -->
                    </div>
                </div>

                <div class="right-section">
                    <div class="right-button" id="cart-button">
                        <img class="cart-icon" src="images/icons/cart.png">
                        <div class="tooltip">Cart</div>
                    </div>
                    <div class="right-button" id="order-button">
                        <img class="order-icon" src="images/icons/order.png">
                        <div class="tooltip">Order</div>
                    </div>
                    <div class="right-button" id="notfication-button">
                        <img class="notfications-icon" src="images/icons/notification.png">
                        <div class="notfication-count">5</div>
                        <div class="tooltip">Notification</div>
                    </div>
                    <div class="right-button" id="profileButton">

                        <img class="current-user-picture" src="images/icons/profile.png"
                            onclick="toggleProfilePopupBox()">

                        <div class="profile-popup" id="profilePopup">
                            <!-- <div class="top-section">
                                <a href="signin-page.php">
                                    <button class="sigin-btn">Sign in</button>
                                </a>
                                <a class="register" href="register-page.html">
                                    Register
                                </a>
                            </div> -->

                            <div class="top-section">
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <span class="username"><?php echo $_SESSION['name']; ?></span>
                                    <a href="php-config/logout.php">
                                        <button class="sigin-btn">Logout</button>
                                    </a>
                                <?php else: ?>
                                    <a href="signin-page.php">
                                        <button class="sigin-btn">Sign in</button>
                                    </a>
                                    <a class="register" href="register-page.html">Register</a>
                                <?php endif; ?>
                            </div>

                            <div class="profile-mid-section">


                                <a href="signin-page.php">
                                    <img src="images/icons/profile-p.png">
                                    <div>Profile</div>
                                </a>

                                <a href="#">
                                    <img src="images/icons/cart.png">
                                    <div>Cart</div>
                                </a>
                                <a href="#">
                                    <img src="images/icons/order.png">
                                    <div>My Orders</div>
                                </a>
                                <a href="#">
                                    <img src="images/icons/payment.png">
                                    <div>Payment</div>
                                </a>
                                <a href="#">
                                    <img src="images/icons/message-center.png">
                                    <div>Message Center</div>
                                </a>
                                <a href="#">
                                    <img src="images/icons/about-us.png">
                                    <div>About</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bottom-bar">
                <div class="dropdown-box">
                    <select name="category" id="category-dropdown">
                        <!-- <option value="" disabled selected>All Categories</option> -->
                        <option value="all-categories">All Categories</option>
                        <option value="electronics">Electronics</option>
                        <option value="home">Home</option>
                        <option value="fashion">Fashion</option>
                        <img class="dropdown-logo" src="images/icons/category.png">
                    </select>
                </div>

                <div class="shortcut-links">
                    <a href="#">
                        <div>Today's Deals</div>
                    </a>

                    <a href="#">
                        <div>Customer Service</div>
                    </a>

                    <a href="#">
                        <div>
                            Sell
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main>
        <section class="slideshow">
            <div class="arrow-back">
                <img src="images/icons/arrow-back.png" class="arrow" alt="Previous">
            </div>
            <div class="slideshow-image-box">
                <div class="slide">
                    <img src="images/slideshow-banner/1.jpg" alt="Slide 1">
                </div>
                <div class="slide">
                    <img src="images/slideshow-banner/2.jpg" alt="Slide 2">
                </div>
                <div class="slide">
                    <img src="images/slideshow-banner/3.webp" alt="Slide 3">
                </div>
            </div>
            
            <div class="arrow-forward">
                <img src="images/icons/arrow-forward.png" class="arrow" alt="Next">
            </div>
        </section>
        
        <section class="hero">
            <div class="hero-content">
                <h1>Welcome to&nbsp;<span><img src="images/logo/green-logo.png"></span></h1>
                <p>Your one-stop shop for everything!</p>
                <a href="#shop" class="cta-button">Shop Now</a>
            </div>
            <div class="service-cards">
                <div class="service-card">
                    <img src="images/services/shipping.png" alt="Free Shipping" class="service-icon">
                    <h3>Free Shipping</h3>
                    <p>Enjoy free shipping on orders over LKR.3000</p>
                    <!-- <a href="#shipping" class="service-link">Learn More</a> -->
                </div>
                <div class="service-card">
                    <img src="images/services/247.png" alt="24/7 Support" class="service-icon">
                    <h3>24/7 Support</h3>
                    <p>Our support team is available 24/7 to assist you.</p>
                    <!-- <a href="#support" class="service-link">Learn More</a> -->
                </div>
                <div class="service-card">
                    <img src="images/services/return.png" alt="Easy Returns" class="service-icon">
                    <h3>Easy Returns</h3>
                    <p>Hassle-free returns within 30 days of purchase.</p>
                    <!-- <a href="#returns" class="service-link">Learn More</a> -->
                </div>
                <div class="service-card">
                    <img src="images/services/payment.png" alt="Secure Payments" class="service-icon">
                    <h3>Secure Payments</h3>
                    <p>Your payments are safe and secure with us.</p>
                    <!-- <a href="#payments" class="service-link">Learn More</a> -->
                </div>
            </div>
        </section>

        <section class="products-grid">
            <div>
                <div>
                    <div>
                        <img src="" alt="">
                    </div>
                    <div>
                        <div>
                            <p>Wireless keyboard</p>
                        </div>
                        <div class="star-rating">
                            <span class="star filled">★</span>
                            <span class="star filled">★</span>
                            <span class="star filled">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                        </div>
                        <div class="star-rating">
                            <p>LKR.3000</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
              
    </main>
    
    
    


    <script src="javascript/header.js"></script>
    <script src="javascript/slideshow.js"></script>
</body>

</html>