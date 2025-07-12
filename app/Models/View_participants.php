<?php

namespace App\Models;

use App\Core\Database;  
use PDO;
use PDOException;

class View_participants extends Model
{
    protected string $table = 'View_participants';

    /**
     * Count passenger reservations in a carpool
     * 
     * @param mixed $carpool_id
     * @return int 0 if none
     */
    public function countPassengers($carpool_id): int
    {
        $stmt = $this->db->prepare("SELECT seats_taken FROM $this->table WHERE carpool_id = :carpool_id;");
        $stmt->execute([':carpool_id' => $carpool_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        if(empty($result)) {
            return 0;
        }else {
            return $result['seats_taken'];
        }
    }
    
}