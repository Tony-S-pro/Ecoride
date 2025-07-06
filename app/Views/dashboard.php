<?php defined('ROOT_PATH') OR exit("You don't have permission to access this resource."); ?>
<script defer src="<?=BASE_URL?>assets/ajax/get-carpools-passenger.js"></script>

<h1>Votre espace</h1>

<section>
    <h2>Bienvenue <?= htmlspecialchars($_SESSION['user']['pseudo']) ?></h2>
    <p>Dans cet espace, vous pouvez consulter vos coivoiturages passés et a venir.</p>
    <p>Vous pouvez aussi choisir devenir un conducteur en enregistrant un véhicule et vos préférences pour organiser vos propre covoiturages.</p>
</section>

<?=var_dump($_SESSION, $_SESSION['user']['role']);?>

<section>
    <h2>Vos covoiturages</h2>
    

    <p class="d-inline-flex gap-1">
    <a class="btn btn-outline-secondary" data-bs-toggle="collapse" href="#carpoolsPast" role="button" aria-expanded="false" aria-controls="carpoolsPast">Passés</a>
    <button class="btn btn-outline-success" type="button" data-bs-toggle="collapse" data-bs-target="#carpoolsPlanned" aria-expanded="false" aria-controls="carpoolsPlanned">A venir</button>
    </p>
    <div class="col">
    <div class="row">
        <div class="collapse multi-collapse overflow-y-auto" class="max-width: max-w-sm; max-height: 300px;" id="carpoolsPast">
            <div class="card card-body border-secondary mb-2" id="carpoolsPastResults">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="collapse multi-collapse overflow-y-auto" class="max-width: max-w-sm; max-height: 300px;" id="carpoolsPlanned">
            <div class="card card-body border-success mb-2" id="carpoolsPlannedResults">
            </div>
        </div>
    </div>
    </div>


</section>