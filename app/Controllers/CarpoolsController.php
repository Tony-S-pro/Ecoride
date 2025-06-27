<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Carpool;

class CarpoolsController extends Controller
{
    public function index(): void
    {        
        /*
        // check if user's connected
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'signup');
            exit;
        }*/

        $results = false;
        if(isset($_POST['search'])) {
            if($_POST['search'] != '') {

                $city = '%'.$_POST['search'].'%';
                unset($_POST['search']);

                $carpool = new Carpool(Database::getPDOInstance());
                $results = $carpool->findByCityDep_like($city);

            if ($results == null) {
                exit();
            }
            //header('Content-Type: application/json');
            $results_json = json_encode($results);
            echo $results_json; //string, still need to be parsed
            exit;


            }

            
        }
        
        $data = [
            'title' => "Liste des covoiturages planifiés",
            'view' => "carpools",
            'results' => $results
        ];        

        Controller::render($data['view'], $data);
    }

    
}