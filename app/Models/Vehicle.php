<?php

namespace App\Models;

use App\Core\Database;  
use PDO;
use PDOException;

class Vehicle extends Model
{
    protected string $table = 'vehicles';

    public function findAllIdByUser($driver_id): array|null
    {
        $query = "SELECT id FROM $this->table WHERE driver_id = :driver_id ;";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':driver_id' => $driver_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(empty($results)) {
            return null;
        }
        
        $arr=[];
        foreach ($results as $r) {
            $arr[]=$r['id'];
        }
        return $arr;
    }

    public function createCar($data)//??????????????????????????????????????????????????????
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

    
}