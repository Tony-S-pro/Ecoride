<?php
namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static $pdoInstance = null;
    private $pdo;

    private function __construct()
    {
        try {
            $this->pdo = new PDO(
                dsn: 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',
                username: DB_USER,
                password: DB_PASS,
                options: [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            error_log('[DATABASE ERROR] ' . $e->getMessage());
            exit('Une erreur est survenue. Merci de réessayer plus tard.');
        }
    }

    /* Singleton design (one class = one(unique) object). If We already have a pdo object, use it */
    public static function getPDOInstance()
    {
        if (self::$pdoInstance === null) {
            self::$pdoInstance = new Database();
        }
        return self::$pdoInstance->pdo;
    }
}