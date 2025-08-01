<?php
namespace App\Core;

class Controller
{
    public static function render(string $viewPath, array $data = []): void
    {
        extract($data); // ex: $title, $content
        
        // send output generated by script to a buffer (to validate, modify, etc + increased performances)
        ob_start();
        require __DIR__ . '/../Views/' . $viewPath . '.php';
        $content = ob_get_clean(); // get output and clean buffer
        require __DIR__ . '/../Views/layout.php';
    }

    /**
     * Intégrer un token csrf dans un champ caché.
     * 
     * On génère une chaine ascii aléatoire convertie en hexadecimal (plus lisible),
     * on la charge dans la variable $_SESSION et on l'intègre à la page dans un champ caché.
     * 
     * @return void
     */
    public static function set_csrf() : void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION["csrf"])) {
            $_SESSION["csrf"] = bin2hex(random_bytes(32));
        }
        echo '<input type="hidden" name="csrf" value="' . $_SESSION["csrf"] . '">';
        
    }

    /**
     * Vérifier si le token csrf est valide.
     * 
     * On vérifie si le token reçu (en POST, on présume) est equivalent au token de session.
     * 
     * @return bool retourne true si le token du POST est equivalent au token SESSION
     */
    public static function is_csrf_valid(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['csrf']) || !isset($_POST['csrf'])) {
            return false;
        } 
        if ($_SESSION['csrf'] != $_POST['csrf']) {
            return false;
        } 
        return true;
    }
}
