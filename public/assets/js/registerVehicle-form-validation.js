document.addEventListener('DOMContentLoaded', function () {

    function clearErrors() {
        document.querySelectorAll('.error-message').forEach(error => error.remove());
    }

    document.getElementById('registerVehicle').addEventListener('submit', function (e) {
        
        clearErrors();
        
        let formIsValid = true;
        
        document.querySelectorAll('.error').forEach(error => error.remove());

        const brand = document.getElementById('brand');
        const model = document.getElementById('model');
        const registration_date = document.getElementById('registration_date');
        const registration = document.getElementById('registration');
        const color = document.getElementById('color');
        const seats = document.getElementById('seats');

        if (brand.value.trim().length < 3) {
            showError(brand, "La marque requiert au moins 3 caractères");
            formIsValid = false;
        }
        if (model.value.trim().length < 3) {
            showError(model, "Le modèle requiert au moins 3 caractères");
            formIsValid = false;
        }
        if (color.value.trim().length < 3) {
            showError(color, "La couleur requiert au moins 3 caractères");
            formIsValid = false;
        }

        const yearRegex = /^(19|20)\d{2}$/;
        if (!yearRegex.test(registration_date.value.trim())) {
            showError(registration_date, "Choisissez une année appropriée (ex: 2025)");
            formIsValid = false;
        }
        const registrationRegex = /^[A-Z]{2}[-][0-9]{3}[-][A-Z]{2}$/;
        if (!registrationRegex.test(registration.value.trim())) {
            showError(registration, "Choisissez un n° au format AA-000-AA.");
            formIsValid = false;
        }
        const seatsRegex = /^([1-9]|1[0-2])$/;
        if (!seatsRegex.test(seats.value.trim())) {
            showError(seats, "Le nombre de places disponibles doit être entre 1 et 12.");
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