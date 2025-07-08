<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\MailHelper;
use App\Models\Carpool;
use App\Models\User;
use App\Models\User_Carpool;
use App\Models\Vehicle;
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

    public function new_carpool()
    {
        $data = [
            'title' => "Créez un covoiturage",
            'view' => "dashboard.carpool"
        ];        

        Controller::render($data['view'], $data);
    }

    public function new_vehicle()
    {
        // check if user's connected
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        }        

        $data = [
            'title' => "Enregistrer un véhicule",
            'view' => "dashboard.vehicles"
        ];        

        Controller::render($data['view'], $data);
    }

    public function vehicles()
    {
        // check if user's connected
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        }      

        $user_id = $_SESSION['user']['id'];
        
        //get vehicules' data
        $vehicle = new Vehicle(Database::getPDOInstance());
        $results = $vehicle->findAllByUser($user_id);
        
        (!empty($results)) ? $vehicle_data=$results : $vehicle_data=null;

        $data = [
            'title' => "Vos véhicules et préférences",
            'view' => "dashboard.vehicles",
            'vehicles' => $vehicle_data
        ];        

        Controller::render($data['view'], $data);
    }

    public function register_vehicle()
    {
        
        // check if user's connected
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        }        

        $user_id = $_SESSION['user']['id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $errors = [];
            $brand = trim($_POST['brand'] ?? '');
            $model = trim($_POST['model'] ?? '');
            $fuel = $_POST['fuel'] ?? '';
            $registration_date = $_POST['registration_date'] ?? '';
            $registration = trim(strtoupper($_POST['registration']) ?? '');
            $color = trim($_POST['color'] ?? '');
            $seats = $_POST['seats'] ?? '4';
            $smoking = $_POST['smoking'] ?? '0';
            $animals = $_POST['animals'] ?? '0';
            $misc = trim($_POST['misc'] ?? null);
            
            
            // Validate data received (back-end)
            if (empty($brand)) {
                $errors['brand'] = "Marque requise.";
            } elseif (strlen($brand) < 3) {
                $errors['brand'] = "La marque requiert au moins 3 caractères.";
            }

            if (empty($model)) {
                $errors['model'] = "Modèle requis.";
            } elseif (strlen($model) < 3) {
                $errors['model'] = "Le modèle requiert au moins 3 caractères.";
            }

            if (empty($fuel)) {
                $errors['fuel'] = "Type d'énergie requise.";
            } elseif ($fuel!=='essence' 
                AND $fuel!=='diesel' 
                AND $fuel!=='electrique' 
                AND $fuel!=='hybrid' 
                AND $fuel!=='autre') {
                $errors['fuel'] = "Selectionnez un type d'énergie.";
            }

            if (empty($registration_date)) {
                $errors['registration_date'] = "Année d'immatriculation requise.";
            } elseif (!preg_match('/^(19|20)\d{2}$/', $registration_date)) { //ie 1900-2099
                $errors['registration_date'] = "Choisissez une année appropriée (exemple: 2025).";
            }

            if (empty($registration)) {
                $errors['registration'] = "N° d'immatriculation requis.";
            } elseif (!preg_match('/^[A-Z]{2}[-][0-9]{3}[-][A-Z]{2}$/', $registration)) {
                $errors['registration'] = "Choisissez un n° au format AA-000-AA.";
            }

            if (empty($color)) {
                $errors['color'] = "Couleur requise.";
            } elseif (strlen($color) < 3) {
                $errors['color'] = "La couleur requiert au moins 3 caractères.";
            }

            if (empty($seats)) {
                $errors['seats'] = "Nombre de places disponibles requis.";
            } elseif (!preg_match('/^([1-9]|1[0-2])$/', $seats)) {
                $errors['seats'] = "Le nombre de places disponibles doit être entre 1 et 12.";
            }

            if ($smoking !== '0') {
                $smoking = '1';
            }
            if ($animals !== '0') {
                $animals = '1';
            }

            if (!empty($misc)) {
                if (strlen($misc) > 255) {
                $errors['misc'] = "255 caractères maximum.";
                }
            }

            // In case of error -> back to signup view w/ messages
            if (!empty($errors)) {
                // Store  errors in session
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $_POST;

                header('Location: '.BASE_URL.'dashboard/vehicles');
                exit;
            }

            // connect to db
            $vehicle = new Vehicle(Database::getPDOInstance());

            // check if registration already in db (or use lastInsertId?)
            $existReg = $vehicle->isRegistrationIn(strtoupper($registration));
            if ($existReg===true) {
                $errors['registration'] = "Ce n° d'immatriculation est déjà utilisé.";
            
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $_POST;
                header('Location: ' . BASE_URL . 'dashboard/vehicles');
                exit;
            }

            // insert in db
            $vehicle->createVehicle([
                'driver_id' => $user_id,
                'brand' => $brand,
                'model' => $model,
                'fuel' => $fuel,
                'registration_date' => $registration_date,
                'registration' => $registration,
                'color' => $color,
                'seats' => $seats,
                'smoking' => $smoking,
                'animals' => $animals,
                'misc' => $misc,
                'creation_date' => date('Y-m-d H:i:s')
            ]);

            // update user session
            $id = $vehicle->getIdByRegistration($registration);
            $_SESSION['user']['vehicles'][] = $id['id'];

            //purge $_SESSION['errors']/$_SESSION['old']
            $_SESSION['errors']=[];
            $_SESSION['old']=[];

            // Redirect to dashboard
            header('Location: '.BASE_URL.'dashboard/vehicles');
            exit;
        }
            
    }

    public function delete_vehicle($vehicle_id)
    {
        // check if user's connected
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        }        

        $user_id = $_SESSION['user']['id'];

        //driver_id to be sure that the driver is deleting their own carpool
        $vehicle = new Vehicle(Database::getPDOInstance());
        $vehicle->deleteVehicle($user_id, $vehicle_id);

        //remove id from $_SESSION['user']['vehicles'][id]
        $res = $vehicle->findAllIdByUser($user_id);
        $_SESSION['user']['vehicles'] = $res;

        header('Location: '.BASE_URL.'dashboard/deleted');
        exit;

    }

    public function deleted()
    {
        $data = [
            'title' => "Véhicule supprimé",
            'view' => "dashboard.deleted"
        ];        

        Controller::render($data['view'], $data);
    }

}


