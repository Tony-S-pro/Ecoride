<script src="<?= BASE_URL ?>assets/js/signup-form-validation.js" defer></script>

<div class="d-flex flex-row justify-content-center">
    <h1>Insrivez-vous sur Ecoride</h1>
</div>

<section class="w-100 p-4 d-flex justify-content-center pb-4">

    <form id="signupForm" action="<?= BASE_URL ?>signup/register" method="POST"  style="width: 22rem;" novalidate>
        <label for="pseudo">Pseudo :</label>
        <input type="text" name="pseudo" id="pseudo" value="<?= $_SESSION['old']['pseudo'] ?? '' ?>" required>
        <?php if (!empty($_SESSION['errors']['pseudo'])): ?>
            <div class="error"><?= $_SESSION['errors']['pseudo'] ?></div>
        <?php endif; ?>

        <label for="name">Nom :</label>
        <input type="text" name="name" id="name" value="<?= $_SESSION['old']['name'] ?? '' ?>" required>
        <?php if (!empty($_SESSION['errors']['name'])): ?>
            <div class="error"><?= $_SESSION['errors']['name'] ?></div>
        <?php endif; ?>

        <label for="firstname">Prénom :</label>
        <input type="text" name="firstname" id="firstname" value="<?= $_SESSION['old']['firstname'] ?? '' ?>" required>
        <?php if (!empty($_SESSION['errors']['pseudo'])): ?>
            <div class="error"><?= $_SESSION['errors']['firstname'] ?></div>
        <?php endif; ?>

        <label for="email">Email :</label>
        <input type="email" name="email" id="email" value="<?= $_SESSION['old']['email'] ?? '' ?>" required>
        <?php if (!empty($_SESSION['errors']['email'])): ?>
            <div class="error"><?= $_SESSION['errors']['email'] ?></div>
        <?php endif; ?>

        <label for="password">Mot de passe :</label>
        <input type="password" name="password" id="password" required>
        <?php if (!empty($_SESSION['errors']['password'])): ?>
            <div class="error"><?= $_SESSION['errors']['password'] ?></div>
        <?php endif; ?>

        <label for="confirm_password">Confirmer le mot de passe :</label>
        <input type="password" name="confirm_password" id="confirm_password" required>
        <?php if (!empty($_SESSION['errors']['confirm'])): ?>
            <div class="error"><?= $_SESSION['errors']['confirm'] ?></div>
        <?php endif; ?>

        <button type="submit">S'inscrire</button>
    </form>

</section>