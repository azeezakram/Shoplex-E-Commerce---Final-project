<?php
// include('navbar.php');
include 'php-config/db-conn.php';


session_start();





?>








<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/inventory-page.css">

</head>

<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-header-content">
                <span>Admin Panel</span>
            </div>
            <div class="hamburger" onclick="toggleSidebar()">
                <div class="hamburger-line"></div>
                <div class="hamburger-line"></div>
                <div class="hamburger-line"></div>
            </div>
        </div>
        <nav class="sidebar-nav">
        <a href="dashboard-page.php"><i class="fas fa-home"></i> <span>Dashboard</span></a>
        <a href="user-page.php"><i class="fas fa-users"></i> <span>Users</span></a>
            <a href="inventory-page.php"><i class="fas fa-archive"></i> <span>Inventories</span></a>
            <a href="order-page.php"><i class="fas fa-box"></i> <span>Orders</span></a>
            <a href="bidding-record-page.php"><i class="fas fa-gavel"></i> <span>Bidding Records</span></a>
            <a href="message-page.php"><i class="fas fa-inbox"></i> <span>Messages</span></a>
            <a href="sales-analysis.php"><i class="fas fa-inbox"></i> <span>sales-analysis</span></a>
            <a href="banner-page.php"><i class="fas fa-ad"></i> <span>Banners</span></a>
            <!-- <a href="analytics.php"><i class="fas fa-chart-bar"></i> <span>Analytics</span></a> -->
            <!-- <a href="settings.php"><i class="fas fa-cog"></i> <span>Settings</span></a> -->
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
        </nav>
    </div>
    <div class="main-content">
        <div class="header">
            <h1>Inventory Management</h1>
            <div>
                <button id="addProductBtn" class="inventory-buttons">Add Product</button>
            </div>
        </div>

        <div>
            <button class="inventory-buttons">Normal Products</button>
            <button class="inventory-buttons">Bidding Products</button>
        </div>

        <!-- Table to display users -->
        <table id="normalProductsTable" class="inventory-table">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Price (Rs.)</th>
                    <th>Discount (%)</th>
                    <th>Stock</th>
                    <th>Shipping Fee (Rs.)</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Placeholder for dynamically loaded user data -->
                <tr>
                    <td>1</td>
                    <td class="description-container">
                        <div class="name">
                            Monitor
                        </div>
                    </td>
                    <td class="description-container">
                        <div class="description">
                            Monitorssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss
                        </div>
                    </td>
                    <td>Electric</td>
                    <td>15000</td>
                    <td>15</td>
                    <td>100</td>
                    <td>1250</td>
                    <td>date</td>
                    <td>date</td>
                    <td><button class="action-buttons">Edit</button> <button class="action-buttons">Delete</button></td>
                </tr>
                <!-- More users can be added here dynamically -->
            </tbody>
        </table>

        <table id="biddingProductsTable" class="inventory-table">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Bid Starting Price (Rs.)</th>
                    <th>Stock</th>
                    <th>Shipping Fee (Rs.)</th>
                    <th>Bid Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Placeholder for dynamically loaded user data -->
                <tr>
                    <td>1</td>
                    <td class="description-container">
                        <div class="name">
                            Monitor
                        </div>
                    </td>
                    <td class="description-container">
                        <div class="description">
                            Monitorssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss
                        </div>
                    </td>
                    <td>15000</td>
                    <td>100</td>
                    <td>1250</td>
                    <td>Active</td>
                    <td>date</td>
                    <td>date</td>
                    <td><button class="action-buttons">Edit</button> <button class="action-buttons">Delete</button></td>
                </tr>

                <!-- More users can be added here dynamically -->
            </tbody>
        </table>
        <div class="popup-overlay" id="productPopup">
            <div class="popup-content">
                <span class="close-btn" onclick="closeProductForm()">&times;</span>

                <div class="product-type-selector">
                    <button class="product-type-btn active" data-type="normal" onclick="selectProductType('normal')">Normal Product</button>
                    <button class="product-type-btn" data-type="bidding" onclick="selectProductType('bidding')">Bidding Product</button>
                </div>

                <form id="productForm">
                    <div class="form-section">
                        <label>Product Name</label>
                        <input type="text" required>
                    </div>

                    <div class="form-section">
                        <label>Description</label>
                        <textarea rows="4"></textarea>
                    </div>

                    <div class="form-section">
                        <label for="parentCategory">Parent Category</label>
                        <select id="parentCategory" name="parentCategory" onchange="fetchSubCategories(this.value)">
                            <option value="">Select Parent Category</option>
                        </select>
                    </div>

                    <!-- Subcategory Dropdown -->
                    <div class="form-section">
                        <label for="subCategory">Sub Category</label>
                        <select id="subCategory" name="subCategory" disabled>
                            <option value="">Select Sub Category</option>
                        </select>
                    </div>

                </form>


                <div id="normalProductFields">
                    <div class="form-section">
                        <label>Price</label>
                        <input type="number" min="0" step="0.01">
                    </div>

                    <div class="form-section">
                        <label>Discount (%)</label>
                        <input type="number" min="0" max="100" value="0">
                    </div>
                </div>

                <div id="biddingProductFields" style="display:none;">
                    <div class="form-section">
                        <label>Bid Starting Price</label>
                        <input type="number" min="0" step="0.01">
                    </div>

                    <div class="form-section">
                        <label>Bid Starting Date</label>
                        <input type="date">
                    </div>

                    <div class="form-section">
                        <label>Bid Ending Date</label>
                        <input type="date">
                    </div>
                </div>

                <div class="form-section">
                    <label>Stock</label>
                    <input type="number" min="1" value="1">
                </div>

                <div class="form-section">
                    <label>Shipping Fee</label>
                    <input type="number" min="0" value="0" step="0.01">
                </div>

                <div class="form-section">
                    <label>Product Images</label>
                    <div id="imageUploadContainer" class="image-upload">
                        <input type="file" id="imageInput" accept="image/*" multiple hidden>
                        <p>Click to upload images or drag and drop here</p>
                    </div>
                    <div id="imagePreviewContainer" class="image-preview-container"></div>
                </div>


                <button type="submit" class="submit-btn">Add Product</button>
                </form>
            </div>
        </div>

    </div>
    <script src="javascript/user-page.js"></script>
    <script src="javascript/inventory-page.js"></script>
</body>

</html>