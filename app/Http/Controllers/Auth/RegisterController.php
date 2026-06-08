<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    //Existen reglas para nombrar los métodos del controller

    public function index()
    {
        return view('auth.register');
    }

}
