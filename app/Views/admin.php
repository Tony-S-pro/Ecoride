<?php defined('ROOT_PATH') OR exit("You don't have permission to access this resource."); ?>

<script defer src="https://cdn.jsdelivr.net/npm/chart.js@4.5.0/dist/chart.umd.min.js"></script>
<script defer src="<?=BASE_URL?>assets/ajax/get-charts.js"></script>

<h1>Espace Admin</h1>

<section class="m-2 p-3">
    <h2>Statistiques du site</h2>
    <canvas id="adminCharts" style="width:100%;max-width:600px;max-height:400px"></canvas>
    <p>Nombre total de crédit gagné par la plateforme : <?=$credits_total ?: 'ERREUR' ?></p>
</section>

<?php dump($_SESSION);?>




