<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Carpool;
use App\Models\User;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index(): void
    {
        //placeholder ("voir tout vos avis ?" conducteur et/ou passager)
        header('Location: '.BASE_URL);
        exit;
    }

    public function passenger($carpool_id): void
    {
        // check if user's connected
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        }

        if (!filter_var($carpool_id,FILTER_VALIDATE_INT)) {
            echo "Une erreur s'est produite, veuillez essayer plus tard.";
            exit();
        }

        $carpool_data = [];
        $driver_data = [];

        //get carpool data
        $carpool = new Carpool(Database::getPDOInstance());
        $results_carpool = $carpool->findById($carpool_id);
        if(empty($results_carpool)) {
            echo "Une erreur s'est produite, veuillez essayer plus tard.";
            exit();
        }
        $carpool_data = [
            'id' => $results_carpool['id'],
            'dep_date' => date('d/m/y', strtotime($results_carpool['departure_date'])),
            'dep_time' => substr($results_carpool['departure_time'],0,-3),
            'dep_city' => $results_carpool['departure_city'],
            'dep_address' => $results_carpool['departure_address']
        ];

        //get driver data
        $user = new User(Database::getPDOInstance());
        $results_driver = $user->findById($results_carpool['driver_id']);
        if(empty($results_driver)) {
            echo "Une erreur s'est produite, veuillez essayer plus tard.";
            exit();
        }
        $driver_data = [
            'firstname' => $results_driver['firstname'],
            'pseudo' => $results_driver['pseudo']
        ];        

        $data = [
            'title' => "Créer un covoiturage",
            'view' => "review.passenger",
            'carpool' => $carpool_data,
            'driver' => $driver_data
        ];        

        Controller::render($data['view'], $data);
    }

    public function register_review($carpool_id)
    {
        // check if user's connected
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        }

        $user_id = $_SESSION['user']['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // check CRSF token
            if(!Controller::is_csrf_valid()) {
                exit("Error - CSRF token invalid");
            }

            // clean
            $rating = $_POST['rating'] ?? '';
            $comment = $_POST['comment'] ?? '';
            $checkObjection = $_POST['checkObjection'] ?? '';

            // Validate fields
            if (!in_array($rating, ['1','2','3','4','5',''])) {
                $errors['rating'] = "Veuillez entrer une valeur correcte";
            }

            if (empty($comment) AND $checkObjection === '1') {
                $errors['comment'] = "Veuillez décrire votre problème avec ce covoiturage.";
            }
            if (!empty($comment)) {
                if (strlen($comment) > 255) {
                $errors['comment'] = "255 caractères maximum.";
                }
            }

            // In case of error, back to page w/ messages and old values
            if (!empty($errors)) {
                // Store errors in session
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $_POST;

                header('Location: '.BASE_URL.'review/passenger/'.$carpool_id);
                exit;
            }

            // If no error then connnect to db
            $validated='1';

            if($rating === '') {
                $rating=null;
            }

            if($comment==='') {
                $comment=null;
            }else {
                $comment=(string)$comment;
                $validated='0';
            }
            if($checkObjection==='') {
                $checkObjection='0';
            }else{
                 $checkObjection='1';
                 $validated='0';
            }            
            
            //create review in review
            $reviewModel = new Review(Database::getPDOInstance());
            $review = $reviewModel->create_review([
                'user_id' => $user_id,
                'carpool_id' => $carpool_id,
                'rating' => $rating,
                'comment' => $comment,
                'validated' => $validated,
                'objection' => $checkObjection,
                'creation_date' => date('Y-m-d H:i:s')
            ]);

            //if no objection, then get price 
            if($checkObjection==='0') {
                $carpoolModel = new Carpool(Database::getPDOInstance());
                $price = $carpoolModel->getPrice($carpool_id);

                //get driver
                if(isset($price['price'])) {                    
                    $driver = $carpoolModel->findDriverById($carpool_id);

                    //pay driver
                    if(!empty($driver['driver_id'])) {                        
                    $userModel = new User(Database::getPDOInstance());
                    $userModel->giveCreds($driver['driver_id'], $price['price']);
                    }
                }
            }

            //purge $_SESSION['errors']/$_SESSION['old']
            $_SESSION['errors']=[];
            $_SESSION['old']=[];

            header('Location: '.BASE_URL.'review/confirmed/'.$review['id']);
            exit;
        }
    }

    public function confirmed($review_id): void
    {
        // check if user's connected
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        }      

        if (filter_var($review_id, FILTER_VALIDATE_INT)=== false) {
            echo "Une erreur s'est produite, veuillez essayer plus tard.";
            exit();
        }

        //get review data
        $review = new Review(Database::getPDOInstance());
        $results_review = $review->findById($review_id);
        if(empty($results_review)) {
            echo "Une erreur s'est produite, veuillez essayer plus tard.";
            exit();
        }
        $review_data = [
            'rating' => ($results_review['rating'] !== null) ? '1' : '0',
            'comment' => ($results_review['comment'] !== null) ? '1' : '0' ,
            'objection' => $results_review['objection'],
            'validated' => $results_review['validated']
        ];

        $data = [
            'title' => "Nous avons reçu votre confirmation",
            'view' => "review.confirmed",
            'review' => $review_data
        ];        

        Controller::render($data['view'], $data);
    }
}