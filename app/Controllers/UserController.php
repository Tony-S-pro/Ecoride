<?php
namespace App\Controllers;

use App\Core\Controller;

class UserController extends Controller
{
    public function index(): void
    {
        if(isset($_POST)) {
            $post=$_POST;
        };
        
        $data = [
            'title' => "Test title",
            'view' => "test",
            'post' => $post
        ];        
        Controller::render($data['view'], $data);
    }

    public function driver(): void
    {
        if(isset($_POST)) {
            $post=$_POST;
        };
        
        $data = [
            'title' => "Test title",
            'view' => "test",
            'post' => $post
        ];        
        Controller::render($data['view'], $data);
    }
}