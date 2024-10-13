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

    return message === ''; 
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

    const isEmailValid = validateEmail();

    const errors = {
        password: password === '' ? 'Password is required' : '',
    };

    document.getElementById('psw-msg').innerHTML = errors.password;

    const hasErrors = Object.values(errors).some(error => error !== '') || !isEmailValid;

    if (!hasErrors) {
        showPopup("Successfully Signed in!");
        document.getElementById('register-form').reset();
    }
});

function showPopup(message) {
    const popup = document.getElementById('popup');
    popup.innerHTML = message;
    popup.classList.add('show');  

    setTimeout(() => {
        popup.classList.remove('show');  
    }, 2000);
}

