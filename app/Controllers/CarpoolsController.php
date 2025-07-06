<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Carpool;
use App\Models\Review;
use App\Models\Vehicle;
use App\Models\View_carpool_full;
use App\Models\User;
use App\Models\User_Carpool;
use App\Models\View_driver_comments;

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

            $vw_carpool_full = new View_carpool_full(Database::getPDOInstance());

            $results = $vw_carpool_full->findByCity(
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

    public function details($carpool_id)
    {
        // check if user's connected
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        }

        // Get full carpool details
        $vw_carpool_full = new View_carpool_full(Database::getPDOInstance());
        $results_carpool = $vw_carpool_full->findById($carpool_id);
        if($results_carpool ===null) {
            echo "Une erreur s'est produite, veuillez essayer plus tard.";
            dump($carpool_id);
            exit();
        }

        // Get comments from reviews
        $comments = new View_driver_comments(Database::getPDOInstance());
        $driver_id = $results_carpool['driver_id'];
        $results_comment = $comments->findByDriver($driver_id);
        if($results_comment ===null) {
            echo "Une erreur s'est produite, veuillez essayer plus tard.";
            exit();
        }
        
        $data = [
            'title' => "Réservez votre place",
            'view' => "carpools.details",
            'carpool' => $results_carpool,
            'comments' => $results_comment
        ];       

        Controller::render($data['view'], $data);

    }

    public function booking($carpool_id)
    {
        
        // check if user's connected
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $booking = null;
        $stats = [];

        // Check if enough credits
        $user = new User(Database::getPDOInstance());
        $creds = $user->getCreds($user_id);
        if($creds ===null) {
            echo "Une erreur s'est produite, veuillez essayer plus tard.";
            dump($_SESSION);
            exit();
        }
       
        $carpool = new Carpool(Database::getPDOInstance());
        $price = $carpool->getPrice($carpool_id);
        if($price ===null) {
            echo "Une erreur s'est produite, veuillez essayer plus tard.";
            exit();
        }
                
        if($creds['credit']<$price['price']) {
            $booking = 'no_credits';
        }else {            
            // check if seat still available
            $vw_carpool_full = new View_carpool_full(Database::getPDOInstance());
            $vw_carpool_full = $carpool->isSeatAvailable($carpool_id);
            if($vw_carpool_full) {
                //book seat
                $uc = new User_Carpool(Database::getPDOInstance());
                $uc->bookSeat($user_id, $carpool_id);
                //take credits from user
                $user->takeCreds($user_id, $price['price']);
                $booking = 'valid'; 
                //get new credits balance
                $creds = $user->getCreds($user_id);
            }else {
                $booking = 'no_seats';
            }            
        } 

        $stats['credit']=$creds['credit']; 
        $stats['price']=$price['price'];
                
        $data = [
            'title' => "Réservez votre place",
            'view' => "carpools.booking",
            'booking' => $booking,
            'stats' =>$stats
        ];       

        Controller::render($data['view'], $data);
    } 

    
}