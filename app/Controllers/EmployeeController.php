<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Carpool;
use App\Models\Review;
use App\Models\User;
use App\Models\View_participants;

class EmployeeController extends Controller
{
    public function index(): void
    {
        // check if user's connected and has employee role/id        
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        }
        if(!$_SESSION['user']['role']==='employee' OR !in_array($_SESSION['user']['id'], EMPLOYEES_ID)) {
            header('Location: '.BASE_URL.'signup');
            exit;
        }

        //get reviews (comments)
        $reviewModel = new Review(Database::getPDOInstance());
        $results_review = $reviewModel->findNoValidId(FALSE);

        //get reviews data (comments)
        if(empty($results_review)) {
            $comments_data = null;
        }else {
            foreach ($results_review as $id) {
                $results = $reviewModel->getNoValidData($id['id']);
                $results['creation_date'] = date('d-m-y', strtotime($results['creation_date']));
                //$comments_data[] = $results;
                $comments_data[]=[
                    "id" => $results['id'],
                    "passenger_email" => $results['passenger_email'],
                    "passenger_pseudo" => $results['passenger_pseudo'],
                    "driver_email" => $results['driver_email'],
                    "driver_pseudo" => $results['driver_pseudo'],
                    "rating" => $results['rating'],
                    "comment" => $results['comment'],
                    "creation_date" => $results['creation_date']
                ];
            }
        }

        //get reviews(objections)
        $results_review = $reviewModel->findNoValidId(TRUE);

        //get reviews data (objections)
        if(empty($results_review)) {
            $objections_data = null;
        }else {
            foreach ($results_review as $id) {
                $results = $reviewModel->getNoValidData($id['id']);
                $results['creation_date'] = date('d-m-y', strtotime($results['creation_date']));
                //$objections_data[] = $results;
                $objections_data[]=[
                    "id" => $results['id'],
                    "carpool_id" => $results['carpool_id'],
                    "passenger_email" => $results['passenger_email'],
                    "passenger_pseudo" => $results['passenger_pseudo'],
                    "driver_email" => $results['driver_email'],
                    "driver_pseudo" => $results['driver_pseudo'],
                    "rating" => $results['rating'],
                    "comment" => $results['comment'],
                    "creation_date" => $results['creation_date']
                ];               
            }
        }

        //get carpool data for objections
        if(empty($results_review)) {
            $carpools_data = null;
        }else {
            $carpoolModel = new Carpool(Database::getPDOInstance());
            foreach ($objections_data as &$id) {
                $results_carpool = $carpoolModel->findById($id['carpool_id']);
                $results_carpool['departure_date'] = date('d-m-y', strtotime($results_carpool['departure_date']));
                $results_carpool['departure_time'] = substr($results_carpool['departure_time'],0,-3);
                //$carpools_data[] = $results;
                $carpools_data=[
                    "departure_date" => $results_carpool['departure_date'],
                    "departure_time" => $results_carpool['departure_time'],
                    "departure_address" => $results_carpool['departure_address'],
                    "departure_city" => $results_carpool['departure_city'],
                    "arrival_city" => $results_carpool['arrival_city'],
                    "arrival_address" => $results_carpool['arrival_address'],
                    "travel_time" => $results_carpool['travel_time'],
                    "description" => $results_carpool['description']
                ];
                $id += ['carpool' => $carpools_data];                
            }
        }

        $data = [
            'title' => "Dashboard employé",
            'view' => "employee",
            'comments' => $comments_data,
            'objections' => $objections_data
        ];

        Controller::render($data['view'], $data);
    }

    public function validate_comment($review_id)
    {
        // check if user's connected and has employee role/id        
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        }
        if(!$_SESSION['user']['role']==='employee' OR !in_array($_SESSION['user']['id'], EMPLOYEES_ID)) {
            header('Location: '.BASE_URL.'signup');
            exit;
        }

        //validate review
        $review = new Review(Database::getPDOInstance());
        $review->validate($review_id);

        header('Location: '.BASE_URL.'employee/confirmation/comment_validated');
        exit;
    }

    public function reject_comment($review_id)
    {
        // check if user's connected and has employee role/id        
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        }
        if(!$_SESSION['user']['role']==='employee' OR !in_array($_SESSION['user']['id'], EMPLOYEES_ID)) {
            header('Location: '.BASE_URL.'signup');
            exit;
        }

        //remove comment
        $review = new Review(Database::getPDOInstance());
        $review->remove_comment($review_id);
        
        //validate review
        $review->validate($review_id);

        header('Location: '.BASE_URL.'employee/confirmation/comment_removed');
        exit;
    }

    public function validate_objection($review_id)
    {
        // check if user's connected and has employee role/id        
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        }
        if(!$_SESSION['user']['role']==='employee' OR !in_array($_SESSION['user']['id'], EMPLOYEES_ID)) {
            header('Location: '.BASE_URL.'signup');
            exit;
        }

        //get carpool's id, then price 
        $reviewModel= new Review(Database::getPDOInstance());
        $carpool_id = $reviewModel->findCarpoolId($review_id);
        $carpool_id = $carpool_id['carpool_id'];

        $carpoolModel = new Carpool(Database::getPDOInstance());
        $price = $carpoolModel->getPrice($carpool_id);
        $price = $price['price'];

        //get passenger's id, then reinburse 
        $passenger =  $reviewModel->findUserId($review_id);
        $passenger= $passenger['user_id'];

        $userModel = new User(Database::getPDOInstance());
        $userModel->giveCreds($passenger, $price); 

        //mark objection as reviewed
        $reviewModel->switchObjectionToReviewed($review_id);

        header('Location: '.BASE_URL.'employee/confirmation/objection_validated');
        exit;
    }

    public function reject_objection($review_id)
    {
        // check if user's connected and has employee role/id        
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        }
        if(!$_SESSION['user']['role']==='employee' OR !in_array($_SESSION['user']['id'], EMPLOYEES_ID)) {
            header('Location: '.BASE_URL.'signup');
            exit;
        }

        //get carpool's id, then price 
        $reviewModel= new Review(Database::getPDOInstance());
        $carpool_id = $reviewModel->findCarpoolId($review_id);
        $carpool_id = $carpool_id['carpool_id'];

        $carpoolModel = new Carpool(Database::getPDOInstance());
        $price = $carpoolModel->getPrice($carpool_id);
        $price = $price['price'];

        //get driver's id, then pay 
        $driver = $carpoolModel->findDriverById($carpool_id);
        $driver = $driver['driver_id'];

        $userModel = new User(Database::getPDOInstance());
        $userModel->giveCreds($driver, $price);

        //mark objection as reviewed
        $reviewModel->switchObjectionToReviewed($review_id);

        header('Location: '.BASE_URL.'employee/confirmation/objection_rejected');
        exit;

    }

    public function confirmation($param)
    {
        // check if user's connected and has employee role/id        
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        }
        if(!$_SESSION['user']['role']==='employee' OR !in_array($_SESSION['user']['id'], EMPLOYEES_ID)) {
            header('Location: '.BASE_URL.'signup');
            exit;
        }

        $param=(string)$param;

        switch ($param) {
            case 'comment_validated':
                $display = 'Le commentaire a bien été validé.';
                break;
            case 'comment_removed':
                $display = 'Le commentaire a bien été supprimé.';
                break;
            case 'objection_validated':
                $display = "L'objection a été validée et le passagé remboursé.";
                break;
            case 'objection_rejected':
                $display = "L'objection a été rejetée et le conducteur a été payé.";
                break;            
            default:
                $display = "Erreur.";
                break;
        }

        $data = [
            'title' => "Traitement de l'avis",
            'view' => "employee.confirmation",
            'display' => $display
        ];

        Controller::render($data['view'], $data);

    }
}