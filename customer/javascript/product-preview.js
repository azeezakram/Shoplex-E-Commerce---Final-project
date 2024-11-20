document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("product-preview-modal");
    const bidModal = document.getElementById("bid-product-preview-modal");
    const carousel = document.querySelector(".carousel");
    let currentIndex = 0;
    let productId = 0;

    // Event listener for Add to Cart and Buy Now buttons
    document.querySelectorAll(".add-to-cart, .buy-now, .place-bid").forEach((button) => {
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


                        if (data.bid_activate == 0) {
                            document.getElementById("modal-original-price").style.display = "inline";
                            document.getElementById("modal-discount-badge").style.display = "inline";
                            document.getElementById("modal-discounted-price").style.display = "inline";
                            document.getElementById("modal-bid-starting-price").style.display = "none";
                            document.getElementById("modal-highest-bid-price").style.display = "none";
                            document.querySelector(".place-bid-price-controller").style.display = "none";
                            document.getElementById("custom-add-to-cart").style.display = "inline-block";
                            document.getElementById("custom-buy-now").style.display = "inline-block";
                            document.getElementById("custom-place-bid").style.display = "none";
                            document.querySelector(".quantity-controller").style.display = "block";

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


                        } else {
                            // const auctionId = JSON.stringify(data.auction_history);
                            // console.log(auctionId);

                            document.getElementById("modal-original-price").style.display = "none";
                            document.getElementById("modal-discount-badge").style.display = "none";
                            document.getElementById("modal-discounted-price").style.display = "none";
                            document.getElementById("modal-bid-starting-price").style.display = "inline-block";
                            document.getElementById("modal-highest-bid-price").style.display = "inline-block";
                            document.querySelector(".place-bid-price-controller").style.display = "inline-block";
                            document.getElementById("custom-add-to-cart").style.display = "none";
                            document.getElementById("custom-buy-now").style.display = "none";
                            document.getElementById("custom-place-bid").style.display = "block";
                            document.querySelector(".quantity-controller").style.display = "none";
                            document.getElementById("modal-bid-ending_date").innerText = "";
                            document.getElementById("modal-bid-status").innerText = "";
                            document.getElementById('place-bid-price-input').value = "";
                            document.querySelector('.highest-bid-price').innerText = "";

                            // console.log("Bid starting price : " + data.bid_starting_price)
                            document.getElementById('auction-id').innerText = data.auction_history.auction_id;
                            if (data.bidding_records.length == 0) {
                                // document.getElementById("modal-highest-bid-price").innerText = "No highest bid record."
                                document.querySelector('.highest-bid-price').innerText = parseFloat(data.bid_starting_price).toFixed(2);
                                document.getElementById('place-bid-price-input').value = parseFloat(data.bid_starting_price).toFixed(2);
                            } else {
                                
                                let highest_bid = getHighestBidAmount(data, data.auction_history.auction_id);
                                document.getElementById("modal-highest-bid-price").style.display = "inline-block";
                                
                                document.querySelector('.highest-bid-price').innerText = `${Number(highest_bid).toFixed(2)}`;

                                // const highestBidSpan = document.createElement("span");
                                // highestBidSpan.classList.add("highest-bid-price");
                                // highestBidSpan.textContent = `${Number(highest_bid).toFixed(2)}`;
                                // document.getElementById('modal-highest-bid-price').appendChild(highestBidSpan);
                                // document.getElementById('modal-highest-bid-price').innerText = `Highest Bid Amount: LKR. ${parseFloat(highest_bid).toFixed(2)}`;
                                document.getElementById('place-bid-price-input').value = parseFloat(highest_bid).toFixed(2);
                                
                            }

                            if (data.bid_starting_price) {
                                document.getElementById("modal-bid-starting-price").textContent = "LKR. " + data.bid_starting_price;
                                
                                document.getElementById("modal-bid-starting-price").style.display = "inline-block";
                                
                            } else {
                                document.getElementById("modal-original-price").style.display = "none";
                            }
                            
                            if (data.auction_history && data.auction_history.end_time) {
                                // Ensure the end_time is parsed correctly
                                const inputDate = data.auction_history.end_time;
                                const inputDateTime = new Date(inputDate);
                            
                                // Check if the parsed date is valid
                                if (!isNaN(inputDateTime.getTime())) {
                                    const currentDate = new Date();
                                    const dateOnly = inputDateTime.toISOString().split('T')[0];
                            
                                    // Update the ending date
                                    document.getElementById("modal-bid-ending_date").innerText = dateOnly;
                            
                                    // Determine bid status
                                    if (inputDateTime > currentDate) {
                                        document.getElementById("modal-bid-status").innerText = "Ongoing";
                                    } else {
                                        document.getElementById("modal-bid-status").innerText = "Ended";
                                    }
                                } else {
                                    // Invalid date format
                                    document.getElementById("modal-bid-ending_date").innerText = "Invalid date format";
                                    document.getElementById("modal-bid-status").innerText = "Error in end time";
                                }
                            } else {
                                // Handle missing or undefined end_time
                                document.getElementById("modal-bid-ending_date").innerText = "Ending date not decided";
                                document.getElementById("modal-bid-status").innerText = "Not available";
                            }
                            
                            


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
                            document.getElementById("modal-stock-info").style.display = "none";
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
                        inputController(data);

                        

                        // if (data.review_details) { // Explicitly check for undefined
                        //     if (data.shipping_fee > 0) {
                        //         document.getElementById('no-review-label').style.display = "none";

                                

                        //         // Add base text for the shipping fee
                        //         const baseText = document.createTextNode("Shipping Fee: LKR.");
                        //         shippingFeeDetail.appendChild(baseText);

                        //         // Create and append a span element for the fee value
                        //         const shippingFee = document.createElement("span");
                        //         shippingFee.classList.add("shipping-fee");
                        //         shippingFee.textContent = ` ${Number(data.shipping_fee).toFixed(2)}`; // Add space before value
                        //         shippingFeeDetail.appendChild(shippingFee);

                        //     } else {
                        //         document.getElementById('no-review-label').style.display = "block";
                        //         shippingFeeDetail.style.display = "inline";
                        //     }
                        // } else {
                        //     // Hide the element if no shipping fee is provided
                        //     shippingFeeDetail.style.display = "none";
                        // }



                        // console.log(data.review_details);
                        const reviewsContainer = document.getElementById("reviews-container");
                        // document.getElementById('no-review-label').style.display = "none";

                        if (data.review_details && data.review_details.length > 0) {
                            
                            document.getElementById('no-review-label').style.display = "none";

                            reviewsContainer.innerHTML = '';
                            // Loop through each review and create HTML elements
                            data.review_details.forEach(review => {
                                // Create review item container
                                const reviewItem = document.createElement("div");
                                reviewItem.classList.add("review-item");
                
                                // Add reviewer name
                                const reviewerName = document.createElement("div");
                                reviewerName.classList.add("reviewer-name");
                                reviewerName.textContent = review.reviewer_name;
                                reviewItem.appendChild(reviewerName);
                
                                // Add review rating
                                const reviewRating = document.createElement("div");
                                reviewRating.classList.add("review-rating");
                                reviewRating.textContent = "⭐".repeat(review.rating); // Display stars
                                reviewItem.appendChild(reviewRating);
                
                                // Add review comment
                                const reviewComment = document.createElement("div");
                                reviewComment.classList.add("review-content");
                                reviewComment.textContent = review.review_content;
                                reviewItem.appendChild(reviewComment);
                
                                // Add review date
                                const reviewDate = document.createElement("div");
                                reviewDate.classList.add("review-date");
                                reviewDate.textContent = `Reviewed on: ${new Date(review.created_at).toLocaleDateString()}`;
                                reviewItem.appendChild(reviewDate);
                
                                // Append the review item to the container
                                reviewsContainer.appendChild(reviewItem);
                            });

                        } 
                        else {
                            // Show a message if no reviews are found
                            reviewsContainer.innerHTML = '';
                            document.getElementById('no-review-label').style.display = "block";
                        }











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


    function getHighestBidAmount(data, auction_id) {
        // Filter bidding records for the specified auction_id
        const biddingRecords = data.bidding_records.filter(record => record.auction_id === auction_id);

        const highestBid = biddingRecords.reduce((max, record) => {
            const bidAmount = parseFloat(record.bid_amount) || 0; // Ensure bid_amount is a number
            return bidAmount > max ? bidAmount : max;
        }, 0);
        
        console.log(highestBid);

        return highestBid;
    }
    

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



    function inputController(data) {
        if (data.bid_activate == 0) {
            const quantityInput = document.getElementById("quantity-input");
    
            if (quantityInput) {
                const increaseButton = document.getElementById("increase-quantity");
                const decreaseButton = document.getElementById("decrease-quantity");
    
                // Function to add event listeners to buttons
                function attachEventListeners() {
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
                }
    
                // Clone buttons and reset event listeners when closing the preview
                function resetPreview() {
                    // Check if buttons exist before trying to clone them
                    const increaseButton = document.getElementById("increase-quantity");
                    const decreaseButton = document.getElementById("decrease-quantity");
    
                    if (increaseButton && decreaseButton) {
                        const cloneIncreaseButton = increaseButton.cloneNode(true);
                        const cloneDecreaseButton = decreaseButton.cloneNode(true);
    
                        increaseButton.parentNode.replaceChild(cloneIncreaseButton, increaseButton);
                        decreaseButton.parentNode.replaceChild(cloneDecreaseButton, decreaseButton);
    
                        // Reattach event listeners to the cloned buttons
                        attachEventListeners();
                    } else {
                        console.warn("Increase or decrease buttons not found for replacement.");
                    }
                }
    
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
    
                // Attach event listeners initially
                attachEventListeners();
    
                // Close the preview and reset buttons
                document.querySelector(".close-button").addEventListener("click", () => {
                    // Close the modal
                    const modal = document.querySelector(".modal");
                    if (modal) {
                        modal.classList.remove("show");
                    }
    
                    // Reset buttons when closing the preview
                    resetPreview();
                });
            } else {
                console.warn("Element with ID 'product-quantity' not found.");
            }
                
        } else {
            const placeBidPriceInput = document.getElementById("place-bid-price-input");
        
            const biddingRecords = data.bidding_records.filter(record => record.auction_id === data.auction_history.auction_id);
        
            const highestBid = biddingRecords.reduce((max, record) => {
                const bidAmount = parseFloat(record.bid_amount) || 0; // Ensure bid_amount is a number
                return bidAmount > max ? bidAmount : max;
            }, 0);
        
            if (placeBidPriceInput) {
                const increaseBidPriceButton = document.getElementById("increase-bid-price");
                const decreaseBidPriceButton = document.getElementById("decrease-bid-price");
        
                // Clear any existing event listeners
                const cloneIncreaseButton = increaseBidPriceButton.cloneNode(true);
                const cloneDecreaseButton = decreaseBidPriceButton.cloneNode(true);
        
                increaseBidPriceButton.parentNode.replaceChild(cloneIncreaseButton, increaseBidPriceButton);
                decreaseBidPriceButton.parentNode.replaceChild(cloneDecreaseButton, decreaseBidPriceButton);
        
                // Assign new event listeners
                cloneIncreaseButton.addEventListener("click", () => {
                    const currentValue = parseFloat(placeBidPriceInput.value);
        
                    // Allow increasing bid price beyond the highest bid
                    placeBidPriceInput.value = currentValue + 1;
                    cloneDecreaseButton.disabled = false; // Enable decrease button
                    cloneDecreaseButton.style.pointerEvents = "auto";
                    cloneDecreaseButton.style.opacity = "1";
                });
        
                cloneDecreaseButton.addEventListener("click", () => {
                    const currentValue = parseFloat(placeBidPriceInput.value);
        
                    if (currentValue > highestBid) {
                        placeBidPriceInput.value = currentValue - 1;
                        cloneIncreaseButton.disabled = false; // Enable increase button
                        cloneIncreaseButton.style.pointerEvents = "auto";
                        cloneIncreaseButton.style.opacity = "1";
                    }
        
                    if (currentValue - 1 <= highestBid) {
                        cloneDecreaseButton.disabled = true;
                        cloneDecreaseButton.style.pointerEvents = "none"; // Disable hover effect
                        cloneDecreaseButton.style.opacity = "0.6"; // Optional: Dim button
                    }
                });
        
                // Initial button state
                if (parseFloat(placeBidPriceInput.value) >= highestBid) {
                    cloneIncreaseButton.disabled = false;
                    cloneIncreaseButton.style.pointerEvents = "auto";
                    cloneIncreaseButton.style.opacity = "1";
                } else {
                    cloneIncreaseButton.disabled = true;
                    cloneIncreaseButton.style.pointerEvents = "none";
                    cloneIncreaseButton.style.opacity = "0.6";
                }
        
                if (parseFloat(placeBidPriceInput.value) <= data.auction_history.starting_price) {
                    cloneDecreaseButton.disabled = true;
                    cloneDecreaseButton.style.pointerEvents = "none";
                    cloneDecreaseButton.style.opacity = "0.6";
                } else {
                    cloneDecreaseButton.disabled = false;
                    cloneDecreaseButton.style.pointerEvents = "auto";
                    cloneDecreaseButton.style.opacity = "1";
                }
            } else {
                console.warn("Element with ID 'place-bid-price-input' not found.");
            }
        }
        
    }

    



    // Close modal
    document.querySelector(".close-button").addEventListener("click", () => {
        // Close the modal
        modal.classList.remove("show");

        // Reset quantity input
        const quantityInput = document.getElementById("quantity-input");
        const increaseButton = document.getElementById("increase-quantity");
        const decreaseButton = document.getElementById("decrease-quantity");
        const increaseBidPriceButton = document.getElementById("increase-bid-price");
        const decreaseBidPriceButton = document.getElementById("decrease-bid-price");

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

            increaseBidPriceButton.disabled = false; // Enable the increase button
            increaseBidPriceButton.style.pointerEvents = "auto";
            increaseBidPriceButton.style.opacity = "1";

            decreaseBidPriceButton.disabled = true; // Disable the decrease button
            decreaseBidPriceButton.style.pointerEvents = "none";
            decreaseBidPriceButton.style.opacity = "0.6";
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
        document.getElementById("confirm-order-btn").addEventListener("click", function () {
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
                        successMessage.innerText = "Order has been placed successfully!";
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



    document.getElementById("custom-place-bid").addEventListener("click", () => {

        const auctionId = parseInt(document.getElementById("auction-id").innerText);
        const placeBidInput = parseFloat(document.getElementById("place-bid-price-input").value);
        const highestBidElement = document.querySelector(".highest-bid-price");
        const highestBid = highestBidElement ? parseFloat(highestBidElement.innerHTML).toFixed(2) : 0.00;
        // const quantity = document.getElementById("quantity-input").value;

        if (!productId || !placeBidInput || placeBidInput <= 0) {
            alert("Invalid product or quantity.");
            return;
        }

        console.log(placeBidInput)
        console.log(highestBid)

        if (placeBidInput <= highestBid) {
            const successMessage = document.getElementById("success-message");
            successMessage.classList.add("show");
            successMessage.innerText = `Bid amount should be higher than LKR. ${highestBid}!`;
            successMessage.style.backgroundColor = "red";
            // Hide the message after 3 seconds
            setTimeout(() => {
                successMessage.classList.remove("show");
            }, 3000);
            return;
        }

        // alert("Processeing");
        fetch('other-php/place-bid.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}&auction_id=${auctionId}&bid_amount=${placeBidInput}`,
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
                    successMessage.innerText = data.message;
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

});
