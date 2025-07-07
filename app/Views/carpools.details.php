<?php defined('ROOT_PATH') OR exit("You don't have permission to access this resource."); ?>

<section>
    
    <h2>L'essentiel</h2>
    <div class="ps-3 pb-3">
        <p>Le <strong><?=$carpool_data['departure_date']?></strong>, à <strong><?=$carpool_data['departure_time']?></strong></p>       
        <p><strong>Départ : </strong></br>
            <?=$carpool_data['departure_city']?>,</br>
            <?=$carpool_data['departure_address']?></p>
        <p><strong>Arrivée : </strong></br>
            <?=$carpool_data['arrival_city']?>,</br>
            <?=$carpool_data['arrival_address']?></p>
        <p><strong>durée du trajet (est.) : </strong><?=$carpool_data['travel_time']?>h</p>
        <p><strong>sièges disponibles : </strong><?=$carpool_data['remaining_seats']?></p>                          
        <p><strong>description : </strong></br> 
            <?=$carpool_data['description']?></p>
        <a href="<?=BASE_URL?>carpools/booking/<?=$carpool_data['id']?>"><button type="button" class="btn btn-warning btn-sm" onclick="return confirm('Réservez votre place pour <?=$carpool_data['price']?> Crédit(s) ?')">Réserver</button></a>     
    </div>

    <h2>Le vehicule</h2>
    <div class="ps-3 pb-3">
        <p><?=$carpool_data['brand']?> - <?=$carpool_data['model']?> (<?=$carpool_data['registration_date']?>, <strong><?=$carpool_data['fuel']?></strong>)</p> 
    </div>

    <h2>Les préférences du conducteur</h2>
    <div class="ps-3 pb-3">
        <p>La présence d'animaux <strong><?=$carpool_data['animals'] ? "est" : "n'est pas"?></strong> acceptée.</p> 
        <p>Fumer <strong><?=$carpool_data['smoking'] ? "est" : "n'est pas"?></strong> permit dans le véhicule.</p> 
        <p><strong>Autres préférences : </strong></br> 
            <?=$carpool_data['misc']?></p>
    </div>

    <h2>Note et avis </h2>
    <div class="ps-3 pb-3">
        <p><strong>note chauffeur :</strong> <?=$carpool_data['avg_rating']?>/5 (<?=$carpool_data['ratings_nbr']?> avis)</p>

        <div class="overflow-y-auto mt-3 pb-5" style="max-width: max-w-sm; max-height: 300px;">
            <?php if (!empty($comments)) :?>
                <?php foreach($comments as $c) : ?>
                    <p>Le : <?=date('d/m/y', (int)$c['creation_date'])?>, note : <?=$c['rating']?>/5
                    <?=($c['comment'] !=null) ? ' </br>'.$c['comment'] : ''?></p>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</section>