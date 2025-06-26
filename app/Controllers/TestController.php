<?php
namespace App\Controllers;

use App\Core\Controller;

class TestController extends Controller
{
    public function index(): void
    {
        $data = [
            'title' => "Test title"
        ];        
        echo 'test';
        Controller::render('test', $data);
    }
}