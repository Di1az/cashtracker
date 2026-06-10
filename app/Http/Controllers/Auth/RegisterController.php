<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

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
        $user = User::create($data);

        //Mandamos a llamar al evento
        event(new Registered($user));

        //Autenticamos el usuario, esto creará un cookie que la recuperamos en la ruta
        Auth::login($user);

        //Redirigimos al usuario
        return redirect()->route('verification.notice');

    }

}
