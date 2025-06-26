<?php
namespace App\Controllers;

use App\Core\Controller;

class RandomController extends Controller
{
    public function index(): void
    {
        $data = [
            'title' => "Random title",
            'view' => "random"
        ];        

        Controller::render($data['view'], $data);
    }
}