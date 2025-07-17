document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('#loginForm');
    const emailInput = document.querySelector('#email');
    const passwordInput = document.querySelector('#password');
    const checkInput = document.querySelector('#check');

    const emailError = document.querySelector('#email-error');
    const passwordError = document.querySelector('#password-error');
    const checkError = document.querySelector('#check-error');

    form.addEventListener('submit', function (e) {
        let isValid = true;

        // Reset error message(s)
        emailError.textContent = '';
        passwordError.textContent = '';
        checkError.textContent = "";

        const emailValue = emailInput.value.trim();
        const passwordValue = passwordInput.value;
        const checkValue = checkInput.checked;

        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (emailValue === '') {
            emailError.textContent = 'Entrer votre adresse email.';
            isValid = false;
        } else if (!emailRegex.test(emailValue)) {
            emailError.textContent = 'Adresse email non valide.';
            isValid = false;
        }

        // Psw validation
        const passwordRegex = /^(?=.*[A-Z])(?=.*\d).{8,}$/;
        if (passwordValue === '') {
            passwordError.textContent = 'Veuillez entrer votre mot de passe.';
            isValid = false;
        } else if (!passwordRegex.test(passwordValue)) {
            passwordError.textContent = 'Le mot de passe doit contenir au moins 9 caractères dont une majuscule et un chiffre.';
            isValid = false;
        }

        //checkbox ticked
        if (checkValue == false) {
            checkError.textContent = 'Veuillez accepter les cookies.';
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault(); // If error -> do not send
        }
    });
});