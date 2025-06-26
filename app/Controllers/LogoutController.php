<?php
namespace App\Controllers;

use App\Core\Controller;

class LogoutController extends Controller
{
    public function index(): void
    {
        session_unset();
        session_destroy();
        header('Location: '.BASE_URL.'login');
        exit;
    }
}