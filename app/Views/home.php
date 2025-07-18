<?php defined('ROOT_PATH') OR exit("You don't have permission to access this resource."); ?>
<script defer src="<?=BASE_URL?>assets/ajax/get-carpools-lite.js"></script>
<h1 class="mb-3 text-center">Ecoride, la plateforme de covoiturage écologique !</h1>

<section class="mb-3">
    <div class="d-flex row row-cols-1 row-cols-sm-2">
        <div class="col">
            <img class ="img-fluid rounded-1" src="<?=BASE_URL?>assets/img/img_home_01.jpg" alt="photo d'une voiture sur la route dans une forêt" srcset="">
        </div>
        <div class="col align-content-center">
            <h2 class="mb-2">La communauté Ecoride</h2>
            <p>Nous sommes ravis de vous accueillir parmi nous pour partager des trajets tout en réduisant notre empreinte carbone. Ensemble, faisons un geste pour la planète en optimisant nos déplacements et en favorisant les rencontres responsables.</p>
            <p>Rejoignez une communauté engagée pour un avenir plus vert et plus solidaire.</p>
            <p>Bon voyage !</p>
        </div>
            
    </div>
</section>

<section class="p-4 d-flex flex-column align-items-center mb-3">

    <h2>Recherchez un covoiturage</h2>

    <div class="search col-md-4" >
        <form action="home" method="post" id="searchForm-lite">
            <div class="d-flex m-1 justify-content-between col-12">
                <input type="text" name="search_city1" placeholder="Vile Départ" class="form-control me-1">
                <input type="text" name="search_city2" placeholder="Ville Arrivée" class="form-control me-1">
            </div>
            <div class="d-flex m-1 justify-content-space-between col-12">
                <input type="date" name="search_date" class="form-control me-1" data-date-format="yyyy-mm-dd">
                <button type="submit" name="submit" class="btn btn-outline-warning me-1 col 2">Rechercher</button>
            </div>
        </form>
    </div>
    
    <div class="results" id="results-carpool-lite">
    </div>
    
</section>

<section>
    <h2 class="mb-2 text-center">Pratiquer le covoiturage</h2>
    <div class="d-flex row row-cols-1 row-cols-sm-2 row-cols-md-3">        
        <div class="col d-flex flex-column">
            <img class ="img-fluid rounded-1" src="<?=BASE_URL?>assets/img/img_home_02.jpg" alt="photo d'une plante qui pousse d'une pile de pièce de monaie" srcset="">
            <p class="p-2">Le covoiturage est une solution économique pour les trajets quotidiens ou occasionnels. En partageant les frais de carburant et de péage, les passagers et les conducteurs réalisent des économies significatives. C'est une alternative intelligente pour réduire les coûts de transport tout en profitant d'une compagnie agréable.</p>
        </div>
        <div class="col d-flex flex-column">
            <img class ="img-fluid rounded-1" src="<?=BASE_URL?>assets/img/img_home_03.jpg" alt="photo d'une femme assise sur la toit d'une voiture regardant les montagnes" srcset="">
            <p class="p-2">Sur le plan écologique, le covoiturage contribue à la réduction des émissions de CO2. En diminuant le nombre de voitures sur les routes, il participe activement à la lutte contre la pollution et à la protection de l'environnement. C'est un geste simple et efficace pour un avenir plus durable.</p>
        </div>
        <div class="col d-flex flex-column">
            <img class ="img-fluid rounded-1" src="<?=BASE_URL?>assets/img/img_home_04.jpg" alt="photo d'un groupe de personnes joyeuses dans une voiture" srcset="">
            <p class="p-2">Enfin, le covoiturage favorise les rencontres et les échanges. Il permet de créer des liens sociaux et de rendre les trajets plus conviviaux. Que ce soit pour des trajets courts ou longs, le covoiturage offre une expérience de voyage enrichissante et solidaire.</p>
        </div>
            
    </div>
</section>
