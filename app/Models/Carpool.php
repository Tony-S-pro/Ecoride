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
                return $results;
            }
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
        if($checkEco!==null) {
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

        if($date!==null) {
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
        }
        
        return null;
    }

    public function findCarpoolsNb()
    {
        $results_final = []; // array to return json to charts.js -> [0 => [xVal, yVal], 1 => [xVal, yVal]]
        
        //create a date array, to be able to return every dates, including value=0, w/o making an ugly view
        $date_arr=[];
        for ($i=5; $i > 0; $i--) {
            array_push($date_arr, date('Y-m-d', strtotime("-$i days")));                     
        }

        foreach ($date_arr as $date) {
            /*
            $query = "SELECT 
                COUNT(*) AS carpools_nb
                FROM carpools
                WHERE (status = 'en_cours' OR status = 'termine' OR status = 'valide') 
                AND departure_date = :date;";
            */
            
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
            //don't forget to turn yyyy/mm/dd into dd/mm/yy
            array_push($results_final, ['xVal'=> date('d/m', strtotime($date)), 'yVal'=> $results['carpools_nb'], 'y2Val'=> $results['credits_nb']]);                                       
        }

        return $results_final;
    }
    
}