<?php

namespace App\Models;

use App\Core\Database;  
use PDO;
use PDOException;

class View_carpools_status extends Model
{
    protected string $table = 'View_carpools_status';

    public function findByUser_past(string|int $user_id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_id = :user_id AND (status = 'termine' OR status = 'valide' );");
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: null;
    }

    public function findByUser_planned(string|int $user_id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_id = :user_id AND (status = 'planifie' OR status = 'en_cours');");
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: null;
    }
    
}