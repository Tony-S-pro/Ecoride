<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Carpool;
use App\Models\User;

class AdminController extends Controller
{
    public function index(): void
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

        //total credits earned
        $creds_total = null;
        $carpool = new Carpool(Database::getPDOInstance());
        $result = $carpool->findAllCreditsEarned();
        $creds_total = $result;

        $data = [
            'title' => "Dashboard admin",
            'view' => "admin",
            'credits_total' => $creds_total
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

    public function users()
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

        $results = false;
        if(isset($_POST['search_id']) OR isset($_POST['search_pseudo']) OR isset($_POST['search_email']) OR isset($_POST['search_role'])) {
            
            $id=null;
            $pseudo=null;
            $email=null;
            $role=null;

            if($_POST['search_id'] != '') {
                $id = $_POST['search_id'];
                unset($_POST['search_id']);
            }            
            if($_POST['search_email'] != '') {
                $email = '%'.trim($_POST['search_email']).'%';
                unset($_POST['search_email']);
            }
            if($_POST['search_pseudo'] != '') {
                $pseudo = '%'.trim($_POST['search_pseudo']).'%';
                unset($_POST['search_pseudo']);
            }
            if($_POST['search_role'] != '') {
                $role = $_POST['search_role'];
                unset($_POST['search_role']);
            }

            $carpool = new User(Database::getPDOInstance());
            $results = $carpool->findUsers($id, $pseudo, $email, $role);

            if ($results == null) {
                exit();
            }
            //header('Content-Type: application/json');
            $results_json = json_encode($results);
            echo $results_json; //string, still need to be parsed
            exit;            
        }
    }

    public function ban($user_id)
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

        //change user suspended status to 1
        $user = new User(Database::getPDOInstance());
        $user->setUserSuspended($user_id, true);
        
        header('Location: '.BASE_URL.'admin');
        exit;
        
    }

    public function reinstate($user_id)
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

        //change user suspended status to 1
        $user = new User(Database::getPDOInstance());
        $user->setUserSuspended($user_id, false);

        header('Location: '.BASE_URL.'admin');
        exit;
        
    }
}