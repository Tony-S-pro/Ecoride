<?php defined('ROOT_PATH') OR exit("You don't have permission to access this resource."); ?>
<h1>Votre espace</h1>

<section>
    <h2>Bienvenue <?= htmlspecialchars($_SESSION['user']['pseudo']) ?></h2>
    <p>Dans cet espace, vous pouvez consulter vos coivoiturages passés et a venir.</p>
    <p>Vous pouvez aussi choisir devenir un conducteur en enregistrant un véhicule et vos préférences pour organiser vos propre covoiturages.</p>
</section>

<?=var_dump($_SESSION, $_SESSION['user']['role']);?>