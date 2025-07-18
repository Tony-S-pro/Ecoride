<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Carpool;

class HomeController extends Controller
{
    public function index(): void
    {
        
        $results = false;
        if(isset($_POST['search_city1']) OR isset($_POST['search_city2']) OR isset($_POST['search_date'])) {

            $city1=null;
            $city2=null;
            $date=null;
            
            if($_POST['search_city1'] != '') {
                $city1 = '%'.trim($_POST['search_city1']).'%';
                unset($_POST['search_city1']);
            }

            if($_POST['search_city2'] != '') {
                $city2 = '%'.trim($_POST['search_city2']).'%';
                unset($_POST['search_city2']);
            }

            if($_POST['search_date'] != '') {
                $date = $_POST['search_date'];
                unset($_POST['search_date']);
            }

            $carpool = new Carpool(Database::getPDOInstance());
            $results = $carpool->findByCity_lite($city1, $city2, $date);

            if ($results == null) {
                exit();
            }
            //header('Content-Type: application/json');
            $results_json = json_encode($results);
            echo $results_json; //string, still need to be parsed
            exit;
            
        }
        
        
        $data = [
            'title' => "Bienvenue sur la plateforme de covoiturage écologique !",
            'view' => "home",
        ];        

        Controller::render($data['view'], $data);
    }
}