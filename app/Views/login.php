<script src="<?= BASE_URL ?>assets/js/login-form-validation.js" defer></script>

<div class="d-flex flex-row justify-content-center">
    <h1>Connectez-vous à Ecoride</h1>
</div>




        
<section class="w-100 p-4 d-flex justify-content-center pb-4">
                
    <form id="loginForm" action="<?= BASE_URL ?>login/login" method="post" style="width: 22rem;" novalidate>

        <?php \App\Core\Controller::set_csrf();  ?>
        
        <?php if (isset($errors)) : ?>
            <?php if (!empty($errors)) : ?>
                <div style="color:red;">
                    <?php foreach ($errors as $error) : ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div data-mdb-input-init="" class="form-outline mb-4" data-mdb-input-initialized="true">
            <label class="form-label" for="email" style="margin-left: 0px;">Address email :</label>
            <input type="email" name="email" id="email" class="form-control">
            <div id="email-error" class="error-message"></div>
        </div>

        <div data-mdb-input-init="" class="form-outline mb-4" data-mdb-input-initialized="true">
        <label class="form-label" for="password" style="margin-left: 0px;">Mot de passe :</label>
        <input type="password" name="password" id="password" class="form-control">
        <div id="password-error" class="error-message"></div>
        </div>

        <!-- 2 column grid layout for inline styling -->
        <div class="row mb-4">
        <div class="col d-flex justify-content-center">
            <!-- Checkbox -->
            <div class="form-check">
            <input name="check" id="check" class="form-check-input" type="checkbox" value="accept" required>
            <label class="form-check-label" for="check">J'ai lu les <a href="<?=BASE_APP?>mentions">mentions légales</a> et accepte l'utilisation de cookies décrite sur la page de <a href="<?=BASE_APP?>mentions/cookies">politique de cookies</a>.</label>
            </div>            
        </div>
        <div id="check-error" class="error-message"></div>
        </div>

        <div class="col mb-4">
            <!-- Simple link -->
            <a href="#!">Mot de passe oublié ?</a>
        </div>

        <!-- Submit button -->
        <button type="submit" data-mdb-button-init="" data-mdb-ripple-init="" class="btn btn-warning btn-block mb-4" data-mdb-button-initialized="true">Se connecter</button>

        <!-- Register buttons -->
        <div class="text-center">
            <p>Pas encore inscrit ? <a href="<?=BASE_APP?>signup">Rejoignez-nous</a>.</p>
        </div>
    </form>
</section>
