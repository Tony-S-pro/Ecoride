document.addEventListener('DOMContentLoaded', function () {

    function clearErrors() {
        document.querySelectorAll('.error-message').forEach(error => error.remove());
    }

    document.getElementById('signupForm').addEventListener('submit', function (e) {
        
        clearErrors();
        
        let formIsValid = true;
        
        document.querySelectorAll('.error').forEach(error => error.remove());

        const pseudo = document.getElementById('pseudo');
        const name = document.getElementById('name');
        const firstname = document.getElementById('firstname');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const confirm = document.getElementById('confirm_password');

        if (pseudo.value.trim().length < 3) {
            showError(pseudo, "Le pseudo requiert au moins 3 caractères.");
            formIsValid = false;
        }
        if (name.value.trim().length < 3) {
            showError(name, "Le nom requiert au moins 3 caractères.");
            formIsValid = false;
        }
        if (firstname.value.trim().length < 3) {
            showError(firstname, "Le prénom requiert au moins 3 caractères.");
            formIsValid = false;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value.trim())) {
            showError(email, "Adresse mail non valide.");
            formIsValid = false;
        }

        const passwordValue = password.value;
        const confirmValue = confirm.value;
        const passwordRegex = /^(?=.*[A-Z])(?=.*\d).{9,}$/;

        if (!passwordRegex.test(passwordValue)) {
            showError(password, "Le mot de passe doit contenir au moins 9  caractères (au moins une majuscule et un chiffre).");
            formIsValid = false;
        }

        if (passwordValue !== confirmValue) {
            showError(confirm, "Mots de passe différents.");
            formIsValid = false;
        }

        if (!formIsValid) {
            e.preventDefault();
        }
    });

    // a message pops up under the field in case of error
    function showError(input, message) {
        let errorElement = input.nextElementSibling;
        if (!errorElement || !errorElement.classList.contains('error-message')) {
            errorElement = document.createElement('div');
            errorElement.classList.add('error-message');
            errorElement.style.color = 'red';
            errorElement.style.fontSize = '0.9em';
            input.parentNode.insertBefore(errorElement, input.nextSibling);
        }
        errorElement.textContent = message;
    }
});