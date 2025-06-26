
<div class="d-flex flex-row justify-content-center">
    <h1>Connectez-vous à Ecoride</h1>
</div>

<section class="w-100 p-4 d-flex justify-content-center pb-4">
                
    <form method="post" style="width: 22rem;">

        <?php 
        if(isset($errors)) {
            if(!empty($errors)) {
                echo '<div class="alert alert-warning">'
                .implode("<br>", $errors)
                .'</div>';
            }
        }
        ?>

        <!-- Email input -->
        <div data-mdb-input-init="" class="form-outline mb-4" data-mdb-input-initialized="true">
            <input type="email" name="loginMail" id="loginMail" class="form-control">
            <label class="form-label" for="loginMail" style="margin-left: 0px;">Address mail</label>
            <div class="form-notch">
                <div class="form-notch-leading" style="width: 9px;">dqzdqzdqd</div>
                <div class="form-notch-middle" style="width: 88.8px;">eaéeéeaéa</div>
                <div class="form-notch-trailing">eéaeaéeaéeaé</div>
            </div>
        </div>

        <!-- Password input -->
        <div data-mdb-input-init="" class="form-outline mb-4" data-mdb-input-initialized="true">
        <input type="password" name="loginPsw" id="loginPsw" class="form-control">
        <label class="form-label" for="loginPsw" style="margin-left: 0px;">Mot de passe</label>
        <div class="form-notch"><div class="form-notch-leading" style="width: 9px;"></div><div class="form-notch-middle" style="width: 64px;"></div><div class="form-notch-trailing"></div></div></div>

        <!-- 2 column grid layout for inline styling -->
        <div class="row mb-4">
        <div class="col d-flex justify-content-center">
            <!-- Checkbox -->
            <div class="form-check">
            <input name="loginCheck" class="form-check-input" type="checkbox" value="accept" id="loginCheck" checked="">
            <label class="form-check-label" for="loginCheck">J'ai lu les <a href="<?=BASE_APP?>mentions">mentions légales</a> et accepte l'utilisation de cookies décrite sur la page de <a href="<?=BASE_APP?>mentions/cookies">politique de cookies</a>.</label>
            </div>
        </div>
        </div>

        <div class="col mb-4">
            <!-- Simple link -->
            <a href="#!">Mot de passe oublié ?</a>
        </div>

        <!-- Submit button -->
        <button type="submit" data-mdb-button-init="" data-mdb-ripple-init="" class="btn btn-primary btn-block mb-4" data-mdb-button-initialized="true">Se connecter</button>

        <!-- Register buttons -->
        <div class="text-center">
            <p>Pas encore inscrit ? <a href="<?=BASE_APP?>signup">Rejoignez-nous</a>.</p>
        </div>
    </form>
</section>
