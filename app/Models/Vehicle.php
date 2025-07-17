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

    public function findAllByUser($driver_id): array|null
    {
        $query = "SELECT * FROM $this->table WHERE driver_id = :driver_id ;";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':driver_id' => $driver_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(empty($results)) {
            return null;
        }
        
        return $results;
    }

    public function isRegistrationIn(string $registration)
    {
        $query = "SELECT registration FROM $this->table WHERE registration = :registration ;";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['registration' => $registration]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(!empty($results)) {
            return true;
        }
        return false;        
    }    

    public function getIdByRegistration($registration)
    {
        $query = "SELECT id FROM $this->table WHERE registration = :registration;";
        $stmt = $this->db->prepare($query);
        
        $stmt->bindValue(':registration', $registration, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        
        return $results;        
    }

    public function createVehicle($data)
    {
        $db = $this->db;

        $query = "INSERT INTO $this->table (
            driver_id,
            brand,
            model,
            fuel,
            registration_date,
            registration,
            color,
            seats,
            smoking,
            animals,
            misc,
            creation_date
        ) VALUES (
            :driver_id,
            :brand,
            :model,
            :fuel,
            :registration_date,
            :registration,
            :color,
            :seats,
            :smoking,
            :animals,
            :misc,
            :creation_date
        )";

        $stmt = $db->prepare($query);

        // Debug
        if (!$stmt && DEBUG == true) {
            die("Error SQL Statement : " . implode(" | ", $db->errorInfo()));
        }

        try {
            $stmt->execute([
                'driver_id' => $data['driver_id'],
                'brand' => $data['brand'],
                'model' => $data['model'],
                'fuel' => $data['fuel'],
                'registration_date' => $data['registration_date'],
                'registration' => $data['registration'],
                'color' => $data['color'],
                'seats' => $data['seats'],
                'smoking' => $data['smoking'],
                'animals' => $data['animals'],
                'misc' => $data['misc'],
                'creation_date' => $data['creation_date'],
            ]);

            $data['id'] = $db->lastInsertId(); //no need to go look for Id -->A TESTER
            return $data;
        } catch (PDOException $e) {
            // DEBUG only
            if (DEBUG == true) {
                echo $e->getMessage(); 
            }
            return false;
        }
    }

     public function deleteVehicle($driver_id,  $vehicle_id)
    {
        //driver_id to be sure that the driver is deleting their own vehicle
        $query = "DELETE FROM $this->table WHERE (id = :id AND driver_id = :driver_id);";;
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $vehicle_id, PDO::PARAM_STR);
        $stmt->bindValue(':driver_id', $driver_id, PDO::PARAM_STR);
        $stmt->execute();
    }

    
}