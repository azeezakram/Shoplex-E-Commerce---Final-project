function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('collapsed');
}



const parentCategories = {
    'electronics': ['Laptops', 'Smartphones', 'Accessories'],
    'clothing': ['Men', 'Women', 'Kids']
};

document.getElementById('addProductBtn').addEventListener('click', function() {
    document.getElementById('productPopup').classList.add('show');
}); 

function closeProductForm() {
    document.getElementById('productPopup').classList.remove('show');
}

function selectProductType(type) {
    const normalFields = document.getElementById('normalProductFields');
    const biddingFields = document.getElementById('biddingProductFields');
    const typeButtons = document.querySelectorAll('.product-type-btn');

    typeButtons.forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.type === type) {
            btn.classList.add('active');
        }
    });

    if (type === 'normal') {
        normalFields.style.display = 'block';
        biddingFields.style.display = 'none';
    } else {
        normalFields.style.display = 'none';
        biddingFields.style.display = 'block';
    }
}

function updateSubCategories() {
    const parentCategory = document.getElementById('parentCategory').value;
    const subCategorySelect = document.getElementById('subCategory');

    subCategorySelect.innerHTML = '<option value="">Select Sub Category</option>';
    
    if (parentCategory) {
        subCategorySelect.disabled = false;
        parentCategories[parentCategory].forEach(subCat => {
            const option = document.createElement('option');
            option.value = subCat.toLowerCase();
            option.textContent = subCat;
            subCategorySelect.appendChild(option);
        });
    } else {
        subCategorySelect.disabled = true;
    }
}

document.querySelector('.image-upload').addEventListener('click', function() {
    this.querySelector('input[type="file"]').click();
});

document.querySelector('.image-upload input[type="file"]').addEventListener('change', function(e) {
    const files = e.target.files;
    this.parentElement.querySelector('p').textContent = `${files.length} image(s) selected`;
});


document.addEventListener('DOMContentLoaded', () => {
    const imageUploadContainer = document.getElementById('imageUploadContainer');
    const imageInput = document.getElementById('imageInput');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');

    // Handle clicking on the upload container
    imageUploadContainer.addEventListener('click', () => {
        imageInput.click();
    });

    // Handle file selection
    imageInput.addEventListener('change', handleFileSelect);

    // Handle drag-and-drop functionality
    imageUploadContainer.addEventListener('dragover', (event) => {
        event.preventDefault();
        imageUploadContainer.classList.add('dragging');
    });

    imageUploadContainer.addEventListener('dragleave', () => {
        imageUploadContainer.classList.remove('dragging');
    });

    imageUploadContainer.addEventListener('drop', (event) => {
        event.preventDefault();
        imageUploadContainer.classList.remove('dragging');
        handleFileSelect(event.dataTransfer);
    });

    // Function to handle file selection
    function handleFileSelect(fileInput) {
        const files = fileInput.files || fileInput;
        Array.from(files).forEach(file => {
            if (file.type.startsWith('images/')) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    createImagePreview(event.target.result, file.name);
                };
                reader.readAsDataURL(file);
            } else {
                alert('Only image files are allowed!');
            }
        });
        imageInput.value = ''; // Reset input
    }

    // Create image preview with remove button
    function createImagePreview(imageSrc, imageName) {
        const imagePreview = document.createElement('div');
        imagePreview.className = 'image-preview';

        const img = document.createElement('img');
        img.src = imageSrc;
        img.alt = imageName;

        const removeBtn = document.createElement('span');
        removeBtn.className = 'remove-btn';
        removeBtn.innerHTML = '&times;';
        removeBtn.onclick = () => imagePreview.remove();

        imagePreview.appendChild(img);
        imagePreview.appendChild(removeBtn);
        imagePreviewContainer.appendChild(imagePreview);
    }
});

function fetchSubCategories(parentId) {
    const subCategoryDropdown = document.getElementById('subCategory');
    subCategoryDropdown.innerHTML = '<option value="">Loading...</option>'; // Show loading message

    // Make sure parentId is valid
    if (!parentId) {
        subCategoryDropdown.innerHTML = '<option value="">Select Sub Category</option>';
        subCategoryDropdown.disabled = true;
        return;
    }

    // Make an AJAX request to fetch subcategories
    fetch(`?parent_id=${parentId}`)
        .then(response => {
            // Check if the response is OK (status 200)
            if (!response.ok) {
                throw new Error('Network response was not ok.');
            }
            return response.json();
        })
        .then(data => {
            subCategoryDropdown.innerHTML = '<option value="">Select Sub Category</option>'; // Reset options
            
            // Check if data is valid and not empty
            if (data && data.length > 0) {
                data.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.category_id;
                    option.textContent = category.category_name;
                    subCategoryDropdown.appendChild(option);
                });
                subCategoryDropdown.disabled = false; // Enable dropdown
            } else {
                subCategoryDropdown.innerHTML = '<option value="">No subcategories available</option>';
                subCategoryDropdown.disabled = true; // Disable dropdown
            }
        })
        .catch(error => {
            console.error('Error fetching subcategories:', error);
            subCategoryDropdown.innerHTML = '<option value="">Error loading subcategories</option>';
            subCategoryDropdown.disabled = true; // Disable dropdown on error
        });
}

// Fetch parent categories when the page loads
window.addEventListener('DOMContentLoaded', (event) => {
    fetchParentCategories();
});

function fetchParentCategories() {
    const parentCategoryDropdown = document.getElementById('parentCategory');
    
    // Make an AJAX request to fetch parent categories
    fetch('other-php/fetchSubCategories.php')
        .then(response => response.json())
        .then(data => {
            parentCategoryDropdown.innerHTML = '<option value="">Select Parent Category</option>'; // Reset options
            data.forEach(category => {
                const option = document.createElement('option');
                option.value = category.category_id;
                option.textContent = category.category_name;
                parentCategoryDropdown.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching parent categories:', error);
            parentCategoryDropdown.innerHTML = '<option value="">Error loading parent categories</option>';
        });
}



