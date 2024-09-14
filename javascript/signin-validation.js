// Function to toggle password visibility
function togglePassword(id) {
    const passwordInput = document.getElementById(id);
    const eyeIcon = passwordInput.nextElementSibling.querySelector('i');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
}

function validateEmail() {
    const email = document.getElementById('email').value.trim();  
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const message = emailPattern.test(email) ? "" : "Invalid email address";
    document.getElementById('email-msg').innerHTML = message;

    return message === '';  // Return whether the email is valid
}


document.getElementById('email').addEventListener('input', validateEmail);

document.getElementById('password').addEventListener('input', function() {
    const password = document.getElementById('password').value.trim();
    const message = password === '' ? 'Password is required' : '';
    document.getElementById('psw-msg').innerHTML = message;
});


// Form submission validation
document.getElementById('signin-form').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const password = document.getElementById('password').value.trim();

    // Validate email first
    const isEmailValid = validateEmail();

    // Validate form fields
    const errors = {
        password: password === '' ? 'Password is required' : '',
    };

    document.getElementById('psw-msg').innerHTML = errors.password;

    const hasErrors = Object.values(errors).some(error => error !== '') || !isEmailValid;

    // Check if there are any errors
    if (!hasErrors) {
        showPopup("Successfully Signed in!");
        document.getElementById('register-form').reset();
    }
});

function showPopup(message) {
    const popup = document.getElementById('popup');
    popup.innerHTML = message;
    popup.classList.add('show');  // Add the show class to make it visible

    setTimeout(() => {
        popup.classList.remove('show');  // Remove the show class after 2 seconds
    }, 2000);
}

