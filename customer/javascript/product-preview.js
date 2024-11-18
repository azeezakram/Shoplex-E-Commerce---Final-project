document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("product-preview-modal");
    const carousel = document.querySelector(".carousel");
    let currentIndex = 0;
    let productId = 0;

    // Event listener for Add to Cart and Buy Now buttons
    document.querySelectorAll(".add-to-cart, .buy-now").forEach((button) => {
        button.addEventListener("click", function () {
            productId = this.dataset.productId;

            if (!productId) {
                alert("Invalid product ID.");
                return;
            }

            // alert("Product ID: " + productId);

            // Fetch product details
            fetch(`other-php/fetch_product_details.php?product_id=${productId}`)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        // console.log(JSON.stringify(data));
                        // Populate modal
                        document.getElementById("modal-product-name").textContent = data.product_name;
                        data.description == null ? document.getElementById("modal-product-description").textContent = "No description" : document.getElementById("modal-product-description").textContent = data.description;;
                        document.getElementById("modal-discounted-price").textContent = "LKR. " + data.discounted_price;

                        const ratingContainer = document.getElementById("modal-product-rating");

        // Clear previous ratings and reviews to prevent duplicates
                        ratingContainer.innerHTML = ""; // This will clear the old stars and review count

                        if (data.product_rating && data.product_review_count) {
                            const rating = data.product_rating; 
                            
                            for (let i = 1; i <= 5; i++) {
                                const star = document.createElement("span");
                                star.classList.add("fa");
                                if (i <= rating) {
                                    star.classList.add("fa-star", "checked"); // Add both classes separately
                                } else {
                                    star.classList.add("fa-star"); // Add only the "fa-star" class
                                }                                
                                ratingContainer.appendChild(star);
                            }

                            const ratingPoint = document.createElement("span");
                            ratingPoint.classList.add("rating-point");
                            ratingPoint.textContent = `(${Number(data.product_rating).toFixed(1)})`;
                            ratingContainer.appendChild(ratingPoint);

                            const reviewCount = document.createElement("span");
                            reviewCount.classList.add("review-count");
                            reviewCount.textContent = `(${data.product_review_count} reviews)`;
                            ratingContainer.appendChild(reviewCount);
                        } else {
                            for (let i = 1; i <= 5; i++) {
                                const star = document.createElement("span");
                                star.classList.add("fa");
                                star.classList.add("fa-star");
                                ratingContainer.appendChild(star);
                            }

                            const reviewCount = document.createElement("span");
                            reviewCount.classList.add("review-count");
                            reviewCount.textContent = `(${data.product_review_count} reviews)`;
                            ratingContainer.appendChild(reviewCount);
                        }

                        if (data.original_price) {
                            document.getElementById("modal-original-price").textContent = "LKR. " + data.original_price;
                            document.getElementById("modal-original-price").style.display = "inline";
                        } else {
                            document.getElementById("modal-original-price").style.display = "none";
                        }

                        if (data.discount_percentage) {
                            document.getElementById("modal-discount-badge").textContent = data.discount_percentage + "% off";
                            document.getElementById("modal-discount-badge").style.display = "inline";
                        } else {
                            document.getElementById("modal-discount-badge").style.display = "none";
                        }

                        if (data.stock) {
                            if (data.stock > 10) {
                                document.getElementById("modal-stock-info").textContent = "Availability: In Stock";
                                document.getElementById("modal-stock-info").style.display = "inline";
                            } else {
                                document.getElementById("modal-stock-info").textContent = "Availability: " + data.stock;
                                document.getElementById("modal-stock-info").style.display = "inline";
                            }
                            
                        } else {
                            document.getElementById("modal-shipping-info").style.display = "none";
                        }


                        const shippingFeeDetail = document.getElementById("modal-shipping-info");

                        shippingFeeDetail.innerHTML = "";

                        if (data.shipping_fee !== undefined) { // Explicitly check for undefined
                            if (data.shipping_fee > 0) {
                                shippingFeeDetail.style.display = "inline";

                                // Add base text for the shipping fee
                                const baseText = document.createTextNode("Shipping Fee: LKR.");
                                shippingFeeDetail.appendChild(baseText);

                                // Create and append a span element for the fee value
                                const shippingFee = document.createElement("span");
                                shippingFee.classList.add("shipping-fee");
                                shippingFee.textContent = ` ${Number(data.shipping_fee).toFixed(2)}`; // Add space before value
                                shippingFeeDetail.appendChild(shippingFee);

                            } else {
                                shippingFeeDetail.textContent = "Free Shipping";
                                shippingFeeDetail.style.display = "inline";
                            }
                        } else {
                            // Hide the element if no shipping fee is provided
                            shippingFeeDetail.style.display = "none";
                        }







                        // Populate carousel
                        carousel.innerHTML = ""; // Clear previous images
                        if (data.pictures.length > 0) {
                            data.pictures.forEach((picture) => {
                                const img = document.createElement("img");
                                img.src = "../" + picture;
                                img.classList.add("carousel-image");
                                carousel.appendChild(img);
                            });
                        } else {
                            const img = document.createElement("img");
                            img.src = "../images/product-images/no_picture.jpg";
                            img.classList.add("carousel-image");
                            carousel.appendChild(img);
                        }

                        // Reset carousel position
                        currentIndex = 0;
                        updateCarousel();

                        // Ensure the new button IDs are referenced in your JavaScript
                        const addToCartButton = document.getElementById("custom-add-to-cart");
                        const buyNowButton = document.getElementById("custom-buy-now");

                        if (!addToCartButton || !buyNowButton) {
                            console.error("Custom buttons not found in the DOM.");
                        } else {
                            // console.log("Custom Add to Cart Button: ", window.getComputedStyle(addToCartButton).display);
                            // console.log("Custom Buy Now Button: ", window.getComputedStyle(buyNowButton).display);

                            // Make buttons visible
                            addToCartButton.style.display = "inline-block";
                            buyNowButton.style.display = "inline-block";
                        }

                        // Show modal
                        // modal.style.display = "flex";
                        modal.classList.add("show");


                        // Show modal and buttons
                        // modal.style.display = "flex";
                        // document.getElementById("modal-add-to-cart").style.display = "inline-block";
                        // document.getElementById("modal-buy-now").style.display = "inline-block";
                    } else {
                        alert(data.message || "Failed to fetch product details. Please try again.");
                    }
                })
                .catch((error) => {
                    console.error("Error fetching product details:", error);
                    alert("An error occurred while fetching product details.");
                });
        });
    });

    // Carousel navigation
    function updateCarousel() {
        const images = document.querySelectorAll(".carousel-image");
        const offset = -currentIndex * 100; // Calculate the offset
        carousel.style.transform = `translateX(${offset}%)`;
    }

    document.getElementById("prev-btn").addEventListener("click", () => {
        if (currentIndex > 0) {
            currentIndex--;
            updateCarousel();
        }
    });

    document.getElementById("next-btn").addEventListener("click", () => {
        const totalImages = document.querySelectorAll(".carousel-image").length;
        if (currentIndex < totalImages - 1) { // Fix the condition
            currentIndex++;
            updateCarousel();
        }
    });

    const quantityInput = document.getElementById("quantity-input");

    if (quantityInput) {
        const increaseButton = document.getElementById("increase-quantity");
        const decreaseButton = document.getElementById("decrease-quantity");

        increaseButton.addEventListener("click", () => {
            const currentValue = parseInt(quantityInput.value);

            if (currentValue < 9) {
                quantityInput.value = currentValue + 1;
                decreaseButton.disabled = false; // Enable decrease button
                decreaseButton.style.pointerEvents = "auto";
                decreaseButton.style.opacity = "1";
            }

            if (currentValue + 1 === 9) {
                increaseButton.disabled = true;
                increaseButton.style.pointerEvents = "none"; // Disable hover effect
                increaseButton.style.opacity = "0.6"; // Optional: Dim button
            }
        });

        decreaseButton.addEventListener("click", () => {
            const currentValue = parseInt(quantityInput.value);

            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
                increaseButton.disabled = false; // Enable increase button
                increaseButton.style.pointerEvents = "auto";
                increaseButton.style.opacity = "1";
            }

            if (currentValue - 1 === 1) {
                decreaseButton.disabled = true;
                decreaseButton.style.pointerEvents = "none"; // Disable hover effect
                decreaseButton.style.opacity = "0.6"; // Optional: Dim button
            }
        });

        // Initial button state
        if (parseInt(quantityInput.value) === 8) {
            increaseButton.disabled = true;
            increaseButton.style.pointerEvents = "none";
            increaseButton.style.opacity = "0.6";
        }

        if (parseInt(quantityInput.value) === 1) {
            decreaseButton.disabled = true;
            decreaseButton.style.pointerEvents = "none";
            decreaseButton.style.opacity = "0.6";
        }
    } else {
        console.warn("Element with ID 'product-quantity' not found.");
    }

    

    // Close modal
    document.querySelector(".close-button").addEventListener("click", () => {
        // Close the modal
        modal.classList.remove("show");
    
        // Reset quantity input
        const quantityInput = document.getElementById("quantity-input");
        const increaseButton = document.getElementById("increase-quantity");
        const decreaseButton = document.getElementById("decrease-quantity");
    
        if (quantityInput && increaseButton && decreaseButton) {
            // Reset quantity value
            quantityInput.value = "1";
    
            // Reset button states
            increaseButton.disabled = false; // Enable the increase button
            increaseButton.style.pointerEvents = "auto";
            increaseButton.style.opacity = "1";
    
            decreaseButton.disabled = true; // Disable the decrease button
            decreaseButton.style.pointerEvents = "none";
            decreaseButton.style.opacity = "0.6";
        } else {
            console.warn("Quantity input or control buttons not found.");
        }
    });
    
    


    
    
    document.getElementById("custom-add-to-cart").addEventListener("click", () => {
        // alert("Clicked");
        
        // const productId = button.dataset.productId;
        const quantity = document.getElementById("quantity-input").value;

        if (!productId || !quantity || quantity <= 0) {
            alert("Invalid product or quantity.");
            return;
        }
        // alert("Processeing");
        fetch('other-php/add-to-cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}&quantity=${quantity}`,
        })
            .then((response) => {
                // console.log("Response received:", response);
                
                // Check if the response is not JSON
                if (!response.ok || !response.headers.get('content-type')?.includes('application/json')) {
                    return response.text().then(text => {
                        console.error("Received non-JSON response:", text);
                        throw new Error("Expected JSON, but received HTML or plain text.");
                    });
                }
                
                return response.json();
            })
            .then((data) => {
                if (data.success) {
                    const successMessage = document.getElementById("success-message");
                    successMessage.classList.add("show");
                    successMessage.innerText = "Product added to cart successfully!";
                    successMessage.style.backgroundColor = "#11942f";
                    // Hide the message after 3 seconds
                    setTimeout(() => {
                        successMessage.classList.remove("show");
                    }, 3000);
                    // Hide the modal and reset the values
                    document.getElementById("orderConfirmationModal").style.display = "none";
                    isOrderPlaced = true; // Mark the order as placed
                } else {
                    const successMessage = document.getElementById("success-message");
                    successMessage.classList.add("show");
                    successMessage.innerText = data.message;
                    successMessage.style.backgroundColor = "red";
                    // Hide the message after 3 seconds
                    setTimeout(() => {
                        successMessage.classList.remove("show");
                    }, 3000);
                    // alert(data.message || "Failed to add product to cart.");
                    // alert(data.message); // This shows the error message from the PHP script
        
                    // Optionally, redirect to the login page if necessary
                    if (data.message === 'Please log in to add items to your cart.') {
                        window.location.href = "signin-page.php"; // Redirect to sign-in page
                    }
                }
            })
            .catch((error) => {
                console.error("Error adding product to cart:", error);
                alert("An error occurred. Please try again.");
            });
        
        
    });


    // Show the product preview modal when "Buy Now" is clicked
    document.getElementById("custom-buy-now").addEventListener("click", () => {

        
        const quantity = parseInt(document.getElementById("quantity-input").value, 10);
        const priceAfterDiscount = parseFloat(document.getElementById("modal-discounted-price").textContent.replace("LKR. ", ""));
        
        let shippingFeeElement = document.querySelector(".shipping-fee");
        const shippingFee = shippingFeeElement 
            ? parseFloat(shippingFeeElement.textContent.replace("(", "").replace(")", "").trim()) 
            : 0;

        // Calculate the subtotal
        const subtotal = priceAfterDiscount * quantity;

        // Update modal with calculated values
        document.getElementById("modal-quantity-info").textContent = quantity;
        document.getElementById("modal-price-info").textContent = priceAfterDiscount.toFixed(2);
        document.getElementById("modal-shipping-fee-info").textContent = shippingFee.toFixed(2);
        document.getElementById("modal-total-info").textContent = (subtotal + shippingFee).toFixed(2);

        // Show the order confirmation modal
        const orderConfirmationModal = document.getElementById("orderConfirmationModal");
        orderConfirmationModal.style.display = "flex";
       
        orderConfirmationModal.classList.add("show");
        // orderConfirmationModal.classList.add("show");
        
        // Declare the productId here or get it from a relevant source
        // const productId = document.getElementById("custom-buy-now").dataset.productId;

        let isOrderPlaced = false;

        // Handle the confirm order button click
        document.getElementById("confirm-order-btn").addEventListener("click", function() {
            if (isOrderPlaced) {
                return; // Prevent placing the same order again
            }

            // Disable the confirm button to prevent further clicks
            this.disabled = true;
            this.textContent = "Processing...";

            // Sending the order details to the server
            fetch('other-php/buy-now.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${productId}&quantity=${quantity}&price_after_discount=${priceAfterDiscount}&subtotal=${subtotal}&shipping_fee=${shippingFee}`,
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    const successMessage = document.getElementById("success-message");
                    successMessage.classList.add("show");
                    successMessage.innerText = "Order placed successfully!";
                    successMessage.style.backgroundColor = "#11942f";
                    // Hide the message after 3 seconds
                    setTimeout(() => {
                        successMessage.classList.remove("show");
                    }, 3000);
                    // Hide the modal and reset the values
                    document.getElementById("orderConfirmationModal").style.display = "none";
                    isOrderPlaced = true; // Mark the order as placed
                } else {
                    alert(data.message || "Failed to place the order.");
                }
            })
            .catch((error) => {
                console.error("Error placing the order:", error);
                alert("An error occurred. Please try again.");
            })
            .finally(() => {
                // Re-enable the button after the process is complete (in case of error or success)
                document.getElementById("confirm-order-btn").disabled = false;
                document.getElementById("confirm-order-btn").textContent = "Confirm Order"; // Reset the text
            });
        });

        // Handle the cancel order button click
        document.getElementById("cancel-order-btn").addEventListener("click", () => {
            // Close the modal if user cancels
            document.getElementById("orderConfirmationModal").style.display = "none";
        });
    });



});
