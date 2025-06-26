<?php
namespace App\Controllers;

use App\Core\Controller;

class TestController extends Controller
{
    public function index(): void
    {
        $data = [
            'title' => "Test title",
            'view' => "test"
        ];        
        Controller::render($data['view'], $data);
    }
}