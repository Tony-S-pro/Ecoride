<?php

namespace App\Models;

use App\Core\Database;  
use PDO;
use PDOException;

class Carpool extends Model
{
    protected string $table = 'carpools';
    protected string $view = 'view_carpools_full';

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

    public function findByCity_lite(?string $city1, ?string $city2, ?string $date)
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
        $query .="departure_date >= NOW()"; //note: carpools already full are still returned on homepage
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
        $query .= "ORDER BY ABS(DATEDIFF(:departure_date, departure_date)) ASC LIMIT 1;"; // limit 2 in case one before/one after ?

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

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC); //keep fetchAll even with limit 1 -> compatible with js

        if ($results !== []) {
            return $results;
        }
        
        return null;
    }

    public function findByCity(?string $city1, ?string $city2, ?string $date, ?string $address1, ?string $address2, ?string $checkEco)
    {
        $query = "SELECT * FROM $this->view WHERE ";

        if($city1!==null) {
            $query .="departure_city LIKE :departure_city AND ";
        }
        if($city2!==null) {
            $query .="arrival_city LIKE :arrival_city AND ";
        }
        if($address1!==null) {
            $query .="departure_address LIKE :departure_address AND ";
        }
        if($address2!==null) {
            $query .="arrival_address LIKE :arrival_address AND ";
        }
        if($checkEco=='check') {
            $query .="fuel = 'electrique' AND ";
        }
        if($date!==null) {
            $query .="departure_date = :departure_date AND ";
        }
        
        $query .="departure_date >= NOW() AND remaining_seats > 0 ";
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
        if($address1!==null) {
            $stmt->bindValue(':departure_address', $address1, PDO::PARAM_STR);
        }
        if($address2!==null) {
            $stmt->bindValue(':arrival_address', $address2, PDO::PARAM_STR);
        }
        if($date!==null) {
            $stmt->bindValue(':departure_date', $date, PDO::PARAM_STR);
        }
        
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($results !== []) {
            return $results;
        }

        //if no results, check closest date
        $query = "SELECT * FROM $this->view WHERE ";

        if($city1!==null) {
            $query .="departure_city LIKE :departure_city AND ";
        }
        if($city2!==null) {
            $query .="arrival_city LIKE :arrival_city AND ";
        }
        if($address1!==null) {
            $query .="departure_address LIKE :departure_address AND ";
        }
        if($address2!==null) {
            $query .="arrival_address LIKE :arrival_address AND ";
        }
        if($checkEco!==null) {
            $query .="fuel = 'electrique' AND ";
        }
        $query .= "departure_date >= NOW() AND remaining_seats > 0 ";
        $query .= "ORDER BY ABS(DATEDIFF(:departure_date, departure_date)) ASC LIMIT 1;"; // limit 2 in case one before/one after ?

        $stmt = $this->db->prepare($query);

        if($city1!==null) {
            $stmt->bindValue(':departure_city', $city1, PDO::PARAM_STR);
        }
        if($city2!==null) {
            $stmt->bindValue(':arrival_city', $city2, PDO::PARAM_STR);
        }
        if($address1!==null) {
            $stmt->bindValue(':departure_address', $address1, PDO::PARAM_STR);
        }
        if($address2!==null) {
            $stmt->bindValue(':arrival_address', $address2, PDO::PARAM_STR);
        }
        if($date!==null) {
            $stmt->bindValue(':departure_date', $date, PDO::PARAM_STR);
        }

        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC); //keep fetchAll even with limit 1 -> compatible with js

        if ($results !== []) {
            return $results;
        }
        
        return null;
    }

    
}