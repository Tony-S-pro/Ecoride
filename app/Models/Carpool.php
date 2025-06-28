<?php

namespace App\Models;

use App\Core\Database;  
use PDO;
use PDOException;

class Carpool extends Model
{
    protected string $table = 'carpools';

    public function findByCityDep_like($city)
    {
        $query = "SELECT * FROM $this->table WHERE departure_city LIKE :departure_city ;";
        $stmt = $this->db->prepare($query);
        
        //$stmt->execute(['email' => $email]);
        //binding the value is more secure than sending it directly
        $stmt->bindValue(':departure_city', $city, PDO::PARAM_STR);
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($results !== []) {
            return $results;
        }
        return null;
    }

    public function findByCity(?string $city1, ?string $city2, ?string $date)
    {
        $query = "SELECT * FROM $this->table WHERE ";

        if($city1!==null) {
            $query .="departure_city LIKE :departure_city AND ";
        }
        if($city2!==null) {
            $query .="arrival_city LIKE :arrival_city AND ";
        }
        if($date!==null) {
            $query .="departure_date = :departure_date AND ";
        }
        
        //$today = date("Y-m-d");
        //$query .="departure_date >= $today ";
        $query .="departure_date >= NOW() ";
        $query .= "ORDER BY departure_date ASC;";

        $stmt = $this->db->prepare($query);
        
        //$stmt->execute(['email' => $email]);
        //binding the value is more secure than sending it directly
        if($city1!==null) {
            $stmt->bindValue(':departure_city', $city1, PDO::PARAM_STR);
        }
        if($city2!==null) {
            $stmt->bindValue(':arrival_city', $city2, PDO::PARAM_STR);
        }
        if($date!==null) {
            $stmt->bindValue(':departure_date', $date, PDO::PARAM_STR);
        }
        
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($results !== []) {
            return $results;
        }

        //if no results, check closest date if date and at least one city are given
        $query = "SELECT * FROM $this->table WHERE ";

        if($city1!==null) {
            $query .="departure_city LIKE :departure_city AND ";
        }
        if($city2!==null) {
            $query .="arrival_city LIKE :arrival_city AND ";
        }     
        $query .= "departure_date >= NOW() ";
        $query .= "ORDER BY ABS(DATEDIFF(:departure_date, departure_date)) ASC LIMIT 2;"; // limit 2 in case one before/one after ?

        $stmt = $this->db->prepare($query);

        if($city1!==null) {
        $stmt->bindValue(':departure_city', $city1, PDO::PARAM_STR);
        }
        if($city2!==null) {
            $stmt->bindValue(':arrival_city', $city2, PDO::PARAM_STR);
        }
        if($date!==null) {
            $stmt->bindValue(':departure_date', $date, PDO::PARAM_STR);
        }

        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($results !== []) {
            return $results;
        }
        
        return null;
    }

    
}