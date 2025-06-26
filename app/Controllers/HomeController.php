<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        $data = [
            'title' => "Home title",
            'view' => "home"
        ];        

        Controller::render($data['view'], $data);
    }
}