<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Review;

class EmployeeController extends Controller
{
    public function index(): void
    {
        // check if user's connected and has employee role/id        
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL.'login');
            exit;
        }
        if(!$_SESSION['user']['role']==='employee' OR !in_array($_SESSION['user']['id'], EMPLOYEES_ID)) {
            header('Location: '.BASE_URL.'signup');
            exit;
        }

        //get reviews (comments)
        $reviewModel = new Review(Database::getPDOInstance());
        $results_review = $reviewModel->findNoValidId(FALSE);

        //get reviews data (comments)
        if(empty($results_review)) {
            $comments_data = null;
        }else {
            foreach ($results_review as $id) {
                $results = $reviewModel->getNoValidData($id['id']);
                $results['creation_date'] = date('d-m-y', strtotime($results['creation_date']));
                $comments_data[] = $results;
            }
        }

        //get reviews(objections)
        $results_review = $reviewModel->findNoValidId(TRUE);

        //get reviews data (objections)
        if(empty($results_review)) {
            $objections_data = null;
        }else {
            foreach ($results_review as $id) {
                $results = $reviewModel->getNoValidData($id['id']);
                $results['creation_date'] = date('d-m-y', strtotime($results['creation_date']));
                $objections_data[] = $results;
            }
        }

        $data = [
            'title' => "Dashboard employé",
            'view' => "employee",
            'comments' => $comments_data,
            'objections' => $objections_data
        ];        

        Controller::render($data['view'], $data);
    }
}