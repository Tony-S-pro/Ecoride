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
        if(isset($_POST['search_city1']) OR isset($_POST['search_city2']) OR isset($_POST['search_date'])) {

            $city1=null;
            $city2=null;
            $date=null;
            $address1=null;
            $address2=null;
            $checkEco=null;
            
            if($_POST['search_city1'] != '') {
                $city1 = '%'.trim($_POST['search_city1']).'%';
                unset($_POST['search_city1']);

                if(isset($_POST['search_address1'])) {
                    if($_POST['search_address1'] != '') {
                    $address1 = '%'.trim($_POST['search_address1']).'%';
                    unset($_POST['search_address1']);
                    }                    
                } 
            }      

            if($_POST['search_city2'] != '') {
                $city2 = '%'.trim($_POST['search_city2']).'%';
                unset($_POST['search_city2']);

                if(isset($_POST['search_address2'])) {
                    if($_POST['search_address2'] != '') {
                    $address2 = '%'.trim($_POST['search_address2']).'%';
                    unset($_POST['search_address2']);
                    }                    
                } 
            } 

            if($_POST['search_date'] != '') {
                $date = $_POST['search_date'];
                unset($_POST['search_date']);
            }

            if(isset($_POST['checkEco'])){
                if($_POST['checkEco'] == 'check') {
                    $checkEco = $_POST['checkEco'];
                    unset($_POST['checkEco']);
                }
            }            

            $carpool = new Carpool(Database::getPDOInstance());
            $results = $carpool->findByCity($city1, $city2, $date, $address1, $address2, $checkEco);

            if ($results == null) {
                exit();
            }
            //header('Content-Type: application/json');
            $results_json = json_encode($results);
            echo $results_json; //string, still need to be parsed
            exit;
            
        } 
        
        $data = [
            'title' => "Liste des covoiturages planifiés",
            'view' => "carpools"
        ];       

        Controller::render($data['view'], $data);
    }

    
}