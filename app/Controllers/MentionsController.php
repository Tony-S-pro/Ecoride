<?php
namespace App\Controllers;

use App\Core\Controller;

class MentionsController extends Controller
{
    public function index(): void
    {
        $data = [
            'title' => "Mentions légales",
            'view' => "mentions"
        ];        

        Controller::render($data['view'], $data);
    }

    public function cookies(): void
    {
        $data = [
            'title' => "Politique de cookies",
            'view' => "mentions.cookies"
        ];        

        Controller::render($data['view'], $data);
    }
}