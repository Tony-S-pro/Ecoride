<?php defined('ROOT_PATH') OR exit("You don't have permission to access this resource."); ?>

<h1 class="mb-3">Espace employé</h1>

<section class="mb-3">
    <h2>Validation des commentaires</h2>
    <p>Rappel. Les commentaires ne peuvent êtres validés que s'ils respectent ces règles :
    </br>– Ne pas enfreindre les dispositions législatives ou réglementaires en vigueur : message au contenu illicite, contraires à l’ordre public et aux bonnes mœurs, de nature publicitaire ou promotionnelle, à caractère raciste, homophobe ou xénophobe, contenant des propos injurieux, diffamatoires ou offensant, ou encore qui inciterait à la violence ou qui porterait atteinte à l’intégrité, au respect ou à la dignité de la personne humaine ou à sa sensibilité, à l’égalité entre les femmes et les hommes et à la protection des enfants et des adolescents.
    </br>– Ne pas fournir de données personnelles (nom, téléphone, email, etc)
    </br>– Ne pas impliquer des personnes par leur nom
    </br>– Ne pas contenir d’informations permettant l’identification de personnes</p>
    <p>Ne seront pas non plus retenus les messages contraires aux droits d’auteurs ou droits voisins, au droit applicable aux bases de données, au droit à l’image ou au droit au respect de la vie privée.</p>
    <p>Les trolls et les spams seront également exclus. Les trolls sont des messages agressifs et provocateurs. Les spams sont des envois répétitifs de liens ou de messages identiques ou très voisins.</p>
    <div class="border border-1 border-light">        
        <?php if (empty($comments)) :?>
            <p class="m-2 p-1">Il n'y a aucun commentaires à valider.</p>
        <?php else :?>
        <?php foreach ($comments as $c) :?>
            <div class="px-3 py-3 d-flex flex-column">
                <div class="d-flex">
                    <div><strong><?=$c['creation_date']?></strong><?=($c['rating']!==null) ? ' ('.$c['rating'].'/5)' : ''?></div>
                    <div class="d-flex flex-grow-1 justify-content-end gap-2">
                        <div><a class="btn btn-primary" href="<?=BASE_URL?>employee/validate_comment/<?=$c['id']?>" role="button">Valider</a></div>
                        <div><a class="btn btn-danger" href="<?=BASE_URL?>employee/reject_comment/<?=$c['id']?>" role="button">Rejeter</a></div>
                    </div>
                </div>
                <div class="mt-2"><?=$c['comment']?></div>
                <div class="m-2 p-1"><a href="#collapseCommentMailto<?=$c['id']?>" data-bs-toggle="collapse" data-bs-target="#collapseCommentMailto<?=$c['id']?>" aria-expanded="false" aria-controls="collapseCommentMailto<?=$c['id']?>">Contacter les intéressés</a></div>
                <div class="collapse" id="collapseCommentMailto<?=$c['id']?>">
                    <div class="card card-body d-flex flex-row gap-2 flex-wrap justify-content-around">
                        <div>conducteur : <a href="mailto:<?=$c['driver_email']?>"><?=$c['driver_pseudo']?></a></div>
                        <div>passager : <a href="mailto:<?=$c['passenger_email']?>"><?=$c['passenger_pseudo']?></a></div>              
                    </div>
                </div>                     
            </div><hr>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<section class="mb-3">
    <h2>Validation des objections</h2>
    <p>Soyez réactif. Tentez d'apporter une réponse en moins de 24h, mais répondez quel que soit le délai.</p>
    <p>Répondez et communiquez avec les utilisateurs toujours de manière positive</p>
    <div class="border border-1 border-danger">
        <?php if (empty($objections)) :?>
            <p class="m-2 p-1">Il n'y a aucune objections à valider.</p>
        <?php else :?>
        <?php foreach ($objections as $o) :?>
            <div class="px-3 py-3 d-flex flex-column">
                <div class="d-flex">
                    <div><strong><?=$o['creation_date']?></strong><?=($o['rating']!==null) ? ' ('.$o['rating'].'/5)' : ''?></div>
                    <div class="d-flex flex-grow-1 justify-content-end gap-2">
                        <div><a class="btn btn-primary" href="<?=BASE_URL?>employee/validate_objection/<?=$o['id']?>" role="button">Rembourser</a></div>
                        <div><a class="btn btn-danger" href="<?=BASE_URL?>employee/reject_objection/<?=$o['id']?>" role="button">Rejeter</a></div>
                    </div>
                </div>
                <div class="mt-2"><?=$o['comment']?></div>
                
                <div class="m-2 p-1"><a href="#collapseObjectionMailto<?=$o['id']?>" data-bs-toggle="collapse" data-bs-target="#collapseObjectionMailto<?=$o['id']?>" aria-expanded="false" aria-controls="collapseObjectionMailto">Contacter les intéressés</a></div>
                <div class="m-2 p-1"><a href="#collapseObjectionCarpool<?=$o['id']?>" data-bs-toggle="collapse" data-bs-target="#collapseObjectionCarpool<?=$o['id']?>" aria-expanded="false" aria-controls="collapseObjectionCarpool">Détails du covoiturage</a></div>
                <div class="collapse" id="collapseObjectionMailto<?=$o['id']?>">
                    <div class="card card-body d-flex flex-row gap-2 flex-wrap justify-content-around">
                        <div>conducteur : <a href="mailto:<?=$o['driver_email']?>"><?=$o['driver_pseudo']?></a></div>
                        <div>passager : <a href="mailto:<?=$o['passenger_email']?>"><?=$o['passenger_pseudo']?></a></div>              
                    </div>
                </div>
                <div class="collapse" id="collapseObjectionCarpool<?=$o['id']?>">
                    <div class="card card-body d-flex gap-1">
                        <div><?=$o['carpool']['departure_date']?>, <?=$o['carpool']['departure_time']?> (n°<?=$o['id']?>)</div>
                        <div>Départ : <?=$o['carpool']['departure_city']?>, <?=$o['carpool']['departure_adress'] ?? "Pas d'adresse."?></div>
                        <div>Arrivée : <?=$o['carpool']['arrival_city']?>, <?=$o['carpool']['arrival_adress'] ?? "Pas d'adresse."?></div>
                        <div>Durée (est.) : <?=$o['carpool']['travel_time']?></div>
                        <div>Description : <?=$o['carpool']['description'] ?? 'Pas de description.'?></div>
                    </div>
                </div>
            </div><hr>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>




