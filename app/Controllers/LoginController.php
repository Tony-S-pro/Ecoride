<?php
namespace App\Controllers;

use App\Core\Controller;

class LoginController extends Controller
{
    public function index(): void
    {
        //check if user is connected
         if (isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'dashboard');
            exit;
        }

        $data = [
            'title' => "Connectez-vous à votre compte Ecoride",
            'view' => "login"
        ];        

        Controller::render($data['view'], $data);
    }
}