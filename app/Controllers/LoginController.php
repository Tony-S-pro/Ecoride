<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\User;
use App\Models\Vehicle;

class LoginController extends Controller
{
    public function index(): void
    {
        //check if user is connected
         if (isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'dashboard');
            exit;
        }

        $errors = $_SESSION['login_errors'] ?? [];
        unset($_SESSION['login_errors']);

        $data = [
            'title' => "Connectez-vous à votre compte Ecoride",
            'view' => "login",
            'errors' => $errors
        ];        

        Controller::render($data['view'], $data);
    }

    public function login(): void
    {
        //check if user is connected
         if (isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'dashboard');
            exit;
        }

        $errors = [];

        // Check if form was sent + clean
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // check CRSF token
            if(!Controller::is_csrf_valid()) {
                exit("Error - CSRF token invalid");
            }

            // clean
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            // Validate fields
            if (empty($email) || empty($password)) {
                $errors[] = "Adresse email et/ou mot de passe requis.";
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Adresse email non valide.";
            }

            // If no error then connnect to db
            if (empty($errors)) {
                $userModel = new User(Database::getPDOInstance());
                $user = $userModel->findByEmail($email);

                if ($user && password_verify($password, $user['password'])) {
                    // only usefull data for other pages is stored in session (i.e. no psw, etc)
                    $vehicle = new Vehicle(Database::getPDOInstance());
                    $cars = $vehicle->findAllIdByUser($user['id']);
                    
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'email' => $user['email'],
                        'pseudo' => $user['pseudo'],
                        'role' => $user['role'],
                        'vehicles' => $cars
                    ];
                    header('Location: '.BASE_URL.'dashboard');
                    exit;
                } else {
                    //hash password to have the same-ish delay when email doesn't match
                    password_hash($password, PASSWORD_BCRYPT);
                    $errors[] = "Identifiants incorrects.";
                }
            }
        }

        if (!empty($errors)) {
            $_SESSION['login_errors'] = $errors;
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }
}