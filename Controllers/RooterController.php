<?php

namespace App\Controllers;

class RooterController extends Controller
{

    public function index()
    {
        $this->render('main/index');
        exit;
    }
}
