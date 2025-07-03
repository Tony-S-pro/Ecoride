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
        if(isset($_POST['search_city1']) OR isset($_POST['search_city2']) OR isset($_POST['search_address1']) OR isset($_POST['search_address2'])) {

            $city1=null;
            $city2=null;
            $date=null;
            $address1=null;
            $address2=null;
            $minRating=null;
            $maxPrice=null;
            $maxTime=null;
            $checkEco=null;
            
            if(!empty($_POST['search_city1'])) {
                $city1 = '%'.trim((string)$_POST['search_city1']).'%';
                unset($_POST['search_city1']);
            }   
            if(!empty($_POST['search_address1'])) {
                    $address1 = '%'.trim((string)$_POST['search_address1']).'%';
                    unset($_POST['search_address1']);
            }                    
            if(!empty($_POST['search_city2'])) {
                $city2 = '%'.trim((string)$_POST['search_city2']).'%';
                unset($_POST['search_city2']); 
            } 
            if(!empty($_POST['search_address2'])) {
                    $address2 = '%'.trim((string)$_POST['search_address2']).'%';
                    unset($_POST['search_address2']);
            }                    
            if(!empty($_POST['search_date'])) {
                $date = (string)$_POST['search_date'];
                unset($_POST['search_date']);
            }
            if(!empty($_POST['minRating'])) {
                $minRating = abs((int)$_POST['minRating']);
                unset($_POST['minRating']);
            }
            if(!empty($_POST['maxPrice'])) {
                $maxPrice = abs((int)$_POST['maxPrice']);
                unset($_POST['maxPrice']);
            }
            if(!empty($_POST['maxTime'])) {
                $maxTime = abs((int)$_POST['maxTime']);
                unset($_POST['maxTime']);
            }
           if($_POST['checkEco'] === 'true') {
                $checkEco = 'check';
                unset($_POST['checkEco']);
            }                       

            $carpool = new Carpool(Database::getPDOInstance());

            $results = $carpool->findByCity(
                $city1, 
                $city2, 
                $date, 
                $address1, 
                $address2, 
                $minRating, 
                $maxPrice, 
                $maxTime, 
                $checkEco);

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