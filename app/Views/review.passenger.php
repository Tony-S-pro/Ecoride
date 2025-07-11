<?php defined('ROOT_PATH') OR exit("You don't have permission to access this resource."); ?>
<h1>Votre avis sur ce covoiturage</h1>

<section>
    <p>Vous avez participé à un covoiturage le <strong><?=$carpool['dep_date']?></strong>, à <?=$carpool['dep_time']?> au départ de <strong><?=$carpool['dep_city']?></strong><?=!empty($carpool['dep_address']) ? ' ('.$carpool['dep_address'].')' : ''?>.</p>
    <p>Merci de bien vouloir confirmer que vous êtes bien arrivé.</p>
    <p>Profitez-en pour donner votre avis sur votre expérience.</p>

    <h2>Valider votre arrivée et laisser une note et/ou un commentaire à propos votre expérience</h2>

    <div class="container">
        <form id="registerReview" action="<?= BASE_URL ?>review/register_review/<?=$carpool['id']?>" method="POST" novalidate>
            
            <?php \App\Core\Controller::set_csrf();  ?>
            
            <div class="d-flex flex-column">

                <div class="mb-3 p2">
                    <label for="rating">Note :</label>
                    <select class="form-control" name="rating" id="rating" value="<?= $_SESSION['old']['rating'] ?? '' ?>">
                        <option value="">Pas de note</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                    <?php if (!empty($_SESSION['errors']['rating'])): ?>
                        <div class="error-message"><?= $_SESSION['errors']['rating'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="comment">Commentaire :</label>
                    <textarea maxlength="255" rows="4" placeholder="Très sympatique !" class="form-control" name="comment" id="comment" value="<?= $_SESSION['old']['comment'] ?? '' ?>"></textarea>
                    <?php if (!empty($_SESSION['errors']['comment'])): ?>
                        <div class="error-message"><?= $_SESSION['errors']['comment'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="row mb-4">
                    <div class="col d-flex justify-content-center">
                        <!-- Checkbox -->
                        <div class="form-check">
                            <input name="checkObjection" id="checkObjection" class="form-check-input" type="checkbox" value="1">
                            <label class="form-check-label" for="checkObjection">Je souhaite signaler un problème et demander un remboursement.</label>
                        </div>            
                    </div>
                    <div id="check-error" class="error-message"></div>
                </div>
                
            </div>            

            <div class="p-2 mb-4">
                <button type="submit" class="btn btn-warning btn-block mb-4 " data-mdb-button-initialized="true">Créer</button>
            </div>
        </form>
    </div>
    

    <p>Retour à <a href="<?=BASE_URL?>dashboard">votre espace</a>.</p>
</section>