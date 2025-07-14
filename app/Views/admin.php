<?php defined('ROOT_PATH') OR exit("You don't have permission to access this resource."); ?>

<script defer src="https://cdn.jsdelivr.net/npm/chart.js@4.5.0/dist/chart.umd.min.js"></script>
<script defer src="<?=BASE_URL?>assets/ajax/get-users.js"></script>
<script defer src="<?=BASE_URL?>assets/ajax/get-charts.js"></script>
<script defer src="<?=BASE_URL?>assets/ajax/get-logs.js"></script>

<h1>Espace Admin</h1>

<section class="m-2 p-3">
    <h2>Statistiques du site</h2>
    <canvas id="adminCharts" style="width:100%;max-width:600px;max-height:400px"></canvas>
    <p>Nombre total de crédit gagné par la plateforme : <?=$credits_total ?: 'ERREUR' ?></p>
</section>

<section class="m-2 p-3">
    <h2>Gestion des comptes Ecoride</h2>
    <p class="d-inline-flex gap-1">
        <a class="btn btn-outline-light" data-bs-toggle="collapse" href="#searchUsers" role="button" aria-expanded="false" aria-controls="searchUsers">Suspendre un compte</a>
        <button class="btn btn-outline-success" type="button" data-bs-toggle="collapse" data-bs-target="#new_employee" aria-expanded="false" aria-controls="new_employee">Créer compte employé</button>
    </p>
    <div class="col">
        <div class="row">
            <div class="collapse multi-collapse overflow-y-auto" id="searchUsers">
                <div class="card card-body border-light" id="searchUsers">

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
            <div class="collapse multi-collapse overflow-y-auto" id="new_employee">
                <div class="card card-body border-success mb-2" id="new_employee">
                    <div class="pb-4">
                        <form id="newEmployeeForm" action="<?= BASE_URL ?>admin/register" method="POST"  novalidate>
                            <?php \App\Core\Controller::set_csrf();  ?>
                            <div class="mb-3 col-sm-8">
                                <label for="emp_pseudo">Pseudo :</label>
                                <input type="text" class="form-control" name="emp_pseudo" id="emp_pseudo" value="<?= $_SESSION['old']['emp_pseudo'] ?? '' ?>" required>
                                <?php if (!empty($_SESSION['errors']['emp_pseudo'])): ?>
                                    <div class="error-message"><?= $_SESSION['errors']['emp_pseudo'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3 col-sm-8">      
                                <label for="emp_email">Email :</label>
                                <input type="email" class="form-control" name="emp_email" id="emp_email" value="<?= $_SESSION['old']['emp_email'] ?? '' ?>" required>
                                <?php if (!empty($_SESSION['errors']['emp_email'])): ?>
                                    <div class="error-message"><?= $_SESSION['errors']['emp_email'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3 col-sm-8">
                                <label for="emp_name">Nom :</label>
                                <input type="text" class="form-control" name="emp_name" id="emp_name" value="<?= $_SESSION['old']['emp_name'] ?? '' ?>" required>
                                <?php if (!empty($_SESSION['errors']['emp_name'])): ?>
                                    <div class="error-message"><?= $_SESSION['errors']['emp_name'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3 col-sm-8">
                                <label for="emp_firstname">Prénom :</label>
                                <input type="text" class="form-control" name="emp_firstname" id="emp_firstname" value="<?= $_SESSION['old']['emp_firstname'] ?? '' ?>" required>
                                <?php if (!empty($_SESSION['errors']['emp_firstname'])): ?>
                                    <div class="error-message"><?= $_SESSION['errors']['emp_firstname'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3 col-sm-8">
                                <label for="emp_password">Mot de passe :</label>
                                <input type="password" class="form-control" name="emp_password" id="emp_password" required>
                                <?php if (!empty($_SESSION['errors']['emp_password'])): ?>
                                    <div class="error-message"><?= $_SESSION['errors']['emp_password'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3 col-sm-8">
                                <label for="emp_confirm_password">Confirmer le mot de passe :</label>
                                <input type="password" class="form-control" name="emp_confirm_password" id="emp_confirm_password" required>
                                <?php if (!empty($_SESSION['errors']['emp_confirm'])): ?>
                                    <div class="error-message"><?= $_SESSION['errors']['emp_confirm_password'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div id="check-error" name="check-error" class="error-message"></div>
                            
                            <button type="submit" class="btn btn-warning btn-block mb-4" data-mdb-button-initialized="true">Envoyer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="m-2 p-3">
    <h2>Journal des objections passagers</h2>
    <p class="d-inline-flex gap-1">
    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#objections" aria-expanded="false" aria-controls="collapseExample">Objections</button>
    </p>
    <div class="collapse" id="objections">
    <div class="card card-body border-secondary">
        <div class="m-2 p-3">
            <div class="results" id="results-objections">
            </div>
        </div>
    </div>
    </div>

</section>






