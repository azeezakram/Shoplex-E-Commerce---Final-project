document.addEventListener('DOMContentLoaded', () => {
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const emailMessage = document.createElement('small');
    const passwordMessage = document.createElement('small');
    emailMessage.classList.add('error-msg');
    passwordMessage.classList.add('error-msg');

    emailInput.insertAdjacentElement('afterend', emailMessage);
    passwordInput.insertAdjacentElement('afterend', passwordMessage);

    // Email validation
    emailInput.addEventListener('input', () => {
        const email = emailInput.value.trim();
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        if (!emailPattern.test(email)) {
            emailMessage.textContent = 'Invalid email format.';
        } else {
            // Clear message and check if email already exists
            emailMessage.textContent = '';
            checkEmailExists(email);
        }
    });

    // Password validation
    passwordInput.addEventListener('input', () => {
        const password = passwordInput.value.trim();
        const hasSymbol = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        const hasNumber = /\d/.test(password);
        const hasUppercase = /[A-Z]/.test(password);
        const hasLowercase = /[a-z]/.test(password);

        if (password.length < 6) {
            passwordMessage.textContent = 'Password must be at least 6 characters.';
        } else if (!hasSymbol) {
            passwordMessage.textContent = 'Password must contain at least one symbol (!, @, #, etc.).';
        } else if (!hasNumber) {
            passwordMessage.textContent = 'Password must contain at least one number.';
        } else if (!hasUppercase) {
            passwordMessage.textContent = 'Password must contain at least one uppercase letter.';
        } else if (!hasLowercase) {
            passwordMessage.textContent = 'Password must contain at least one lowercase letter.';
        } else {
            passwordMessage.textContent = '';
        }
    });

    // Form submission validation
    document.querySelector('form').addEventListener('submit', (event) => {
        const email = emailInput.value.trim();
        const password = passwordInput.value.trim();

        if (emailMessage.textContent || passwordMessage.textContent || !email || !password) {
            event.preventDefault(); // Prevent form submission
            alert('Please correct the errors before submitting the form.');
        }
    });

    // Function to check if email already exists
    function checkEmailExists(email) {
        fetch(`add-user.php?email=${encodeURIComponent(email)}`)
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    emailMessage.textContent = 'This email is already registered.';
                } else {
                    emailMessage.textContent = '';
                }
            })
            .catch(error => {
                console.error('Error checking email existence:', error);
            });
    }
});
