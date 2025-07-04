<?php defined('ROOT_PATH') OR exit("You don't have permission to access this resource."); ?>

<section>
    <?php if(!empty($booking)) : ?>
        <?php if($booking = 'valid') : ?>
            <h2>Votre place est réservée</h2>
            <p>Bla bla on répète les infos</p>
        <?php elseif($booking = 'no_credits') : ?>
            <h2>Vous n'avez pas assez de crédits !</h2>
            <p>Vous n'avez pas suffisamment de crédits sur votre compte pour réserver une place sur ce covoiturage. Veuillez contacter l'assistance d'Ecoride.</p>
        <?php else : ?>
            <h2>Impossible de faire la réservation.</h2>
            <p>La réservation n'a pas pu se faire. Il n'y a peut être plus de place. Veuillez réessayer.</p>
        <?php endif ; ?>
    <?php endif; ?>
    <p>Retour vers la <a href="<?=BASE_URL?>carpools">page des covoiturages</a>.</p>

</section>