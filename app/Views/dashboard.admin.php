<?php defined('ROOT_PATH') OR exit("You don't have permission to access this resource."); ?>

<script defer src="https://cdn.jsdelivr.net/npm/chart.js@4.5.0/dist/chart.umd.min.js"></script>
<script defer src="<?=BASE_URL?>assets/ajax/get-charts.js"></script>

<h1>Votre espace</h1>

<section>
    <h2>Bienvenue <?= htmlspecialchars($_SESSION['user']['pseudo']) ?></h2>
    <p>Dans cet espace, vous pouvez consulter vos coivoiturages passés et a venir.</p>
    <p>Vous pouvez aussi choisir devenir un conducteur en enregistrant un véhicule et vos préférences pour organiser vos propre covoiturages.</p>
</section>


<canvas id="adminChart-carpools" style="width:100%;max-width:600px;max-height:400px"></canvas>

