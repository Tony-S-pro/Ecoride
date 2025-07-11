<?php

namespace App\Models;

use App\Core\Database;  
use PDO;
use PDOException;

class Review extends Model
{
    protected string $table = 'reviews';

    protected string $view = 'view_driver_comments';


    public function findByDriver($driver_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->view WHERE driver_id = :driver_id");
        $stmt->execute(['driver_id' => $driver_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: null;
    }

    public function isReviewed($user_id, $carpool_id)
    {
        $query = "SELECT id FROM $this->table WHERE (user_id = :user_id AND carpool_id = :carpool_id) ;";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['user_id' => $user_id, 'carpool_id' => $carpool_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(!empty($results)) {
            return true;
        }
        return false;  
    }

    public function create_review($data )
    {
        $db = $this->db;



        $query = "INSERT INTO $this->table (
            user_id,
            carpool_id,
            rating,
            comment,
            validated,
            objection,
            creation_date
        ) VALUES (
            :user_id,
            :carpool_id,
            :rating,
            :comment,
            :validated,
            :objection,
            :creation_date
        )";

        $stmt = $db->prepare($query);

        // Debug
        if (!$stmt && DEBUG == true) {
            die("Error SQL Statement : " . implode(" | ", $db->errorInfo()));
        }

        try {
            $stmt->execute([
                'user_id' => $data['user_id'],
                'carpool_id' => $data['carpool_id'],
                'rating' => $data['rating'],
                'comment' => $data['comment'],
                'validated' => $data['validated'],
                'objection' => $data['objection'],
                'creation_date' => $data['creation_date']
            ]);

            $data['id'] = $db->lastInsertId(); //no need to go look for Id -->A TESTER

            
            
            return $data;
        } catch (PDOException $e) {
            // DEBUG only
            if (DEBUG == true) {
                echo $e->getMessage(); 
            }
            return false;
        }
    }




}