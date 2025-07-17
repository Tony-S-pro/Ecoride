<?php defined('ROOT_PATH') OR exit("You don't have permission to access this resource."); ?>
<script defer src="<?=BASE_URL?>assets/ajax/get-carpools-lite.js"></script>
<h1>Home title</h1>

<section>
    <h2>Section title</h2>
    <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Quaerat esse repellat doloremque reprehenderit fuga exercitationem, nostrum ea iure autem? Dicta quia aut et mollitia quo corrupti cupiditate molestiae debitis perspiciatis.</p>
    <img src="<?=BASE_URL?>assets/img/img-test-d.avif" alt="" srcset="">
</section>

<section class="p-4 d-flex flex-column align-items-center pb-4">

    <h2>Recherche par ville de départ, arrivée et date</h2>

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

