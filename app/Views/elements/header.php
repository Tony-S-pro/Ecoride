<header>
        <nav class="navbar bg-success navbar-expand-md border-bottom border-body" data-bs-theme="dark">
            <div class ="container-fluid">
                <a class="navbar-brand ms-3" href="<?=BASE_URL?>">
                    <img src="<?=BASE_URL?>assets/img/ecoride-logo-white-64p.svg" alt="Logo" width="32" height="32" class="d-inline-block align-text-center">
                    Ecoride
                </a>
                <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button> 
                <div class="navbar-collapse collapse" id="navbarMenu" style=""> 
                    <ul class="navbar-nav me-auto ms-5 mb-2 mb-md-0"> 
                        <li class="nav-item"><a class="nav-link <?=($view==='home') ? "active":""?>" aria-current="page" href="<?=BASE_URL?>">Acceuil</a></li> 
                        <li class="nav-item"><a class="nav-link <?=($view==='carpools') ? "active":""?>" href="<?=BASE_URL?>carpools">Covoiturages</a></li> 
                        <li class="nav-item"><a class="nav-link <?=($view==='contact') ? "active":""?>" href="<?=BASE_URL?>contact">Contact</a></li> 
                    </ul>
                    <div class="text-end">

                        <?php if (isset($_SESSION['user'])): ?>
                        <a href="<?=BASE_URL?>dashboard" role="button">
                            <button type="button" class="btn btn-light me-2">Dashboard</button>
                        </a>
                        <a href="<?=BASE_URL?>logout" role="button">
                            <button type="button" class="btn btn-warning">Déconnexion</button>
                        </a>
                        <?php else: ?>
                        <a href="<?=BASE_URL?>signup" role="button">
                            <button type="button" class="btn btn-light me-2">Inscription</button>
                        </a>
                        <a href="<?=BASE_URL?>login" role="button">
                            <button type="button" class="btn btn-warning">Connexion</button>
                        </a>
                        <?php endif; ?> 
                    </div>
                </div>
            </div>
        </nav>
    </header>
    