<?php

namespace App\Models;

use App\Core\Database;  
use PDO;
use PDOException;

class Carpool extends Model
{
    protected string $table = 'carpools';
    protected string $view = 'view_carpools_full';

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
            foreach($results as &$r) {
                $r['departure_date'] = date('d/m/y', strtotime($r['departure_date']));
                $r['departure_time'] = substr($r['departure_time'],0,-3);
                
            }
            return $results;
        }

        //if no results, check closest date if date and at least one city are given

        if($date!==null) {
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
                foreach($results as &$r) {
                    $r['departure_date'] = date('d/m/y', strtotime($r['departure_date']));
                    $r['departure_time'] = substr($r['departure_time'],0,-3);
                    
                }
                return $results;
            }
        }
        return null;
    }

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
        $query = "SELECT * FROM $this->view WHERE ";
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

        $query .= "remaining_seats > 0 AND departure_date >= NOW() ";
        $query .= "ORDER BY departure_date ASC;";

        $stmt = $this->db->prepare($query);            
        
        $stmt->execute($arr);        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        //reformat dates/hours
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

            $query .= "departure_date >= NOW() AND remaining_seats > 0 ";
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

    public function findCarpoolsAndCredits()
    {
        $results_final = []; // array to return json to charts.js -> [0 => [xVal, yVal], 1 => [xVal, yVal]]
        
        //create a date array, to be able to return every dates, including value=0, w/o making an ugly view
        $date_arr=[];
        for ($i=5; $i > 0; $i--) {
            array_push($date_arr, date('Y-m-d', strtotime("-$i days")));                     
        }

        foreach ($date_arr as $date) {
            
            $query = "SELECT 
                (SELECT COUNT(*) FROM carpools WHERE (status = 'en_cours' OR status = 'termine' OR status = 'valide') AND departure_date = :date) AS carpools_nb,
                (SELECT COUNT(*) FROM carpools WHERE (status = 'valide') AND departure_date = :date2) AS valid_nb,
                (SELECT valid_nb*2) as credits_nb;";

            $stmt = $this->db->prepare($query);
            //needs both even with same marker name 
            //"You cannot use a named parameter marker of the same name more than once in a prepared statement, unless emulation mode is on."
            $stmt->bindValue(':date', $date, PDO::PARAM_STR);
            $stmt->bindValue(':date2', $date, PDO::PARAM_STR); 
            $stmt->execute();
            $results = $stmt->fetch(PDO::FETCH_ASSOC);
            //don't forget to turn yyyy/mm/dd into dd/mm
            array_push($results_final, ['xVal'=> date('d/m', strtotime($date)), 'yVal'=> $results['carpools_nb'], 'y2Val'=> $results['credits_nb']]);                                       
        }

        return $results_final;
    }

    public function getPrice($carpool_id)
    {
        $query = "SELECT price FROM $this->table WHERE id = :id;";
        $stmt = $this->db->prepare($query);
        
        $stmt->bindValue(':id', $carpool_id, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        return $results;
    }

    public function isSeatAvailable($carpool_id)
    {
        $query = "SELECT remaining_seats FROM $this->view WHERE id = :id;";
        $stmt = $this->db->prepare($query);
        
        $stmt->bindValue(':id', $carpool_id, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        if($results['remaining_seats'] > 0) {
            return true;
        }        
        return false;
    }

    public function findIdByDriver_past(string|int $driver_id): ?array
    {
        $stmt = $this->db->prepare("SELECT id FROM {$this->table} WHERE driver_id = :driver_id AND (status = 'termine' OR status = 'valide' );");
        $stmt->execute([':driver_id' => $driver_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: null;
    }

    public function findIdByDriver_planned(string|int $driver_id): ?array
    {
        $stmt = $this->db->prepare("SELECT id FROM {$this->table} WHERE driver_id = :driver_id AND (status = 'planifie' OR status = 'en_cours');");
        $stmt->execute([':driver_id' => $driver_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: null;
    }

    public function cancelCarpool($driver_id,  $carpool_id)
    {
        //driver_id to be sure that the driver is deleting their own carpool
        $query = "DELETE FROM $this->table WHERE (id = :id AND driver_id = :driver_id);";;
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $carpool_id, PDO::PARAM_STR);
        $stmt->bindValue(':driver_id', $driver_id, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function startTrip($driver_id,  $carpool_id)
    {
        //driver_id to be sure that the driver is updating their own carpool
        $query = "UPDATE $this->table SET status = 'en_cours' WHERE (id = :id AND driver_id = :driver_id);";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $carpool_id, PDO::PARAM_STR);
        $stmt->bindValue(':driver_id', $driver_id, PDO::PARAM_STR);
        $stmt->execute();    
    }

    public function endTrip($driver_id,  $carpool_id)
    {
        //driver_id to be sure that the driver is updating their own carpool
        $query = "UPDATE $this->table SET status = 'termine' WHERE (id = :id AND driver_id = :driver_id);";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $carpool_id, PDO::PARAM_STR);
        $stmt->bindValue(':driver_id', $driver_id, PDO::PARAM_STR);
        $stmt->execute();
    }
    
}