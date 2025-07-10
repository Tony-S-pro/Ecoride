<?php defined('ROOT_PATH') OR exit("You don't have permission to access this resource."); ?>
<script defer src="<?=BASE_URL?>assets/ajax/get-carpools-passenger.js"></script>
<script defer src="<?=BASE_URL?>assets/ajax/get-carpools-driver.js"></script>

<h1>Votre espace</h1>

<section>
    <h2>Bienvenue <?= htmlspecialchars($_SESSION['user']['pseudo']) ?></h2>
    <p>Dans cet espace, vous pouvez consulter vos covoiturages passés et a venir.</p>
    <p>Vous pouvez aussi choisir devenir un conducteur en enregistrant un véhicule et vos préférences pour organiser vos propre covoiturages.</p>
</section>

<section>
    <h2>Vos covoiturages en tant que passager</h2>
    <p>Vous trouverez ci-dessous la liste de tous les covoiturages auquels vous avez prévu de participer, ainsi que l'historique de vos trajets.</p>
    <p>N'oubliez pas de donner votre avis après chaque covoiturages.</p>    

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

<?php if(empty($_SESSION['user']['vehicles'])):?>

<section>
    <h2>Devenez un chauffeur sur Ecoride</h2>
    <p>Enregistrez un véhicule pour organiser vos covoiturages en tant que conducteur.</p>
    <p class="d-inline-flex gap-1"><a class="btn btn-warning" href="<?=BASE_URL?>vehicles" role="button">Enregistrer un véhicule</a></p> 
</section>

<?php else:?>

<section>
    <h2>Vos covoiturages en tant que conducteur</h2>
    <p>En tant que conducteur, vous pouvez créer vos propre covoiturages en décidant vous-même de tous les détails*.</p>
    <p class="text-white-50">*Notez que la création de chaque covoiturage vous coutera 2 crédits pour frais de gestion.</p>
    <p class="d-inline-flex gap-1"><a class="btn btn-success" href="<?=BASE_URL?>vehicles" role="button">Vos vehicules</a></p>
    <p class="d-inline-flex gap-1"><a class="btn btn-warning" href="<?=BASE_URL?>carpooling" role="button">Créer un covoiturage</a></p>
    
    <p>Voici la liste de tous les covoiturages que vous avez organisé en tant que conducteur. N'oubliez pas de signaler le départ et l'arrivé de tout trajet.</p>
    
    <p class="d-inline-flex gap-1">
    <a class="btn btn-outline-secondary" data-bs-toggle="collapse" href="#driverPast" role="button" aria-expanded="false" aria-controls="driverPast">Passés</a>
    <button class="btn btn-outline-success" type="button" data-bs-toggle="collapse" data-bs-target="#driverPlanned" aria-expanded="false" aria-controls="driverPlanned">A venir</button>
    
    </p>
    <div class="col">
        <div class="row">
            <div class="collapse multi-collapse overflow-y-auto" class="max-width: max-w-sm; max-height: 300px;" id="driverPast">
                <div class="card card-body border-secondary mb-2" id="driverPastResults">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="collapse multi-collapse overflow-y-auto" class="max-width: max-w-sm; max-height: 300px;" id="driverPlanned">
                <div class="card card-body border-success mb-2" id="driverPlannedResults">
                </div>
            </div>
        </div>
    </div>
</section>

<?php endif;?>