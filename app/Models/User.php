<?php

namespace App\Models;

use App\Core\Database;  
use PDO;
use PDOException;

class User extends Model
{
    protected string $table = 'users';

    public function is_mail_in_db($email)
    {
        $query = "SELECT email FROM users WHERE email = :email ;";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['email' => $email]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(!empty($results)) {
            return true;
        }
        return false;        
    }


    public function create($data)
    {
        $db = $this->db;

        $query = "INSERT INTO users (
            email,
            password,
            pseudo,
            firstname,
            name,
            role,
            inscription_date
        ) VALUES (
            :email,
            :password,
            :pseudo,
            :firstname,
            :name,
            :role,
            :inscription_date
        )";

        $stmt = $db->prepare($query);

        // Debug
        if (!$stmt && DEBUG == true) {
            die("Error SQL Statement : " . implode(" | ", $db->errorInfo()));
        }

        try {
            $stmt->execute([
                'email' => $data['email'],
                'password' => $data['password'],
                'pseudo' => $data['pseudo'],
                'firstname' => $data['firstname'],
                'name' => $data['name'],
                'role' => $data['role'],
                'inscription_date' => $data['inscription_date']
            ]);

            $data['id'] = $db->lastInsertId();
            return $data;
        } catch (PDOException $e) {
            // DEBUG only
            if (DEBUG == true) {
                echo $e->getMessage(); 
            }
            return false;
        }
    }
}