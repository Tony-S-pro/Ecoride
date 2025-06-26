<script src="<?= BASE_URL ?>assets/js/signup-form-validation.js" defer></script>

<div class="d-flex justify-content-center">
    <h1>Insrivez-vous sur Ecoride</h1>
</div>

<section class="w-100 p-4 d-flex justify-content-center pb-4">

    <form id="signupForm" action="<?= BASE_URL ?>signup/register" method="POST"  style="width: 22rem;" novalidate>
        <div class="mb-3">
            <label for="pseudo">Pseudo :</label>
            <input type="text" class="form-control" name="pseudo" id="pseudo" value="<?= $_SESSION['old']['pseudo'] ?? '' ?>" required>
            <?php if (!empty($_SESSION['errors']['pseudo'])): ?>
                <div class="error-message"><?= $_SESSION['errors']['pseudo'] ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="name">Nom :</label>
            <input type="text" class="form-control" name="name" id="name" value="<?= $_SESSION['old']['name'] ?? '' ?>" required>
            <?php if (!empty($_SESSION['errors']['name'])): ?>
                <div class="error-message"><?= $_SESSION['errors']['name'] ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="firstname">Prénom :</label>
            <input type="text" class="form-control" name="firstname" id="firstname" value="<?= $_SESSION['old']['firstname'] ?? '' ?>" required>
            <?php if (!empty($_SESSION['errors']['firstname'])): ?>
                <div class="error-message"><?= $_SESSION['errors']['firstname'] ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">      
            <label for="email">Email :</label>
            <input type="email" class="form-control" name="email" id="email" value="<?= $_SESSION['old']['email'] ?? '' ?>" required>
            <?php if (!empty($_SESSION['errors']['email'])): ?>
                <div class="error-message"><?= $_SESSION['errors']['email'] ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="password">Mot de passe :</label>
            <input type="password" class="form-control" name="password" id="password" required>
            <?php if (!empty($_SESSION['errors']['password'])): ?>
                <div class="error-message"><?= $_SESSION['errors']['password'] ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="confirm_password">Confirmer le mot de passe :</label>
            <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
            <?php if (!empty($_SESSION['errors']['confirm'])): ?>
                <div class="error-message"><?= $_SESSION['errors']['confirm'] ?></div>
            <?php endif; ?>
        </div>

        <div class="row mb-4">
        <div class="col d-flex justify-content-center">
            <!-- Checkbox -->
            <div class="form-check">
            <input name="check" id="check" class="form-check-input" type="checkbox" value="accept" required>
            <label class="form-check-label" for="check">J'ai lu les <a href="<?=BASE_APP?>mentions">mentions légales</a> et accepte l'utilisation de cookies décrite sur la page de <a href="<?=BASE_APP?>mentions/cookies">politique de cookies</a>.</label>
            </div>            
        </div>
        <div id="check-error" name="check-error" class="error-message"></div>
        </div>

        <button type="submit" class="btn btn-warning btn-block mb-4 " data-mdb-button-initialized="true">S'inscrire</button>
    </form>

</section>