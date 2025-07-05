<?php

namespace App\Models;

use App\Core\Database;  
use PDO;
use PDOException;

class User_Carpool extends Model
{
    protected string $table = 'user_carpool';

    public function bookSeat($user_id, $carpool_id)
    {
        $query = "INSERT INTO $this->table (user_id, carpool_id) VALUES (:user_id, :carpool_id); ";
        $stmt = $this->db->prepare($query);
        
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->bindValue(':carpool_id', $carpool_id, PDO::PARAM_STR);
        $stmt->execute();
    }
    
}