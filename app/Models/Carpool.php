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

    public function where_like(
        string $col_name,
        mixed $col_value, 
        string $order_col=null, 
        string $order_type=null,
        int $limit=null,
        int $limit_offset=null): array|bool 
    {
        $query = "SELECT * FROM $this->table WHERE ";

        $query .= $col_name." LIKE :".$col_name;
        
        if($order_col !== null){
            if($order_type === null) {
                $order_type = 'ASC';
            }
            $query .= " ORDER BY $order_col $order_type";
        }
        if($limit !== null){
            if($limit_offset === null) {
                $limit_offset = 0;
            }
            $query .= " LIMIT $limit OFFSET $limit_offset";
        }
        $query .= ";";

        $stmt = $this->db->prepare($query);
        
        //$stmt->execute(['email' => $email]);
        //binding the value is more secure than sending it directly
        $stmt->bindValue(':'.$col_name, $col_value);
        $stmt->execute();
        
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $results; //json_encode + echo to return to client (via jquery)?
        //turn to str before ? date format
    }
}