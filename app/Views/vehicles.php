<?php defined('ROOT_PATH') OR exit("You don't have permission to access this resource."); ?>

<h1>Véhicules et préférences</h1>
<p>Vous pouvez, sur cette page, associer ou supprimer des vehicules à votre compte. Vous devez fournir des détails spécifiques sur le modèle de la voiture et vos préférences pour le trajet.</p>
<p>Vous pouvez également fournir un portrait de vous-même que vos future passagers pourront voir lorsqu'ils réservent un covoiturage</p>
<p>Retour à <a href="<?=BASE_URL?>dashboard">votre espace</a>.</p>

<section>    
    <h2>Vos véhicules</h2>
    <p class="d-inline-flex gap-1">
        <a class="btn btn-outline-secondary" data-bs-toggle="collapse" href="#vehicles" role="button" aria-expanded="false" aria-controls="Véhicles">Mes véhicules</a>
        <button class="btn btn-outline-success" type="button" data-bs-toggle="collapse" data-bs-target="#new_vehicle" aria-expanded="false" aria-controls="new_vehicle">Nouveau Véhicule</button>
    </p>
    <div class="col">
        <div class="row">
            <div class="collapse multi-collapse overflow-y-auto" id="vehicles">
                <div class="card card-body border-secondary" id="vehicles">
                    <?php if (empty($vehicles)) :?>
                        <p>Vous n'avez aucun vehicule enregistré.</p>
                    <?php else :?>
                    <?php foreach ($vehicles as $v) :?>
                    <div class="px-3 py-2">
                        <strong><?=$v['brand']?></strong>, <?=$v['model']?> (<?=$v['registration_date']?>), <strong><?=($v['fuel']==='electrique') ? 'électrique' : 'thermique' ?></strong></br>
                        <?=$v['registration']?>, <?=empty($v['color']) ? '' : $v['color'] ?></br>
                        <?=$v['seats']?> places disponibles</br>
                        <ul>
                            <li><?=($v['smoking']==0) ? 'non ' : '' ?>fumeur</li>
                            <li>les animaux <?=($v['animals']==0) ? 'ne sont pas' : 'sont' ?> autorisés</li>
                            <?=(empty($v['misc'])) ? '' : '<li>autre(s) : '.$v['misc'].'</li>' ?>
                        </ul>
                        <p><a href="<?= BASE_URL ?>vehicles/delete_vehicle/<?=$v['id']?>" onclick="return confirm('Voulez-vous supprimer ce véhicule ?')">Supprimer ce vehicule</a>.</p>                    
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="collapse multi-collapse overflow-y-auto" id="new_vehicle">
                <div class="card card-body border-success mb-2" id="new_vehicle">
                    <form id="registerVehicle" action="<?= BASE_URL ?>vehicles/register_vehicle" method="POST" novalidate>
                        <div class="d-flex flex-row flex-wrap">
                        <div class="mb-3 flex-grow-1">
                            <label for="brand">Marque :</label>
                            <input type="text" class="form-control" name="brand" id="brand" value="<?= $_SESSION['old']['brand'] ?? '' ?>" required>
                            <?php if (!empty($_SESSION['errors']['brand'])): ?>
                                <div class="error-message"><?= $_SESSION['errors']['brand'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3 flex-grow-1">
                            <label for="model">Modèle :</label>
                            <input type="text" class="form-control" name="model" id="model" value="<?= $_SESSION['old']['model'] ?? '' ?>" required>
                            <?php if (!empty($_SESSION['errors']['model'])): ?>
                                <div class="error-message"><?= $_SESSION['errors']['model'] ?></div>
                            <?php endif; ?>
                        </div>
                        </div>

                        <div class="mb-3">
                            <label for="fuel">Energie :</label>
                            <select class="form-control" name="fuel" id="fuel" value="<?= $_SESSION['old']['fuel'] ?? '' ?>" required>
                                <option value="essence">essence</option>
                                <option value="diesel">diesel</option>
                                <option value="electrique">électrique</option>
                                <option value="hybride">hybride</option>
                                <option value="autre">autre</option>
                            </select>
                            <?php if (!empty($_SESSION['errors']['fuel'])): ?>
                                <div class="error-message"><?= $_SESSION['errors']['fuel'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="registration_date">Année (1ère immatriculation) :</label>
                            <input type="number" class="form-control" min="1900" max="2099" name="registration_date" id="registration_date" value="<?= $_SESSION['old']['registration_date'] ?? '' ?>" required>
                            <?php if (!empty($_SESSION['errors']['registration_date'])): ?>
                                <div class="error-message"><?= $_SESSION['errors']['registration_date'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="registration">Immatriculation :</label>
                            <input type="text" class="form-control" name="registration" id="registration" value="<?= $_SESSION['old']['registration'] ?? '' ?>" required>
                            <?php if (!empty($_SESSION['errors']['registration'])): ?>
                                <div class="error-message"><?= $_SESSION['errors']['registration'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="color">Couleur :</label>
                            <input type="text" class="form-control" name="color" id="color" value="<?= $_SESSION['old']['color'] ?? '' ?>" required>
                            <?php if (!empty($_SESSION['errors']['color'])): ?>
                                <div class="error-message"><?= $_SESSION['errors']['color'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="seats">Places disponibles (4 par défaut) :</label>
                            <input type="number" class="form-control" min="1" max="9" name="seats" id="seats" value="<?= $_SESSION['old']['seats'] ?? '' ?>">
                            <?php if (!empty($_SESSION['errors']['seats'])): ?>
                                <div class="error-message"><?= $_SESSION['errors']['seats'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <p>Autorisez-vous : </p>
                            <div class="form-check">
                                <input name="smoking" id="smoking" class="form-check-input" type="checkbox" value="accept">
                                <label class="form-check-label" for="smoking">Fumer dans le véhicule</label>
                            </div>
                            <div id="smoking-error" name="smoking-error" class="error-message"></div>
                            <div class="form-check">
                                <input name="animals" id="animals" class="form-check-input" type="checkbox" value="accept">
                                <label class="form-check-label" for="animals">La présence d'animaux</label>
                            </div>
                            <div id="animals-error" name="animals-error" class="error-message"></div>
                        </div>

                        <div class="mb-3">
                            <label for="misc">Autres préférences (optionnel) :</label>
                            <textarea maxlength="255" rows="4" placeholder="J'aime écouter la radio..." class="form-control" name="misc" id="misc" value="<?= $_SESSION['old']['misc'] ?? '' ?>"></textarea>
                            <?php if (!empty($_SESSION['errors']['misc'])): ?>
                                <div class="error-message"><?= $_SESSION['errors']['misc'] ?></div>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="btn btn-warning btn-block mb-4" data-mdb-button-initialized="true">Enregistrer</button>
                    </form>                
                </div>
            </div>
        </div>
    </div>
</section>

<section>
    <h2>Votre portrait</h2>
    <p>Vous pouvez permettre aux utilisateur d'Ecoride de vous reconnaitre en fournissant un portrait au format png, jpg/jpeg ou webp (10Mb max).</p>
    <div class="container">
        <form class="" action="<?= BASE_URL ?>vehicles/upload" method="POST" enctype="multipart/form-data">
            <label for="file">Votre photo</label>
            <input type="file" name="file" class="form-control p-2 m-2">
            <button type="submit" class="btn btn btn-outline-warning btn-block mb-4" data-mdb-button-initialized="true">Enregistrer</button>
        </form>
        <?php if (!empty($_SESSION['errors']['upload'])): ?>
            <div class="error-message"><?= $_SESSION['errors']['upload'] ?></div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['msg']['upload'])): ?>
            <div class="success-message"><?= $_SESSION['msg']['upload'] ?></div>
        <?php endif; ?>
    </div>    

</section>
    
