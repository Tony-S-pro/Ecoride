<?php
namespace App\Controllers;

use App\Core\Controller;

class DashboardController extends Controller
{
    public function index(): void
    {
        // check if user's connected
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'signup');
            exit;
        }

        $data = [
            'title' => "Votre dashboard",
            'view' => "dashboard"
        ];        

        Controller::render($data['view'], $data);
    }
}