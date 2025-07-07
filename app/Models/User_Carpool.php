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

    public function cancelBooking($user_id, $carpool_id)
    {
        $query = "DELETE FROM $this->table WHERE (user_id = :user_id AND carpool_id = :carpool_id);";
        $stmt = $this->db->prepare($query);
        
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->bindValue(':carpool_id', $carpool_id, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function findCarpoolsByUser($user_id): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: null;
    }

    public function findUsersByCarpool($carpool_id): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE carpool_id = :carpool_id");
        $stmt->execute([':carpool_id' => $carpool_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: null;
    }
    
}