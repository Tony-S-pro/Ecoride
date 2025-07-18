<script defer src="<?=BASE_URL?>assets/ajax/get-carpools.js"></script>

<div class="d-flex justify-content-center">
    <h1>Covoiturages :</h1>
</div>

<section class="p-4 d-flex flex-column align-items-center">
<?php if(isset($_SESSION['user'])) : ?>
    <p>Vous disposez de : <strong><?=$credit?> Crédits</strong>.</p>
<?php endif ;?>
    
    <h2>Recherche par ville de départ et arrivée</h2>

    <div class="search col-md-4" >
        <form action="carpools" method="post" id="searchForm-full">
            <div class="d-flex justify-content-between col-12">
                <input type="text" name="search_city1" placeholder="Ville Départ" class="form-control m-1">
                <input type="text" name="search_city2" placeholder="Ville Arrivée" class="form-control m-1">
            </div>
            <div class="d-flex justify-content-space-between col-12">
                <input type="date" name="search_date" class="form-control m-1" data-date-format="yyyy-mm-dd">
                <button type="submit" name="submit" class="btn btn-outline-warning m-1 col 2">Rechercher</button>
            </div>

            <div class="m-2 p-1"><a href="#collapseSearch" data-bs-toggle="collapse" data-bs-target="#collapseSearch" aria-expanded="false" aria-controls="collapseSearch">Plus d'options de recherche</a></div>
            
            <div class="collapse" id="collapseSearch">
                <div class="card card-body">
                    <div><input type="text" name="search_address1" placeholder="Adresse départ" class="form-control m-1"></div>
                    <div><input type="text" name="search_address2" placeholder="Adresse arrivée" class="form-control m-1"></div>
                    <div>
                        <select class="form-select form-select-sm form-control m-1" name="minRating" aria-label="">
                            <option selected>Note min du chauffeur</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <div><input type="number" min="1" max="100" name="maxPrice" placeholder="Prix max (en Crédits)" class="form-control m-1"></div>
                    <div><input type="number" min="1" max="24" name="maxTime" placeholder="durée max (en heures)" class="form-control m-1"></div>
                    <div class="m-2">
                        <input type="checkbox" class="btn-check m-1" id="btn-check-outlined" autocomplete="off" id="checkEco" name="checkEco">
                        <label class="btn btn-outline-success" for="btn-check-outlined">Voyage Ecolo</label><br>
                    </div>
                </div>
            </div>

        </form>


   
    <div class="results" id="results-carpool">
    </div>
</section>