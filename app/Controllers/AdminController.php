<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Carpool;

class AdminController extends Controller
{
    public function index(): void
    {
        // check if user's connected and has admin role/id
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        }        
        if(!$_SESSION['user']['role']==='admin' OR !$_SESSION['user']['id']===ADMIN_ID) {
            header('Location: '.BASE_URL.'login');
            exit;
        }

        $data = [
            'title' => "Dashboard admin",
            'view' => "admin"
        ];        

        Controller::render($data['view'], $data);
    }

    public function chart(): void
    {
        // check if user's connected and has admin role/id        
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        }
        if(!$_SESSION['user']['role']==='admin' OR !$_SESSION['user']['id']===ADMIN_ID) {
            header('Location: '.BASE_URL.'login');
            exit;
        }

        $carpool = new Carpool(Database::getPDOInstance());
        
        $results = $carpool->findCarpoolsAndCredits();

        //shouldn't ever be null, but just in case
        if ($results == null) {
            exit();
        }
        //header('Content-Type: application/json');
        $results_json = json_encode($results);
        //return $results_json;
        echo $results_json; //string, still need to be parsed
        exit;
        
    }
}