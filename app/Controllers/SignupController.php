<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\User;

class SignupController extends Controller
{
    public function index(): void
    {
        // if already connected, send to dashboard
         if (isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'dashboard');
            exit;
        }

        $data = [
            'title' => "Créez votre compte Ecoride",
            'view' => "signup"
        ];        

        Controller::render($data['view'], $data);
    }

    public function register()
    {
        
        if (isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'dashboard');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $errors = [];
            $pseudo = trim($_POST['pseudo'] ?? '');
            $name = trim($_POST['name'] ?? '');
            $firstname = trim($_POST['firstname'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';
            
            // Validate data received (back-end)
            if (empty($pseudo)) {
                $errors['pseudo'] = "Pseudo requis.";
            } elseif (strlen($pseudo) < 3) {
                $errors['pseudo'] = "Le pseudo requiert au moins 3 caractères.";
            }
            if (empty($name)) {
                $errors['name'] = "nom requis.";
            } elseif (strlen($name) < 3) {
                $errors['name'] = "Le nom requiert au moins 3 caractères.";
            }
            if (empty($firstname)) {
                $errors['firstname'] = "prénom requis.";
            } elseif (strlen($pseudo) < 3) {
                $errors['firstname'] = "Le prénom requiert au moins 3 caractères.";
            }

            if (empty($email)) {
                $errors['email'] = "Email requis.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Adresse mail non valide.";
            }

            if (empty($password)) {
                $errors['password'] = "Mot de passe requis.";
            } elseif (!preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
                $errors['password'] = "Le mot de passe doit contenir au moins 9  caractères (au moins une majuscule et un chiffre).";
            }

            if ($password !== $confirm) {
                $errors['confirm'] = "Mots de passe différents.";
            }

            // In case of error -> back to signup view w/ messages
            if (!empty($errors)) {
                // Store  errors in session
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $_POST;

                header('Location: '.BASE_URL.'signup');
                exit;
            }

            // connect to db
            $userModel = new User(Database::getPDOInstance());

            // check if email already in db
            $existCheck = $userModel->isEmailIn(strtolower($email));
            if ($existCheck===true) {
                    $errors['email'] = "Cette adresse email est déjà utilisée.";
            

                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $_POST;
                header('Location: ' . BASE_URL . 'signup');
                exit;
            }

            // if not, hash the psw
            $hashedPassword = password_hash( $password, PASSWORD_BCRYPT);

            // insert in db
            $userModel = new User(Database::getPDOInstance());
            $userModel->create([
                'email' => $email,
                'password' => $hashedPassword,
                'pseudo' => $pseudo,
                'name' => $name,
                'firstname' => $firstname,
                'role' => 'user',
                'subscription_date' => date('Y-m-d H:i:s')
            ]);

            // creation of a (non session) cookie

            // user session
            $id = $userModel->getIdByEmail($email);
            $_SESSION['user'] = [
                'id' => $id['id'],
                'pseudo' => $pseudo,
                'email' => $email,
                'role' => 'user'
            ];

            // Redirect to dashboard
            header('Location: '.BASE_URL.'dashboard');
            exit;
        }
    }
}
