<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Carpool;
use App\Models\View_carpool_full;
use App\Models\View_carpools_status;

class DashboardController extends Controller
{
    public function index(): void
    {
        // check if user's connected
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        }
        
        if($_SESSION['user']['role']==='admin' && $_SESSION['user']['id']===ADMIN_ID) {
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
            header('Location: '.BASE_URL.'login');
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

    public function carpools_passenger($param)
    {
        // check if user's connected
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $results = [];

        //find carpools by user_id
        $cs = new View_carpools_status(Database::getPDOInstance());

        if($param==='past') {            
            $carpools_arr = $cs->findByUser_past($user_id);
            
        }elseif($param==='planned') {
            $carpools_arr = $cs->findByUser_planned($user_id);
           
        }

        if ($carpools_arr === null) {
                echo null;
                exit();
        }        

        //get carpools data
        $carpools = new View_carpool_full(Database::getPDOInstance());

        foreach ($carpools_arr as $c) {
            $res = $carpools->findById($c['carpool_id']);
            $res['departure_date'] = date('d/m/y', strtotime($res['departure_date']));
            $res['departure_time'] = substr($res['departure_time'],0,-3);
            if($res===null) {
                return null;
            } 
            $results[]=$res;
        }
        //header('Content-Type: application/json');
        $results_json = json_encode($results);
        //return $results_json;
        echo $results_json; //string, still need to be parsed
        exit;        
    }

}