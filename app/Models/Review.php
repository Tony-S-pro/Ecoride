<?php

namespace App\Models;

use App\Core\Database;  
use PDO;
use PDOException;

class Review extends Model
{
    protected string $table = 'reviews';

    protected string $view = 'view_driver_comments';


    public function findByDriver($driver_id): array|null
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->view WHERE driver_id = :driver_id");
        $stmt->execute(['driver_id' => $driver_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: null;
    }

    public function isReviewed($user_id, $carpool_id): bool
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

    /**
     * Find all non validated reviews id by their objection state
     * 
     * @param bool $objection FALSE: not an objection (default), TRUE: an objection
     * @return array|null an array of id (most recent first) or null if no reviews
     */
    public function findNoValidId(bool $objection = FALSE): array|null
    {
        ($objection === FALSE) ? $obj=0 : $obj=1;
        $query = "SELECT id FROM $this->table WHERE (validated = 0 AND objection = :objection) ORDER BY creation_date DESC;";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['objection' => $obj]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Get the data associated to a review id
     * 
     * Includes the email address of both passenger and driver
     * @param mixed $review_id
     * @return array|null
     */
    public function getNoValidData($review_id): array|null
    {
        $query = "SELECT 
        r.id,
        r.carpool_id,
        r.user_id AS passenger_id,
        u_passenger.email AS passenger_email,
        u_passenger.pseudo AS passenger_pseudo,
        c.driver_id,
        u_driver.email AS driver_email,
        u_driver.pseudo AS driver_pseudo,
        r.rating,
        r.comment,
        r.objection,
        r.creation_date
        
        FROM $this->table r
        JOIN carpools c ON r.carpool_id = c.id
        LEFT JOIN users u_driver ON c.driver_id = u_driver.id
        LEFT JOIN users u_passenger ON r.user_id = u_passenger.id
        WHERE r.id = :id;";

        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $review_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }


    public function validate($review_id)
    {
        $query = "UPDATE $this->table SET validated = 1 WHERE id = :id;";
        $stmt = $this->db->prepare($query);
        
        $stmt->bindValue(':id', $review_id, PDO::PARAM_STR);
        $stmt->execute();     
    }

    public function remove_comment($review_id)
    {
        $query = "UPDATE $this->table SET comment = NULL WHERE id = :id;";
        $stmt = $this->db->prepare($query);        
        $stmt->bindValue(':id', $review_id, PDO::PARAM_STR);
        $stmt->execute();     
    }

    public function countByCarpoolId ($carpool_id)
    {        
        $query = "SELECT COUNT(*) AS reviews_nb FROM $this->table WHERE carpool_id = :carpool_id ;";
        $stmt = $this->db->prepare($query);        
        $stmt->bindValue(':carpool_id', $carpool_id, PDO::PARAM_STR);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
        
    }

    public function findCarpoolId($review_id)
    {
        $stmt = $this->db->prepare("SELECT carpool_id FROM $this->table WHERE id = :id");
        $stmt->execute(['id' => $review_id]);
        $results = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        return $results;
    }

    public function findUserId($review_id)
    {
        $stmt = $this->db->prepare("SELECT user_id FROM $this->table WHERE id = :id");
        $stmt->execute(['id' => $review_id]);
        $results = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        return $results;
    }






}