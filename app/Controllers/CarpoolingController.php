<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Carpool;
use App\Models\Vehicle;
use App\Models\User;

class CarpoolingController extends Controller
{
    public function index(): void
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
            'title' => "Créer un covoiturage",
            'view' => "carpooling",
            'vehicles' => $vehicle_data
        ];        

        Controller::render($data['view'], $data);
    }
   
    public function register_carpool()
    {
        
        // check if user's connected
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        }        

        $user_id = $_SESSION['user']['id'];
        $vehicle_id_arr = $_SESSION['user']['vehicles'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $errors = [];
            $departure_date = $_POST['departure_date'] ?? '';
            $departure_time = $_POST['departure_time'] ?? '';
            $travel_time = $_POST['travel_time'] ?? '';
            $departure_city = trim($_POST['departure_city'] ?? '');
            $arrival_city = trim($_POST['arrival_city'] ?? '');
            $departure_address = trim($_POST['departure_address'] ?? '');
            $arrival_address = trim($_POST['arrival_address'] ?? '');
            $vehicle_id = $_POST['vehicle_id'] ?? '';
            $price = $_POST['price'] ?? '';
            $description = trim($_POST['description'] ?? null);            
                        
            // Validate data received (back-end)
            if (empty($departure_date)) {
                $errors['departure_date'] = "Date requise.";
            } elseif (!preg_match('/^\d{4}\-(0?[1-9]|1[012])\-(0?[1-9]|[12][0-9]|3[01])$/', $departure_date)) { // yyyy-mm-dd or yyyy-m-d
                $errors['departure_date'] = "Choisissez une date appropriée.";
            } elseif ($departure_date < date("Y-m-d")) {
                $errors['departure_date'] = "Choisissez une date appropriée.";
            }

            if (empty($departure_time)) {
                $errors['departure_time'] = "Heure de départ requise.";
            } elseif (!preg_match('/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/', $departure_time)) { // hh:mm OR h:mm
                $errors['departure_time'] = "Le format doit être hh:mm ou h:mm.";
            }

            if (empty($travel_time)) {
                $errors['travel_time'] = "Durée requise.";
            } elseif (!preg_match('/^(0?[1-9]|1[0-9]|2[0-4])$/', $travel_time)) { //1-24, allow 01,02,etc
                $errors['travel_time'] = "La durée doit être entre 1 et 24.";
            }

            if (empty($departure_city)) {
                $errors['departure_city'] = "Ville de départ requise.";
            } elseif (strlen($departure_city) < 3) {
                $errors['departure_city'] = "Requiert au moins 3 caractères.";
            }

            if (empty($arrival_city)) {
                $errors['arrival_city'] = "Ville d'arrivée requise.";
            } elseif (strlen($arrival_city) < 3) {
                $errors['arrival_city'] = "Requiert au moins 3 caractères.";
            }

            if (empty($departure_address)) {
                $errors['departure_address'] = "Adresse de départ requise.";
            } elseif (strlen($departure_address) < 3) {
                $errors['departure_address'] = "Requiert au moins 3 caractères.";
            }

            if (empty($arrival_address)) {
                $errors['arrival_address'] = "Adresse d'arrivée requise.";
            } elseif (strlen($arrival_address) < 3) {
                $errors['arrival_address'] = "Requiert au moins 3 caractères.";
            }

            if (empty($vehicle_id)) {
                $errors['vehicle_id'] = "Vehicule requis.";
            } elseif (!in_array($vehicle_id, $vehicle_id_arr)) {
                $errors['vehicle_id'] = "Impossible de trouver ce vehicule.";
            }

            if (empty($price)) {
                $errors['price'] = "Prix requis.";
            } elseif (!preg_match('/^(0?[1-9]|[1-9][0-9])$/', $price)) { //1-99, allow 01,02,etc
                $errors['price'] = "Le prix doit être entre 1 et 99.";
            }

            if (!empty($description)) {
                if (strlen($description) > 255) {
                $errors['description'] = "255 caractères maximum.";
                }
            }

            //check if user have credits required (2)
            $user = new User(Database::getPDOInstance());
            $results = $user->getCreds($user_id);
            if ($results['credit'] < 2) {
                $errors['no_credit'] = "Vous n'avez pas les 2 crédits requis pour créer un covoiturage.";
            }

            // In case of error -> back to carpooling view w/ messages
            if (!empty($errors)) {
                // Store  errors in session
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $_POST;

                header('Location: '.BASE_URL.'carpooling');
                exit;
            }

            // take 2 credits and insert carpool into db
            $user->takeCreds($user_id, 2);

            $carpool = new Carpool(Database::getPDOInstance());
            $carpool->createCarpool([
                'driver_id' => $user_id,
                'departure_date' => $departure_date,
                'departure_time' => $departure_time,
                'travel_time' => $travel_time,
                'departure_city' => $departure_city,
                'arrival_city' => $arrival_city,
                'departure_address' => $departure_address,
                'arrival_address' => $arrival_address,
                'vehicle_id' => $vehicle_id,
                'price' => $price,
                'description' => $description,
                'creation_date' => date('Y-m-d H:i:s')
            ]);

            //purge $_SESSION['errors'] and $_SESSION['old']
            $_SESSION['errors']=[];
            $_SESSION['old']=[];

            // Redirect to confirmed page
            header('Location: '.BASE_URL.'carpooling/confirmed');
            exit;
        }            
    }

    public function confirmed(): void
    {
        // check if user's connected
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        }      

        $data = [
            'title' => "Votre covoiturage est prêt.",
            'view' => "carpooling.confirmed"
        ];        

        Controller::render($data['view'], $data);
    }

    /*

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

        header('Location: '.BASE_URL.'vehicles/deleted');
        exit;

    }

    public function deleted()
    {
        $data = [
            'title' => "Véhicule supprimé",
            'view' => "vehicles.deleted"
        ];        

        Controller::render($data['view'], $data);
    }
    */
}