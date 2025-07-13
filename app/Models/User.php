<?php

namespace App\Models;

use App\Core\Database;  
use PDO;
use PDOException;

class User extends Model
{
    protected string $table = 'users';

    public function isEmailIn($email): bool
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

    public function getIdByEmail($user_email)
    {
        $query = "SELECT id FROM $this->table WHERE email = :email;";
        $stmt = $this->db->prepare($query);
        
        $stmt->bindValue(':email', $user_email, PDO::PARAM_STR);
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

    public function giveCreds($user_id, $price)
    {
        $query = "UPDATE $this->table SET credit = credit + :credit WHERE id = :id;";
        $stmt = $this->db->prepare($query);
        
        $stmt->bindValue(':credit', $price, PDO::PARAM_STR);
        $stmt->bindValue(':id', $user_id, PDO::PARAM_STR);
        $stmt->execute();        
    }

    public function getEmail($user_id)
    {
        $query = "SELECT email FROM $this->table WHERE id = :id;";
        $stmt = $this->db->prepare($query);
        
        $stmt->bindValue(':id', $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        
        return $results;        
    }

    public function uploadPhotoPath($file_name, $user_id)
    {
        $query = "UPDATE $this->table SET photo = :photo WHERE id = :id;";
        $stmt = $this->db->prepare($query);
        
        $stmt->bindValue(':photo', $file_name, PDO::PARAM_STR);
        $stmt->bindValue(':id', $user_id, PDO::PARAM_STR);
        $stmt->execute(); 
    }

    public function findPhotoById($user_id): array|null
    {
        $query = "SELECT photo FROM $this->table WHERE id = :id ;";
        $stmt = $this->db->prepare($query);
        
        $stmt->bindValue(':id', $user_id, PDO::PARAM_STR);
        $stmt->execute();
        
        $results = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return $results;        
    }

    public function findUsers(?string $id, ?string $pseudo, ?string $email, ?string $role = 'user')
    {
        $query = "SELECT * FROM $this->table WHERE role = :role AND ";
        
        if($id!==null) {
            $query .="id = :id AND ";
        }
        if($pseudo!==null) {
            $query .="pseudo LIKE :pseudo AND ";
        }
        if($email!==null) {
            $query .="email LIKE :email AND ";
        }

        $query = trim($query, "AND ");
        $query .= " ORDER BY id ASC;";

        $stmt = $this->db->prepare($query);
        
        if($role!==null) {
            $stmt->bindValue(':role', $role, PDO::PARAM_STR);
        }
        if($id!==null) {
            $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        }
        if($pseudo!==null) {
            $stmt->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
        }
        if($email!==null) {
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        }
        
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

       return $results;
    }

    /**
     * Set the user's suspended status
     * @param mixed $user_id
     * @param bool $suspended true:set to 1, false: set to 0
     * @return void
     */
    public function setUserSuspended($user_id, bool $suspended)
    {
        $sus = $suspended ? 1 : 0;

        $query = "UPDATE $this->table SET suspended = $sus WHERE id = :id;";
        $stmt = $this->db->prepare($query);
        
        $stmt->bindValue(':id', $user_id, PDO::PARAM_STR);
        $stmt->execute();  
    }



    
}