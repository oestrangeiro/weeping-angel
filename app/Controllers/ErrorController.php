<?php

namespace App\Controllers;

// Controler responsável pelo redirecionamento de errors para o usuário

class ErrorController extends BaseController {
    public function permissionDenied(){

        return  view('includes/header') . 
                view('errors/access-denied/access_denied.php') .
                view('includes/footer');
    }
}