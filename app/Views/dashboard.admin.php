<?php defined('ROOT_PATH') OR exit("You don't have permission to access this resource."); ?>

<script defer src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"></script>
<script defer src="<?=BASE_URL?>assets/ajax/get-charts.js"></script>

<h1>Votre espace</h1>

<section>
    <h2>Bienvenue <?= htmlspecialchars($_SESSION['user']['pseudo']) ?></h2>
    <p>Dans cet espace, vous pouvez consulter vos coivoiturages passés et a venir.</p>
    <p>Vous pouvez aussi choisir devenir un conducteur en enregistrant un véhicule et vos préférences pour organiser vos propre covoiturages.</p>
</section>


<canvas id="adminChart" style="width:100%;max-width:700px"></canvas>

