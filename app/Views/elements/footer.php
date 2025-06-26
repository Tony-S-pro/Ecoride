<div class="container justify-content-center my-1 d-flex align-text-center">
    <span class="fw-light fs-6 text-body-secondary">&copy; <?= date("Y"); ?> EcoRide</span>
</div>

<footer class="mt-auto footer">
    
    <nav class="navbar navbar-expand-md bg-success border border-top border-black">
        <div class ="container justify-content-center">
            <ul class="d-flex flex-row justify-content-evenly navbar-nav align-text-center"> 
                <li class="nav-item me-1"><a class="nav-link" href="mailto:<?=APP_MAIL?>">Contactez-nous</a></li>
                <li class="nav-item"><span class="nav-link disabled"> | </span></li>
                <li class="nav-item ms-1"><a class="nav-link <?=($view==='mentions') ? "active":""?>" href="<?=BASE_URL?>mentions">Mentions légales</a></li> 
            </ul>
        </div>
    </nav>

</footer>
