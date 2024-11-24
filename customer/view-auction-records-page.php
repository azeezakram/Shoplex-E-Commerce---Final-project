<?php
include('php-config/db-conn.php');
session_start();
error_reporting(E_ALL);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Auction Histories</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/auction-history-page.css">
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




            <!-- <div class="sidenav-program-section">
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
            </div> -->

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
                    <a href="profile.php" class="subject">
                        <div>Profile</div>
                    </a>
                    <a href="view-auction-records-page.php" class="subject">
                        <div>Auction History</div>
                    </a>
                    <a href="cart-page.php" class="subject">
                        <div>Cart</div>
                    </a>
                    <a href="order-page.php" class="subject">
                        <div>Orders</div>
                    </a>
                <?php else: ?>
                    <a href="signin-page.php" class="subject">
                        <div>Profile</div>
                    </a>
                    <a href="signin-page.php" class="subject">
                        <div>Auction History</div>
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

                </div>

                <div class="right-section">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="right-button" id="cart-button">
                            <a href="cart-page.php">
                                <img class="cart-icon" src="images/icons/cart.png">
                                <div class="tooltip">Cart</div>
                            </a>
                        </div>
                        <div class="right-button" id="order-button">
                            <a href="order-page.php">
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
                            onclick="toggleProfilePopupBox()">

                        <div class="profile-popup" id="profilePopup">

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
                                    <a href="profile.php">
                                        <img src="images/icons/profile-p.png">
                                        <div>Profile</div>
                                    </a>

                                    <a href="view-auction-records-page.php">
                                        <img src="images/icons/cart.png">
                                        <div>Auction History</div>
                                    </a>

                                    <a href="cart-page.php">
                                        <img src="images/icons/cart.png">
                                        <div>Cart</div>
                                    </a>
                                    <a href="order-page.php">
                                        <img src="images/icons/order.png">
                                        <div>Orders</div>
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
                                        <div>Auction History</div>
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

    <div class="container">
        <h1>Auction History</h1>

        <div class="auction-container" id="auctionContainer"></div>
    </div>

    <script src="javascript/header.js"></script>
    <script src="javascript/auction-history.js"></script>                  
</body>

</html>
