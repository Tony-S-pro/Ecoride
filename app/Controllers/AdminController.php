<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Carpool;
use App\Models\User;

use App\Core\DatabaseDM;
use App\Models\ObjectionsLogDM;

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

    public function register()
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
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // check CSRF token
            if(!Controller::is_csrf_valid()) {
                dump($_SESSION, $_POST);
                exit("Error - CSRF token invalid");
            }

            $errors = [];
            $pseudo = trim($_POST['emp_pseudo'] ?? '');
            $name = trim($_POST['emp_name'] ?? '');
            $firstname = trim($_POST['emp_firstname'] ?? '');
            $email = trim($_POST['emp_email'] ?? '');
            $password = $_POST['emp_password'] ?? '';
            $confirm = $_POST['emp_confirm_password'] ?? '';
            
            // Validate data received (back-end)
            if (empty($pseudo)) {
                $errors['emp_pseudo'] = "Pseudo requis.";
            } elseif (strlen($pseudo) < 3) {
                $errors['emp_pseudo'] = "Le pseudo requiert au moins 3 caractères.";
            }
            if (empty($name)) {
                $errors['emp_name'] = "nom requis.";
            } elseif (strlen($name) < 3) {
                $errors['emp_name'] = "Le nom requiert au moins 3 caractères.";
            }
            if (empty($firstname)) {
                $errors['emp_firstname'] = "prénom requis.";
            } elseif (strlen($pseudo) < 3) {
                $errors['emp_firstname'] = "Le prénom requiert au moins 3 caractères.";
            }

            if (empty($email)) {
                $errors['emp_email'] = "Email requis.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['emp_email'] = "Adresse mail non valide.";
            }

            if (empty($password)) {
                $errors['emp_password'] = "Mot de passe requis.";
            } elseif (!preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
                $errors['emp_password'] = "Le mot de passe doit contenir au moins 9 caractères (une majuscule et un chiffre minimum).";
            }

            if ($password !== $confirm) {
                $errors['emp_confirm_password'] = "Mots de passe différents.";
            }

            // In case of error -> back to admin view w/ messages
            if (!empty($errors)) {
                // Store  errors in session
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $_POST;

                header('Location: '.BASE_URL.'admin');
                exit;
            }

            // connect to db
            $userModel = new User(Database::getPDOInstance());

            // check if email already in db
            $existCheck = $userModel->isEmailIn(strtolower($email));
            if ($existCheck===true) {
                    $errors['emp_email'] = "Cette adresse email est déjà utilisée.";            

                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $_POST;
                header('Location: ' . BASE_URL . 'admin');
                exit;
            }

            // if not, hash the psw
            $hashedPassword = password_hash( $password, PASSWORD_BCRYPT);

            // insert in db
            $userModel = new User(Database::getPDOInstance());
            $employee_id = $userModel->create([
                'email' => $email,
                'password' => $hashedPassword,
                'pseudo' => $pseudo,
                'name' => $name,
                'firstname' => $firstname,
                'role' => 'employee',
                'subscription_date' => date('Y-m-d H:i:s')
            ]);

            if (!empty($errors)) {
                // purge to avoid them appearing again
                $_SESSION['errors'] = [];
                $_SESSION['old'] = [];
            }

            // Redirect to confirmation
            header('Location: '.BASE_URL.'admin/confirmation/'.$employee_id['id']);
            exit;
        }
    }

    public function confirmation($employee_id): void
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

        //get employee data
        $employee_data = null;
        $user = new User(Database::getPDOInstance());
        $result = $user->findById($employee_id);

        $employee_data = [
            'id' => $result['id'],
            'name' => $result['name'],
            'pseudo' => $result['pseudo'],
            'firstname' => $result['firstname'],
            'email' => $result['email']
        ];

        $data = [
            'title' => "Dashboard admin",
            'view' => "admin.confirmed",
            'employee' => $employee_data
        ];        

        Controller::render($data['view'], $data);
    }

    public function objections_log(): void
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

        //get logs
        $modelDM = new ObjectionsLogDM(DatabaseDM::getDmInstance());
        $docs = $modelDM->readDocument([], ['sort' => ['creation_date' => -1]]); //1 ASC, -1 DSC
        $results= [];
        foreach ($docs as $doc) {
            $doc['creation_date'] = date('d-m-y', strtotime($doc['creation_date']));
            if(isset($doc['update_date'])) {
                $doc['update_date'] = date('d-m-y', strtotime($doc['creation_date']));
            }
            $results[]=$doc;
        }
        $results_json = json_encode($results);
        echo $results_json;
        exit;
        
    }

    public function delete_log($id): void
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

        //get logs
        $modelDM = new ObjectionsLogDM(DatabaseDM::getDmInstance());
        //To pass an ID to MongoDB w/ PHP Library, need to construct a MongoDB\BSON\ObjectID
        $delFilter = ['_id' => new \MongoDb\BSON\ObjectId($id) ];
        $modelDM->deleteDocument($delFilter);

        header('Location: '.BASE_URL.'admin');
        exit;
        
    }
}