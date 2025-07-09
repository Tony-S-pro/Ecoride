<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\ImageHelper;
use App\Models\Vehicle;

class VehiclesController extends Controller
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
            'title' => "Vos véhicules et préférences",
            'view' => "vehicles",
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

            // In case of error -> back to vehicles view w/ messages
            if (!empty($errors)) {
                // Store  errors in session
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $_POST;

                header('Location: '.BASE_URL.'vehicles');
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
                header('Location: ' . BASE_URL . 'vehicles');
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
            header('Location: '.BASE_URL.'vehicles');
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

    public function upload()
    {
        // check if user's connected
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        } 

        $user_id = $_SESSION['user']['id'];
        
        if (ImageHelper::uploadPhoto($user_id)) {
            $msg['upload'] = "Fichier enregistré.";
            $_SESSION['msg'] = $msg;

            $_SESSION['errors']=[]; //remove previous error

            header('Location: '.BASE_URL.'vehicles');
            exit;
        }else {
            $errors['upload'] = "Impossible d'enregistrer ce fichier";
            $_SESSION['errors'] = $errors;

            $_SESSION['msg']=[]; //remove previous message

            header('Location: '.BASE_URL.'vehicles');
            exit;
            
        }
    }
}