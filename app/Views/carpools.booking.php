<?php defined('ROOT_PATH') OR exit("You don't have permission to access this resource."); ?>

<section>    
    <?php if(!empty($booking)) : ?>
        <?php if($booking === 'valid') : ?>
            <h2>Votre place est réservée</h2>
            <p>Vous pouvez retrouvez les informations concernant ce covoiturage dans <a href="<?=BASE_URL?>dashboard">votre espace</a>.</p>
            <p>Vous avez maintenant <?=$stats['credit']?> C sur votre compte.</p>
        <?php elseif($booking === 'yours') : ?>
            <h2>C'est votre covoiturage !</h2>
            <p>Vous essayez de réserver une place sur votre propre covoiturage.</p>
        <?php elseif($booking === 'no_credits') : ?>
            <h2>Vous n'avez pas assez de crédits !</h2>
            <p>Vous n'avez pas suffisamment de crédits sur votre compte pour réserver une place sur ce covoiturage.</p>
            <p>Vous avez <?=$stats['credit']?> C ; ce covoiture requiert <?=$stats['price']?> C.</p>
        <?php elseif($booking === 'no_seats') : ?>
            <h2>Il n'y a plus de place !</h2>
            <p>Il n'y a plus de place disponible sur ce covoiturage.</p>
        <?php else : ?>
            <h2>Impossible de faire la réservation.</h2>
            <p>La réservation n'a pas pu se faire. Veuillez réessayer plus tard.</p>
        <?php endif ; ?>
    <?php endif; ?>
    <p>Retour vers la <a href="<?=BASE_URL?>carpools">page des covoiturages</a>.</p>
</section>
