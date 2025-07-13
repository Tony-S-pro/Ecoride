<?php defined('ROOT_PATH') OR exit("You don't have permission to access this resource."); ?>

<h1>Créez un nouveau covoiturage</h1>

<section>
    <p>Vous pouvez consulter créez un nouveau covoiturage en entrant toutres les informations necessaires çi-dessous.</p>
    <p class="text-white-50">N'oubliez pas que chaque covoiturage vous coutera 2 crédits.</p>
    <?php if (!empty($_SESSION['errors']['no_credit'])): ?>
        <div class="error-message"><?= $_SESSION['errors']['no_credit'] ?></div>
    <?php endif; ?>

    <div class="container">
        <form id="registerCarpool" action="<?= BASE_URL ?>carpooling/register_carpool" method="POST" novalidate>
            
            <div class="d-flex flex-row flex-wrap justify-content-between">

                <div class="p-2">
                    <label for="departure_date">Date du départ :</label>
                    <input type="date" name="departure_date" class="form-control" data-date-format="yyyy-mm-dd" value="<?= $_SESSION['old']['departure_date'] ?? '' ?>">
                    <?php if (!empty($_SESSION['errors']['departure_date'])): ?>
                        <div class="error-message"><?= $_SESSION['errors']['departure_date'] ?></div>
                    <?php endif; ?>
                </div>
                <!-- <div class="d-flex"> -->
                <div class="p-2">
                    <label for="departure_time">Heure du départ :</label>
                    <input type="time" name="departure_time" class="form-control" value="<?= $_SESSION['old']['departure_time'] ?? '' ?>">
                    <?php if (!empty($_SESSION['errors']['departure_time'])): ?>
                        <div class="error-message"><?= $_SESSION['errors']['departure_time'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="p-2">
                    <label for="travel_time">Durée (est. en h) :</label>
                    <input type="number" class="form-control" min="1" max="24" name="travel_time" id="travel_time" placeholder="2" value="<?= $_SESSION['old']['travel_time'] ?? '' ?>" required>
                    <?php if (!empty($_SESSION['errors']['travel_time'])): ?>
                        <div class="error-message"><?= $_SESSION['errors']['travel_time'] ?></div>
                    <?php endif; ?>
                </div>
                <!-- </div> -->
            </div>

            <div class="d-flex flex-row flex-wrap">
                <div class="p-2 flex-grow-1">
                    <label for="departure_city">Départ (Ville) :</label>
                    <input type="text" class="form-control" name="departure_city" id="departure_city" value="<?= $_SESSION['old']['departure_city'] ?? '' ?>" required>
                    <?php if (!empty($_SESSION['errors']['departure_city'])): ?>
                        <div class="error-message"><?= $_SESSION['errors']['departure_city'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="p-2 flex-grow-1">
                    <label for="arrival_city">Arrivée (Ville) :</label>
                    <input type="text" class="form-control" name="arrival_city" id="arrival_city" value="<?= $_SESSION['old']['arrival_city'] ?? '' ?>" required>
                    <?php if (!empty($_SESSION['errors']['arrival_city'])): ?>
                        <div class="error-message"><?= $_SESSION['errors']['arrival_city'] ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="p-2 flex-grow-1">
                <label for="departure_address">Adresse de départ :</label>
                <input type="text" class="form-control" name="departure_address" id="departure_address" value="<?= $_SESSION['old']['departure_address'] ?? '' ?>" required>
                <?php if (!empty($_SESSION['errors']['departure_address'])): ?>
                    <div class="error-message"><?= $_SESSION['errors']['departure_address'] ?></div>
                <?php endif; ?>
            </div>

            <div class="p-2 flex-grow-1">
                <label for="arrival_address">Adresse d'arrivée :</label>
                <input type="text" class="form-control" name="arrival_address" id="arrival_address" value="<?= $_SESSION['old']['arrival_address'] ?? '' ?>" required>
                <?php if (!empty($_SESSION['errors']['arrival_address'])): ?>
                    <div class="error-message"><?= $_SESSION['errors']['arrival_address'] ?></div>
                <?php endif; ?>
            </div>

            <div class="d-flex flex-row flex-wrap">
                <div class="p-2 flex-grow-1">
                    <label for="vehicle">Véhicule :</label>
                    <select class="form-control" name="vehicle_id" id="vehicle_id" required>
                        <?php foreach ($vehicles as $v) :?>
                            <?php if (!empty($_SESSION['errors'])): ?>
                                <option <?= ($_SESSION['old']['vehicle_id']==$v['id']) ? 'selected="selected"' : '' ?> value="<?=$v['id']?>"><?=$v['brand']?> - <?=$v['model']?> [<?=$v['registration']?>]</option>
                            <?php else : ?>
                                <option value="<?=$v['id']?>"><?=$v['brand']?> - <?=$v['model']?> [<?=$v['registration']?>]</option>
                            <?php endif; ?>
                        <?php endforeach;?>
                    </select>
                    <?php if (!empty($_SESSION['errors']['vehicle_id'])): ?>
                        <div class="error-message"><?= $_SESSION['errors']['vehicle_id'] ?></div>
                    <?php endif; ?>
                    <div><a href="<?= BASE_URL ?>vehicles">Ajouter un nouveau véhicule.</a></div>

                </div>

                <div class="p-2">
                    <label for="price">Prix (en crédits) :</label>
                    <input type="number" class="form-control" min="1" max="99" name="price" id="price" placeholder="2" value="<?= $_SESSION['old']['price'] ?? '' ?>" required>
                    <?php if (!empty($_SESSION['errors']['price'])): ?>
                        <div class="error-message"><?= $_SESSION['errors']['price'] ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="description">Description (optionnel) :</label>
                <textarea maxlength="255" rows="4" placeholder="On ira par l'autoroute. Je peux vous déposer à la gare..." class="form-control" name="description" id="description"><?= $_SESSION['old']['description'] ?? '' ?></textarea>
                <?php if (!empty($_SESSION['errors']['description'])): ?>
                    <div class="error-message"><?= $_SESSION['errors']['description'] ?></div>
                <?php endif; ?>
            </div>

            <div class="p-2 mb-4"><button type="submit" class="btn btn-warning btn-block mb-4 " data-mdb-button-initialized="true">Créer</button></div>
        </form>
    </div>

</section>

<p>Retour à <a href="<?=BASE_URL?>dashboard">votre espace</a>.</p>