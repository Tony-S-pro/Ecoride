<?php defined('ROOT_PATH') OR exit("You don't have permission to access this resource."); ?>

<script defer src="https://cdn.jsdelivr.net/npm/chart.js@4.5.0/dist/chart.umd.min.js"></script>
<script defer src="<?=BASE_URL?>assets/ajax/get-users.js"></script>
<script defer src="<?=BASE_URL?>assets/ajax/get-charts.js"></script>

<h1>Espace Admin</h1>

<section class="m-2 p-3">
    <h2>Statistiques du site</h2>
    <canvas id="adminCharts" style="width:100%;max-width:600px;max-height:400px"></canvas>
    <p>Nombre total de crédit gagné par la plateforme : <?=$credits_total ?: 'ERREUR' ?></p>
</section>

<section class="m-2 p-3">
    <h2>Gestion des comptes Ecoride</h2>
    <p class="d-inline-flex gap-1">
        <a class="btn btn-outline-secondary" data-bs-toggle="collapse" href="#vehicles" role="button" aria-expanded="false" aria-controls="Vehicles">Suspendre un compte</a>
        <button class="btn btn-outline-success" type="button" data-bs-toggle="collapse" data-bs-target="#new_vehicle" aria-expanded="false" aria-controls="new_vehicle">Nouveau Véhicule</button>
    </p>
    <div class="col">
        <div class="row">
            <div class="collapse multi-collapse overflow-y-auto" id="vehicles">
                <div class="card card-body border-secondary" id="vehicles">

                    <div class="search" >
                        <form action="admin/users" method="post" id="searchFormUsers">
                            <div class="d-flex gap-3 flex-wrap">
                                <div class="d-flex flex-row flex-wrap gap-2 m-1 justify-content-between">
                                    <div><input type="text" name="search_id" placeholder="id" class="form-control me-1"></div>
                                    <div><input type="text" name="search_pseudo" placeholder="Pseudo" class="form-control me-1"></div>
                                    <div><input type="text" name="search_email" placeholder="email" class="form-control me-1"></div>
                                    <div>
                                        <select class="form-control" name="search_role" id="search_role">
                                            <option selected="selected" value="user">utilisateur</option>
                                            <option value="employee">employé</option>
                                        </select>
                                    </div>
                                </div>                            
                                <div class="d-flex  m-1 justify-content-space-between">
                                    <button type="submit" name="submit" class="btn btn-outline-warning me-1">Rechercher</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="m-2 p-3">
                        <div class="results" id="results-users">
                        </div>
                    </div>
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

<?php dump($_SESSION);?>




