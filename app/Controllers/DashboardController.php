<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\MailHelper;
use App\Models\Carpool;
use App\Models\Review;
use App\Models\User;
use App\Models\User_Carpool;
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

        //get user credits balance
        $user_id = $_SESSION['user']['id'];
        $user = new User(Database::getPDOInstance());
        $creds = $user->getCreds($user_id);
        $creds = $creds['credit'];


        $data = [
            'title' => "Votre dashboard",
            'view' => "dashboard",
            'credit' => $creds
        ];        

        Controller::render($data['view'], $data);
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
        }else {
            header('Location: '.BASE_URL.'dashboard');
            exit;
        }

        if ($carpools_arr === null) {
                echo null;
                exit();
        }      

        //get carpools data
        $carpools = new View_carpool_full(Database::getPDOInstance());

        foreach ($carpools_arr as $c) {
            $res = $carpools->findById($c['carpool_id']);
            if($res===null) {
                return null;
            }
            $res['departure_date'] = date('d/m/y', strtotime($res['departure_date']));
            $res['departure_time'] = substr($res['departure_time'],0,-3);
            
            //check if past carpool already reviewed
            if($param==='past') {            
                $review = new Review(Database::getPDOInstance());
                ($review->isReviewed($user_id, $c['carpool_id'])) ? $res['reviewed']=1 : $res['reviewed']=0  ;
                
            }

            $results[]=$res;            
        }

        //header('Content-Type: application/json');
        $results_json = json_encode($results);
        //return $results_json;
        echo $results_json; //string, still need to be parsed
        exit;        
    }

    public function carpools_driver($param)
    {
        // check if user's connected and a driver
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        }
        if (empty($_SESSION['user']['vehicles'])) {
            header('Location: '.BASE_URL.'dashboard');
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $results = [];

        //find carpools by user_id as driver
        $carpool = new Carpool(Database::getPDOInstance());

        if($param==='past') {            
            $carpools_arr = $carpool->findIdByDriver_past($user_id);            
        }elseif($param==='planned') {
            $carpools_arr = $carpool->findIdByDriver_planned($user_id);           
        }else {
            header('Location: '.BASE_URL.'dashboard');
            exit;
        }

        if ($carpools_arr === null) {
                echo null;
                exit();
        }        

        //get carpools data
        $carpools = new View_carpool_full(Database::getPDOInstance());

        foreach ($carpools_arr as $c) {
            $res = $carpools->findById($c['id']);
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

    public function cancel_driver($carpool_id)
    {
        // check if user's connected and a driver
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        }
        if (empty($_SESSION['user']['vehicles'])) {
            header('Location: '.BASE_URL.'dashboard');
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $results = [];

        //get passengers list
        $list=[];
        $uc = new User_Carpool(Database::getPDOInstance());
        $results_uc = $uc->findUsersByCarpool($carpool_id);
        if($results_uc===null){
            $list=null;
        }else{
            foreach ($results_uc as $r) {            
                $list[]=$r['user_id'];
            } 
        }

        //get carpool price to reimburse passengers
        $carpool = new Carpool(Database::getPDOInstance());
        if(!empty($list)) {
            $price = $carpool->getPrice($carpool_id);
            if($price ===null) {
                echo "Une erreur s'est produite, veuillez essayer plus tard.";
                exit();
            }
            $price=$price["price"];
        }         
        
        //suppr carpool
        $carpool->cancelCarpool($user_id, $carpool_id);

        //reimburse passengers and get emails
        $list_email=[];
        $user = new User(Database::getPDOInstance());
        if(!empty($list)) {
            foreach ($list as $user_id) {
            $user->giveCreds($user_id, $price);
            $list_email[] = $user->getEmail($user_id);
            }  
        }              
        
        //give back 2 credits to driver
        $user->giveCreds($user_id, 2);

        //send mail to passengers w/ php mailer via MailHelper
        if(!empty($list) AND !empty($list_email)) {
            foreach ($list_email as $contact_email) {
            MailHelper::sendCancelMail($contact_email['email']);
            }  
        }
                
        header('Location: '.BASE_URL.'dashboard/cancellation');
        exit;
        
    }

    public function cancel_passenger($carpool_id)
    {
        // check if user's connected
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        }        

        $user_id = $_SESSION['user']['id'];

        //get carpool price
        $carpool = new Carpool(Database::getPDOInstance());
        $price = $carpool->getPrice($carpool_id);
        if($price ===null) {
            echo "Une erreur s'est produite, veuillez essayer plus tard.";
            exit();
        }

        //suppr carpool booking
        $uc = new User_Carpool(Database::getPDOInstance());
        $uc->cancelBooking($user_id, $carpool_id);

        //give credits to user
        $user = new User(Database::getPDOInstance());
        $user->giveCreds($user_id, $price['price']);
        
        header('Location: '.BASE_URL.'dashboard/cancellation');
        exit;
        
    }

    public function cancellation()
    {
        $data = [
            'title' => "Covoiturage annulé",
            'view' => "dashboard.cancellation"
        ];        

        Controller::render($data['view'], $data);
    }
    
    public function departure($carpool_id)
    {
        // check if user's connected
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        }        

        $user_id = $_SESSION['user']['id'];
        
        //change carpool status (also use user_id to auth)
        $carpool = new Carpool(Database::getPDOInstance());
        $carpool->startTrip($user_id, $carpool_id);
        
        header('Location: '.BASE_URL.'dashboard');
        exit;
    }

    public function arrival($carpool_id)
    {
        // check if user's connected
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        }        

        $user_id = $_SESSION['user']['id'];
        
        //change carpool status (also use user_id to auth)
        $carpool = new Carpool(Database::getPDOInstance());
        $carpool->endTrip($user_id, $carpool_id);

        //get passengers list
        $list=[];
        $uc = new User_Carpool(Database::getPDOInstance());
        $results_uc = $uc->findUsersByCarpool($carpool_id);
        if($results_uc===null){
            $list=null;
        }else{
            foreach ($results_uc as $r) {            
                $list[]=$r['user_id'];
            } 
        }

        //get emails
        $list_email=[];
        if(!empty($list)) {
            $user = new User(Database::getPDOInstance());
            foreach ($list as $user_id) {
            $list_email[] = $user->getEmail($user_id);
            }  
        }              

        //ask them by mail to validate/review the trip
        if(!empty($list) AND !empty($list_email)) {
            foreach ($list_email as $contact_email) {
            MailHelper::sendReviewMail($contact_email['email']);
            }  
        }
        
        header('Location: '.BASE_URL.'dashboard');
        exit;
    }



}


