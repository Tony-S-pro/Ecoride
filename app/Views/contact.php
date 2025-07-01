<?php defined('ROOT_PATH') OR exit("You don't have permission to access this resource."); ?>
<h1 class="mb-5">L'équipe d'Ecoride répondera à toutes vos questions !</h1>

<section>
    <h2>Vous ne trouvez pas la réponse à votre question sur le site ?</h2>
    <p>Notre Service Relation Client est à votre disposition : 
        Remplissez le formulaire ci-dessous et nous vous répondrons dans un délai de 48 heures ouvrées.*</p>
    <p class="text-white-50">* Ce site a été créé dans le cadre d'un projet de formation. Ce n'est pas un véritable site de covoiturage, une réponse est peu probable.</p>

    <div class="w-100 p-4 d-flex justify-content-center pb-4">

        <form id="contactForm" action="<?= BASE_URL ?>contact/message" method="POST"  style="width: 22rem;" novalidate>
            
            <div class="mb-3">
                <label for="contact_name">Nom et Prenom / raison sociale :</label>
                <input type="text" class="form-control" name="contact_name" id="contact_name" value="<?= $_SESSION['old']['contact_name'] ?? '' ?>">
            </div>

            <div class="mb-3">      
                <label for="contact_email">Email :</label>
                <input type="email" class="form-control" name="contact_email" id="contact_email" value="<?= $_SESSION['old']['contact_email'] ?? ($_SESSION['user']['email'] ?? '') ?>" required>
                <?php if (!empty($_SESSION['errors']['contact_email'])): ?>
                    <div class="error-message"><?= $_SESSION['errors']['contact_email'] ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="contact_message">Message :</label>
                <textarea class="form-control" id="contact_message" name="contact_message" rows="5" maxlength="280"><?= $_SESSION['old']['contact_message'] ?? '' ?></textarea>
            </div>

            <div class="mb-3">
                <div class="form-check d-flex">
                    <input name="checkSendMe" id="checkSendMe" class="form-check-input" type="checkbox" value="sendMe">
                    <label class="form-check-label ms-3" for="checkSendMe">Je souhaite recevoir une copie de ce message par email.</label>
                </div>            
            </div>
          
            <button type="submit" class="btn btn-warning btn-block mb-4 " data-mdb-button-initialized="true">Envoyer</button>
        </form>
    </div>

</section>