<?php

namespace App\Models;

use App\Core\Database;  
use PDO;
use PDOException;

class View_driver_comments extends Model
{
    protected string $table = 'view_driver_comments';

    public function findByDriver($driver_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE driver_id = :driver_id");
        $stmt->execute(['driver_id' => $driver_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: null;
    }
    
}