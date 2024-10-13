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

// Function to validate password requirements
function validatePassword() {
    const password = document.getElementById('password').value.trim();

    
    const lengthRequirement = password.length >= 6;
    const lowerCaseRequirement = /[a-z]/.test(password);
    const upperCaseRequirement = /[A-Z]/.test(password);
    const numberRequirement = /\d/.test(password);
    const specialCharRequirement = /[\W_]/.test(password);

    
    const passwordError = document.getElementById('psw-msg');

    
    if (!lengthRequirement) {
        passwordError.innerHTML = 'Password must be at least 6 characters';
    } else if (!lowerCaseRequirement) {
        passwordError.innerHTML = 'Password must include at least one lowercase letter';
    } else if (!upperCaseRequirement) {
        passwordError.innerHTML = 'Password must include at least one uppercase letter';
    } else if (!numberRequirement) {
        passwordError.innerHTML = 'Password must include at least one number';
    } else if (!specialCharRequirement) {
        passwordError.innerHTML = 'Password must include at least one special character';
    } else {
        passwordError.innerHTML = '';
    }
}

function validateEmail() {
    const email = document.getElementById('email').value.trim();  
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const message = emailPattern.test(email) ? "" : "Invalid email address";
    document.getElementById('email-msg').innerHTML = message;

    return message === '';  
}

// Live validation for form fields
document.getElementById('fName').addEventListener('input', function () {
    const fname = this.value.trim();
    const message = fname === "" ? "First name is required" : "";
    document.getElementById('fName-msg').innerHTML = message;
});

document.getElementById('lName').addEventListener('input', function () {
    const lname = this.value.trim();
    const message = lname === "" ? "Last name is required" : "";
    document.getElementById('lName-msg').innerHTML = message;
});

document.getElementById('email').addEventListener('input', validateEmail);
document.getElementById('password').addEventListener('input', validatePassword);

document.getElementById('cPassword').addEventListener('input', function () {
    const confirmPassword = this.value.trim();
    const password = document.getElementById('password').value.trim();
    const message = confirmPassword !== password ? "Passwords do not match" : "";
    document.getElementById('cPsw-msg').innerHTML = message;
});

// Form submission validation
document.getElementById('register-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const fname = document.getElementById('fName').value.trim();
    const lname = document.getElementById('lName').value.trim();
    const password = document.getElementById('password').value.trim();
    const confirmPassword = document.getElementById('cPassword').value.trim();

    const isEmailValid = validateEmail();

    const errors = {
        fName: fname === '' ? 'First name is required' : '',
        lName: lname === '' ? 'Last name is required' : '',
        password: password.length < 6 ? 'Password must be at least 6 characters' : '',
        cPassword: confirmPassword !== password ? 'Passwords do not match' : ''
    };

    document.getElementById('fName-msg').innerHTML = errors.fName;
    document.getElementById('lName-msg').innerHTML = errors.lName;
    document.getElementById('psw-msg').innerHTML = errors.password;
    document.getElementById('cPsw-msg').innerHTML = errors.cPassword;

    const hasErrors = Object.values(errors).some(error => error !== '') || !isEmailValid;

    if (!hasErrors) {
        showPopup("Successfully Registered!");
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
