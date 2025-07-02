<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Carpool;

class DashboardController extends Controller
{
    public function index(): void
    {
        // check if user's connected
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'signup');
            exit;
        }

        if($_SESSION['user']['role']='admin' && $_SESSION['user']['id']=ADMIN_ID) {
            self::admin();
            exit;
        }

        $data = [
            'title' => "Votre dashboard",
            'view' => "dashboard"
        ];        

        Controller::render($data['view'], $data);
    }

    public function admin(): void
    {
        // check if user's connected and has admin role/id
        
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'signup');
            exit;
        }
        if(!$_SESSION['user']['role']='admin' OR !$_SESSION['user']['id']=ADMIN_ID) {
            header('Location: '.BASE_URL.'signup');
            exit;
        }

        $data = [
            'title' => "Dashboard admin",
            'view' => "dashboard.admin"
        ];        

        Controller::render($data['view'], $data);
    }

    public function chart(): void
    {
        // check if user's connected and has admin role/id
        
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'signup');
            exit;
        }
        if(!$_SESSION['user']['role']='admin' OR !$_SESSION['user']['id']=ADMIN_ID) {
            header('Location: '.BASE_URL.'signup');
            exit;
        }

        $carpool = new Carpool(Database::getPDOInstance());
        
        $results = $carpool->findCarpoolsNb();

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