<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\MailHelper;

class ContactController extends Controller
{
    public function index(): void
    {
        $data = [
            'title' => "Contactez l'equipe d'Ecoride",
            'view' => "contact"
        ];        

        Controller::render($data['view'], $data);
    }

    public function message()
    {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $errors = [];
            $contact_name = trim($_POST['contact_name'] ?? '');
            $contact_email = trim($_POST['contact_email'] ?? '');
            $contact_message = trim($_POST['contact_message'] ?? '');
            $contact_sendMe = $_POST['checkSendMe'] ?? '';
            
            // Validate data received (back-end)
            if (empty($contact_email)) {
                $errors['contact_email'] = "Email requis.";
            } elseif (!filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
                $errors['contact_email'] = "Adresse mail non valide.";
            }

            // In case of error -> back to signup view w/ messages
            if (!empty($errors)) {
                // Store errors + values entered in session
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $_POST;

                header('Location: '.BASE_URL.'contact');
                exit;
            }

            // php mailer via MailHelper
            $mail = MailHelper::sendContactMail($contact_email, $contact_name, $contact_message, $contact_sendMe);

            if($mail) {
                // Redirect to thankyou page
                header('Location: '.BASE_URL.'contact/thanks');
                exit;
            }
            exit;
        }
    }

    public function thanks() 
    {
        $data = [
            'title' => "Contactez l'equipe d'Ecoride",
            'view' => "contact.thanks"
        ];        

        Controller::render($data['view'], $data);
    }
}