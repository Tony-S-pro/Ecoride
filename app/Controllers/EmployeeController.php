<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class EmployeeController extends Controller
{
    public function index(): void
    {
        // check if user's connected and has employee role/id        
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        }
        if(!$_SESSION['user']['role']==='employee' OR !in_array($_SESSION['user']['id'], EMPLOYEES_ID)) {
            header('Location: '.BASE_URL.'signup');
            exit;
        }

        $data = [
            'title' => "Dashboard employé",
            'view' => "employee"
        ];        

        Controller::render($data['view'], $data);
    }
}