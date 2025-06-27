<script defer src="<?=BASE_URL?>assets/ajax/get-carpools.js"></script>

<div class="d-flex justify-content-center">
    <h1>Covoiturages :</h1>
</div>

<section class="p-4 d-flex flex-column align-items-center pb-4"
    
    <h2>Recherche par ville de départ et arrivée</h2>

    <div class="search col-md-4" >
        <form action="<?= BASE_URL?>carpools" method="post" id="searchForm">
            <div class="d-flex m-1 justify-content-between col-12">
                <input type="text" name="search_city1" placeholder="Vile Départ" class="form-control me-1">
                <input type="text" name="search_city2" placeholder="Ville Arrivée" class="form-control me-1">
            </div>
            <div class="d-flex m-1 justify-content-space-between col-12">
                <input type="date" name="search_date" id="" class="form-control me-1">
                <button type="submit" name="submit" class="btn btn-warning btn-block form-control me-1 col 2">Rechercher</button>
            </div>
        </form>
    </div>

    
    <div class="results" id="results-carpool">
    </div>
    
    <div class="modal" id="myModal" role="dialogue">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column">
                        <label for="">#ID covoiturage</label>
                        <input type="text" name="" id="">
                    </div>

                    <div class="d-flex flex-column">
                        <label for="">#ID conducteur</label>
                        <input type="text" name="" id="">
                    </div>

                    <div class="d-flex flex-column">
                        <label for="">Date création</label>
                        <input type="text" name="" id="">
                    </div>

                    <div class="d-flex flex-column">
                        <label for="">Ville Départ</label>
                        <input type="text" name="" id="">
                    </div>

                    <div class="d-flex flex-column">
                        <label for="">Date départ (YYYY-MM-JJ)</label>
                        <input type="text" name="" id="">
                    </div>

                    <div class="d-flex flex-column">
                        <label for="">Heure départ (hh:mm)</label>
                        <input type="text" name="" id="">
                    </div>

                    <div class="d-flex flex-column">
                        <label for="">Prix</label>
                        <input type="text" name="" id="">
                    </div>

                    <div class="d-flex flex-column">
                        <label for="">Note (pas dans la dbb)</label>
                        <select name="" id="">
                            <option value="0">indiférent</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    
</section>