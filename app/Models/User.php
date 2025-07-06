<?php

namespace App\Models;

use App\Core\Database;  
use PDO;
use PDOException;

class User extends Model
{
    protected string $table = 'users';

    public function isEmailIn($email)
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
            subscription_date
        ) VALUES (
            :email,
            :password,
            :pseudo,
            :firstname,
            :name,
            :role,
            :subscription_date
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
                'subscription_date' => $data['inscription_date']
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

    public function findByEmail($email)
    {
        $query = "SELECT * FROM $this->table WHERE email = :email ;";
        $stmt = $this->db->prepare($query);
        
        //$stmt->execute(['email' => $email]);
        //binding the value is more secure than sending it directly
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        $results = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return $results;        
    }

    public function getCreds($user_id)
    {
        $query = "SELECT credit FROM $this->table WHERE id = :id;";
        $stmt = $this->db->prepare($query);
        
        $stmt->bindValue(':id', $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        
        return $results;
        
    }

    public function takeCreds($user_id, $price)
    {
        $query = "UPDATE $this->table SET credit = credit - :credit WHERE id = :id;";
        $stmt = $this->db->prepare($query);
        
        $stmt->bindValue(':credit', $price, PDO::PARAM_STR);
        $stmt->bindValue(':id', $user_id, PDO::PARAM_STR);
        $stmt->execute();        
    }

    
}