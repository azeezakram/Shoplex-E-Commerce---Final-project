// document.querySelectorAll('.custom-button').forEach(button => {
//     button.addEventListener('click', function() {
//         let productId = this.getAttribute('data-product-id');
//         let productName = document.querySelector('#modal-product-name').textContent;
//         let discountedPrice = document.querySelector('#modal-discounted-price').textContent.replace('LKR. ', '');
//         let price = document.querySelector('#modal-original-price').textContent.replace('LKR. ', '');
//         let productImage = document.querySelector('.modal-left img').getAttribute('src'); // Get product image path
//         let discount = document.querySelector('#modal-discount-badge') 
//                           ? document.querySelector('#modal-discount-badge').textContent.replace('% off', '') / 100 
//                           : 0; // Extract discount or default to 0

//         // Send data to PHP via AJAX
//         let xhr = new XMLHttpRequest();
//         xhr.open('POST', 'add_to_cart.php', true);
//         xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
//         xhr.onload = function() {
//             if (xhr.status === 200) {
//                 alert('Product added to cart');
//             } else {
//                 alert('Error adding product to cart');
//             }
//         };

//         xhr.send(
//             'product_id=' + productId +
//             '&product_name=' + encodeURIComponent(productName) +
//             '&discounted_price=' + discountedPrice +
//             '&price=' + price +
//             '&product_image=' + encodeURIComponent(productImage) +
//             '&discount=' + discount
//         );
//     });
// });
