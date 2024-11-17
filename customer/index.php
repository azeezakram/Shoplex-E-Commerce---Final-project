<?php
include('php-config/db-conn.php');
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 365);
ini_set('session.gc-maxlifetime', 60 * 60 * 24 * 365);
session_start();
error_reporting(E_ALL);
?>


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
            <a href="index.php">
                <img class="sidebar-logo" src="images/logo/white-logo.png">
            </a>

            <div class="sidenav-category-section">
                <label for="categories" class="dropdown-label">Categories</label>
                <div class="dropdown-content">
                    <?php
                    // Initialize an array to store categories
                    $categories = [];

                    // Fetch all categories with their parent relationships
                    $result = $conn->query("SELECT category_id, category_name, parent_category_id FROM category ORDER BY parent_category_id, category_name");

                    // Organize categories into parent-child structure
                    while ($row = $result->fetch_assoc()) {
                        if ($row['parent_category_id'] === null) {
                            // Add parent category
                            $categories[$row['category_id']] = [
                                'name' => $row['category_name'],
                                'children' => []
                            ];
                        } else {
                            // Add child category under the respective parent
                            $categories[$row['parent_category_id']]['children'][] = [
                                'id' => $row['category_id'],
                                'name' => $row['category_name']
                            ];
                        }
                    }

                    // Display categories
                    foreach ($categories as $parent_id => $category): ?>
                        <a href="#" class="subject parent-category" data-id="<?php echo $parent_id; ?>">
                            <div><?php echo htmlspecialchars($category['name']); ?></div>
                        </a>
                        <?php if (!empty($category['children'])): ?>
                            <div class="subcategory-content">
                                <?php foreach ($category['children'] as $child): ?>
                                    <a href="#" class="subject child-category" data-id="<?php echo $child['id']; ?>" style="padding-left: 25px;">
                                        <div><?php echo htmlspecialchars($child['name']); ?></div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
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


                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="subject" id="greeting">
                        <span class="username">Hi,&#xA0;<?php echo $_SESSION['name']; ?></span>
                    </div>
                <?php else: ?>
                    <a href="signin-page.php" class="subject">
                        <div>Sign in</div>
                    </a>
                    <a href="register-page.html" class="subject">
                        <div>Register</div>
                    </a>
                <?php endif; ?>


                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="#" class="subject">
                        <div>Profile</div>
                    </a>
                    <a href="#" class="subject">
                        <div>Cart</div>
                    </a>
                    <a href="#" class="subject">
                        <div>Orders</div>
                    </a>
                <?php else: ?>
                    <a href="signin-page.php" class="subject">
                        <div>Profile</div>
                    </a>
                    <a href="signin-page.php" class="subject">
                        <div>Cart</div>
                    </a>
                    <a href="signin-page.php" class="subject">
                        <div>Orders</div>
                    </a>
                <?php endif; ?>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="php-config/logout.php" class="subject">
                        <div>Log out</div>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div id="overlay" class="overlay"></div>


        <div class="nav-bar">
            <div class="top-bar">
                <div class="left-section">
                    <button class="hamburger-button">
                        <img class="hamburger-menu" src="images/icons/hamburger-menu.png">
                    </button>

                    <a href="index.php">
                        <img class="comp-logo" src="images/logo/green-logo.png">
                    </a>
                </div>

                <div class="mid-section">
                    <input class="search-bar" type="text" placeholder="Search" />
                    <button class="search-button">
                        <img src="images/icons/search.png">
                        <div class="tooltip">Search</div>
                    </button>

                    <!-- <div class="image-search-button" onclick="toggleUploadBox()">
                        <img src="images/icons/image-search.png" alt="Upload Icon">
                        <div class="image-upload-box" id="uploadBox">
                            <p>Search by image</p>
                            <input type="file" accept="image/*">
                        </div> -->
                    <!-- <div class="tooltip">Search by image</div> -->
                    <!-- </div> -->

                </div>

                <div class="right-section">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="right-button" id="cart-button">
                            <a href="#">
                                <img class="cart-icon" src="images/icons/cart.png">
                                <div class="tooltip">Cart</div>
                            </a>
                        </div>
                        <div class="right-button" id="order-button">
                            <a href="#">
                                <img class="order-icon" src="images/icons/order.png">
                                <div class="tooltip">Order</div>
                            </a>
                        </div>
                        <div class="right-button" id="notfication-button">
                            <a href="#">
                                <img class="notfications-icon" src="images/icons/notification.png">
                                <div class="notfication-count">5</div>
                                <div class="tooltip">Notification</div>
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="right-button" id="cart-button">
                            <a href="signin-page.php">
                                <img class="cart-icon" src="images/icons/cart.png">
                                <div class="tooltip">Cart</div>
                            </a>
                        </div>
                        <div class="right-button" id="order-button">
                            <a href="signin-page.php">
                                <img class="order-icon" src="images/icons/order.png">
                                <div class="tooltip">Order</div>
                            </a>
                        </div>
                        <div class="right-button" id="notfication-button">
                            <a href="signin-page.php">
                                <img class="notfications-icon" src="images/icons/notification.png">
                                <div class="notfication-count">5</div>
                                <div class="tooltip">Notification</div>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="right-button" id="profileButton">

                        <img class="current-user-picture" src="images/icons/profile.png"
                            
                            onclick="window.location.href='profile.php'" 
                            onclick="toggleProfilePopupBox()"
                            >

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

                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <a href="#">
                                        <img src="images/icons/profile-p.png">
                                        <div>Profile</div>
                                    </a>

                                    <a href="#">
                                        <img src="images/icons/cart.png">
                                        <div>Cart</div>
                                    </a>
                                    <a href="#">
                                        <img src="images/icons/order.png">
                                        <div>Orders</div>
                                    </a>
                                    <a href="#">
                                        <img src="images/icons/wishlist.png">
                                        <div>Wishlist</div>
                                    </a>
                                    <a href="#">
                                        <img src="images/icons/message-center.png">
                                        <div>Message Center</div>
                                    </a>
                                    <a href="#">
                                        <img src="images/icons/about-us.png">
                                        <div>About</div>
                                    </a>
                                <?php else: ?>
                                    <a href="signin-page.php">
                                        <img src="images/icons/profile-p.png">
                                        <div>Profile</div>
                                    </a>

                                    <a href="signin-page.php">
                                        <img src="images/icons/cart.png">
                                        <div>Cart</div>
                                    </a>
                                    <a href="signin-page.php">
                                        <img src="images/icons/order.png">
                                        <div>Orders</div>
                                    </a>
                                    <a href="signin-page.php">
                                        <img src="images/icons/wishlist.png">
                                        <div>Wishlist</div>
                                    </a>
                                    <a href="signin-page.php">
                                        <img src="images/icons/message-center.png">
                                        <div>Message Center</div>
                                    </a>
                                    <a href="signin-page.php">
                                        <img src="images/icons/about-us.png">
                                        <div>About</div>
                                    </a>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bottom-bar">
                <!-- <div class="dropdown-box">
                    <select name="category" id="category-dropdown">
                       
                        <option value="all-categories">All Categories</option>
                        <option value="electronics">Electronics</option>
                        <option value="home">Home</option>
                        <option value="fashion">Fashion</option>
                        <img class="dropdown-logo" src="images/icons/category.png">
                    </select>
                </div> -->

                <!-- <div class="custom-dropdown-box">
                    <div id="custom-category-dropdown" class="custom-dropdown">
                        <div class="custom-dropdown-toggle">Select Category</div>
                        <div class="custom-dropdown-content">
                            <?php
                            include('php-config/db-conn.php');

                            // Initialize an array to store categories
                            $categories = [];

                            // Fetch all categories with their parent relationships
                            $result = $conn->query("SELECT category_id, category_name, parent_category_id FROM category ORDER BY parent_category_id, category_name");

                            // Organize categories into parent-child structure
                            while ($row = $result->fetch_assoc()) {
                                if ($row['parent_category_id'] === null) {
                                    // Add parent category
                                    $categories[$row['category_id']] = [
                                        'name' => $row['category_name'],
                                        'children' => []
                                    ];
                                } else {
                                    // Add child category under the respective parent
                                    if (isset($categories[$row['parent_category_id']])) {
                                        $categories[$row['parent_category_id']]['children'][] = [
                                            'id' => $row['category_id'],
                                            'name' => $row['category_name']
                                        ];
                                    }
                                }
                            }

                            // Display categories
                            foreach ($categories as $parent_id => $category): ?>
                                <div class="custom-parent-category" data-id="<?php echo $parent_id; ?>">
                                    <span><?php echo htmlspecialchars($category['name']); ?></span>
                                </div>
                                <?php if (!empty($category['children'])): ?>
                                    <div class="custom-subcategory-content" style="display: none;">
                                        <?php foreach ($category['children'] as $child): ?>
                                            <div class="custom-child-category" data-id="<?php echo $child['id']; ?>" style="padding-left: 15px;">
                                                <span><?php echo htmlspecialchars($child['name']); ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div> -->

                <div class="shortcut-links">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="#">
                            <div>Today's Deals</div>
                        </a>

                        <a href="#">
                            <div>Customer Service</div>
                        </a>

                    <?php else: ?>
                        <a href="signin-page.php">
                            <div>Today's Deals</div>
                        </a>

                        <a href="signin-page.php">
                            <div>Customer Service</div>
                        </a>
                    <?php endif; ?>

                    <!-- <a href="#">
                        <div>
                            Sell
                        </div>
                    </a> -->
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
                    <img src="../images/slideshow-banner/1.jpg" alt="Slide 1">
                </div>
                <div class="slide">
                    <img src="../images/slideshow-banner/1.jpg" alt="Slide 2">
                </div>
                <div class="slide">
                    <img src="../images/slideshow-banner/3.png" alt="Slide 3">
                </div>

            </div>

            <div class="arrow-forward">
                <img src="images/icons/arrow-forward.png" class="arrow" alt="Next">
            </div>
        </section>

        <!-- <section class="hero">
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
                  
                </div>
                <div class="service-card">
                    <img src="images/services/247.png" alt="24/7 Support" class="service-icon">
                    <h3>24/7 Support</h3>
                    <p>Our support team is available 24/7 to assist you.</p>
                   
                </div>
                <div class="service-card">
                    <img src="images/services/bid.png" alt="Bid" class="service-icon">
                    <h3>Place bids</h3>
                    <p>You can place bids for auction products.</p>
                   
                </div>
                <div class="service-card">
                    <img src="images/services/payment.png" alt="Secure Payments" class="service-icon">
                    <h3>Secure Payments</h3>
                    <p>Your payments are safe and secure with us.</p>
                    
                </div>
            </div>
        </section> -->

        <section class="products-grid">
            <?php
            $sql = "SELECT * FROM product";

            $productsResult = $conn->query($sql);
            ?>

            <?php
            if ($productsResult->num_rows > 0) {
                while ($row = $productsResult->fetch_assoc()) {
            ?>

                    <div class="product-card">
                        <p class="product-id" hidden><?php $row["product_id"]; ?></p>

                        <div class="product-image">
                            <?php
                            $productId = (int)$row["product_id"];
                            $sql = "SELECT picture_path FROM product_picture WHERE product_id = $productId AND default_picture = 1";

                            $productPictureResult = $conn->query($sql);


                            if ($productPictureResult && $productPictureResult->num_rows > 0) {

                                $pictureRow = $productPictureResult->fetch_assoc();
                                $picturePath = $pictureRow['picture_path'];
                            } else {

                                $picturePath = 'images\product-images\no_picture.jpg';
                            }
                            ?>
                            <a href="signin-page.php"><img src="<?php echo "../" . $picturePath; ?>" alt="Product Image" class="product-img"></a>
                        </div>
                        <div class="product-details">
                            <a href="signin-page.php">
                                <h2 class="product-name"><?php echo $row["product_name"]; ?></h2>
                                <div class="rating">
                                    <?php
                                    $productId = (int)$row["product_id"];
                                    $sql = "SELECT * FROM product_review WHERE product_id = $productId";

                                    $reviewResult = $conn->query($sql);


                                    $totalRating = 0;
                                    $count = 0;


                                    if ($reviewResult && $reviewResult->num_rows > 0) {
                                        while ($reviewRow = $reviewResult->fetch_assoc()) {
                                            $totalRating += (int)$reviewRow["rating"];
                                            $count++;
                                        }

                                        $productRating = $totalRating / $count;

                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $productRating) {
                                                echo '<span class="fa fa-star checked"></span>';
                                            } else {
                                                echo '<span class="fa fa-star"></span>';
                                            }
                                        }
                                        echo '<span class="review-count">(' . $count . ' reviews)</span>';
                                    } else {
                                        echo '
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="review-count">(0 reviews)</span>
                                        ';
                                    }
                                    ?>
                                </div>
                                <div class="price">
                                    <span class="discounted-price">
                                        <?php
                                        $discount = 0;
                                        if ((float)$row["discount"] > 0) {
                                            $discount = (float)$row["discount"];
                                        }

                                        $price = (float)$row["price"];
                                        $discountPrice = number_format($price - ($price * $discount), 2);
                                        echo "LKR. " . $discountPrice;
                                        ?>
                                    </span>

                                    <?php if ((float)$row["discount"] > 0): ?>
                                        <span class="original-price">
                                            LKR. <?php echo number_format($price, 2); ?>
                                        </span>
                                        <span class="discount-badge">
                                            <?php echo $discount * 100; ?>% off
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <div class="shipping">
                                    <span>
                                        <?php
                                        if ((float)$row["shipping_fee"] > 0) {
                                            echo "Shipping Fee: LKR." . $row["shipping_fee"];
                                        } else {
                                            echo "Free Shipping";
                                        }

                                        ?>
                                    </span>
                                </div>
                                <div class="stock-status">Availability:
                                    <span>
                                        <?php
                                        if ((int)$row["stock"] > 10) {
                                            echo "In Stock";
                                        } else {
                                            echo $row["stock"];
                                        }

                                        ?>
                                    </span>
                                </div>
                            </a>
                            <div class="buttons">

                                <?php
                                if ((int)$row["bid_activate"] == 1) {
                                    echo '<button id="placeBidBtn" class="place-bid" style="display: block;"data-product-id="' . $row['product_id'] . '">Place Bid</button>';
                                    echo '<button class="add-to-cart" style="display: none;" data-product-id="' . $row['product_id'] . '">Add to Cart</button>';
                                    echo '<button class="buy-now" style="display: none;" data-product-id="' . $row['product_id'] . '">Buy Now</button>';
                                } else {
                                    echo '<button class="add-to-cart" data-product-id="' . $row['product_id'] . '">Add to Cart</button>';
                                    echo '<button id="addToCartBtn" class="buy-now" data-product-id="' . $row['product_id'] . '">Buy Now</button>';
                                    echo '<button id="buyNowBtn" class="place-bid" style="display: none;" data-product-id="' . $row['product_id'] . '">Place Bid</button>';
                                }
                                ?>


                            </div>

                        </div>

                    </div>

            <?php
                }
            } else {
                echo "No products found.";
            }
            ?>



            <div id="product-preview-modal" class="modal">
                <div class="modal-content">
                    <!-- Close Button -->
                    <span class="close-button">&times;</span>

                    <div class="modal-body">
                        <!-- Left Section - Product Image -->
                        <div class="modal-left">
                            <div id="carousel-container">
                                <div class="carousel"></div>
                                <button id="prev-btn" class="carousel-btn">&#8249;</button>
                                <button id="next-btn" class="carousel-btn">&#8250;</button>
                            </div>
                        </div>

                        <!-- Right Section - Product Details -->
                        <div class="modal-middle">
                            <h2 id="modal-product-name">Product Name</h2>
                            <div id="modal-product-description"></div>

                            <div id="modal-product-rating"></div>
                            <!-- Add Rating Star/Icons Here -->

                            <!-- Price Section -->
                            <div class="price">
                                <span id="modal-discounted-price">LKR. 1000</span>
                                <span id="modal-original-price">LKR. 1500</span>
                                <span id="modal-discount-badge">20% off</span>
                            </div>

                            <!-- Stock and Shipping Section -->
                            <div class="stock-shipping">
                                <p class="stock-info" id="modal-stock-info">Stock: 10 available</p>
                                <p class="shipping-info" id="modal-shipping-info">Shipping Fee: LKR. 200</p>
                            </div>

                            <!-- Quantity Controller -->
                            <div class="quantity-controller">
                                <button id="decrease-quantity">-</button>
                                <input type="text" id="quantity-input" value="1" min="1" disabled />
                                <button id="increase-quantity">+</button>
                            </div>

                            <!-- Action Buttons -->
                            <div class="action-buttons">
                                <button id="custom-add-to-cart" class="custom-button">Add to Cart</button>
                                <button id="custom-buy-now" class="custom-button custom-buy-now">Buy Now</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>



        </section>

    </main>





    <script src="javascript/header.js"></script>
    <script src="javascript/signin-validation.js"></script>
    <script src="javascript/slideshow.js"></script>
    <script src="javascript/product-preview.js"></script>

</body>

</html>