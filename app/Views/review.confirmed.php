<?php defined('ROOT_PATH') OR exit("You don't have permission to access this resource."); ?>

<p>Merci d'avoir confirmé la fin du covoiturage</p>

<?php if ($review['objection']==='1') : ?>
    <p>Un employé d'Ecoride vous contactera bientôt, concernant votre réclamation.</p>
<?php elseif($review['validated']==='0') : ?>
    <p>Merci d'avoir laissé un commentaire. Il sera visible dès qu'il aura été apprové.</p>
<?php endif; ?>

<p>Retour à <a href="<?=BASE_URL?>dashboard">votre espace</a>.</p>