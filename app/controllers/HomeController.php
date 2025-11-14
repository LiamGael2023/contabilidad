<?php

namespace Controllers;

use Core\Controller;
use Core\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Si ya está autenticado, redirigir al dashboard
        if (isset($_SESSION['user_id'])) {
            $this->redirect('dashboard');
        }

        // Redirigir al login
        $this->redirect('login');
    }
}
