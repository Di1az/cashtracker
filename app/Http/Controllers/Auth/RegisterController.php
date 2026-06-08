<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignupRequest;
use App\Models\User;

class RegisterController extends Controller
{
    //Existen reglas para nombrar los métodos del controller

    public function index()
    {
        return view('auth.register');
    }

    //Cuando un usuario llena un formulario y lo envia, se tiene que mandar a llamar el metodo store
    public function store(SignupRequest $request)
    {
        $data = $request->validated();

        //insert into usuarios... 
        User::create($data);

    }

}
