<?php

namespace App\Models;

use App\Core\Database;  
use PDO;
use PDOException;

class View_carpool_full extends Model
{
    protected string $table = 'view_carpools_full';

    public function findByCity(
        ?string $city1=null, 
        ?string $city2=null, 
        ?string $date=null, 
        ?string $address1=null, 
        ?string $address2=null,
        ?string $minRating=null,
        ?string $maxPrice=null,
        ?string $maxTime=null, 
        ?string $checkEco=null
        )
    {
        $query = "SELECT * FROM $this->table WHERE status = 'planifie' AND ";
        $arr = [];

        if(!empty($city1)) {
            $query .="departure_city LIKE :departure_city AND ";
            $arr += [':departure_city' => $city1];
        }
        if(!empty($address1)) {
            $query .="departure_address LIKE :departure_address AND ";
            $arr += [':departure_address' => $address1];
        }
        if(!empty($city2)) {
            $query .="arrival_city LIKE :arrival_city AND ";
            $arr += [':arrival_city' => $city2];
        }
        if(!empty($address2)) {
            $query .="arrival_address LIKE :arrival_address AND ";
            $arr += [':arrival_address' => $address2];
        }
        if(!empty($minRating)) {
            $query .="avg_rating >= :avg_rating AND ";
            $arr += [':avg_rating' => $minRating];
        }
        if(!empty($maxPrice)) {
            $query .="price <= :price AND ";
            $arr += [':price' => $maxPrice];
        }
        if(!empty($maxTime)) {
            $query .="travel_time <= :travel_time AND ";
            $arr += [':travel_time' => $maxTime];
        }
        if(!empty($checkEco)) {
            $query .="fuel = 'electrique' AND ";
        }

        $query_before_date = $query;

        if(!empty($date)) {
            $query .="departure_date = :departure_date AND ";
            $arr += [':departure_date' => $date];
        }

        $query .= "remaining_seats > 0 AND departure_date >= DATE_FORMAT(NOW(),'%Y-%m-%d') ";
        $query .= "ORDER BY departure_date ASC;";

        $stmt = $this->db->prepare($query);            
        
        $stmt->execute($arr);        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($results !== []) {
            foreach($results as &$r) {
                $r['departure_date'] = date('d/m/y', strtotime($r['departure_date']));
                $r['departure_time'] = substr($r['departure_time'],0,-3);
                
            }
            return $results;
        }

        //if no results for a date (if it was given), check closest date
        if(!empty($date)) {
            $query = $query_before_date;

            $query .= "departure_date >= DATE_FORMAT(NOW(),'%Y-%m-%d') AND remaining_seats > 0 ";
            $query .= "ORDER BY ABS(DATEDIFF(:departure_date, departure_date)) ASC LIMIT 1;"; // limit 2 in case one before/one after ?

            $stmt = $this->db->prepare($query);

            $stmt->execute($arr);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC); //keep fetchAll even with limit 1 -> compatible with js

            if ($results !== []) {
                foreach($results as &$r) {
                $r['departure_date'] = date('d/m/y', strtotime($r['departure_date']));                
                } 
                return $results;
            }
        }        
        return null;
    }

    public function isSeatAvailable($carpool_id)
    {
        $query = "SELECT remaining_seats FROM $this->table WHERE id = :id;";
        $stmt = $this->db->prepare($query);
        
        $stmt->bindValue(':id', $carpool_id, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        if($results['remaining_seats'] > 0) {
            return true;
        }        
        return false;
    }
    
}