<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class SignupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    //Método de la clase que puedes reescribir para los mensajes de error
    public function messages(): array
    {
        return [
            'name.required' => 'El Nombre es obligatorio',
            'email.required' => 'El Email es obligatiorio',
            'email.email' => 'Email no válido',
            'email.unique' => 'Este correo ya esta registrado',
            'password.required' => 'El Password es obligatiorio',
            'password.confirmed' => 'Las Contrasaeñas no coinciden',
            'password.min' => 'La Contraseña debe contener al menos :min caractéres',
            'password.letters' => 'La Contraseña debe tener al menos 1 letra',
            'password.mixed' => 'La Contraseña debe tener al menos una letra mayúscula y una letra minúscula',
            'password.symbols' => 'LaContraseña debe tener al menos una caractér especial',
            'password.numbers' => 'La Contraseña debe tener al menos un número',
            'password.uncompromised' => 'La Contraseña ha aparecido en filtraciones de datos. Elige un más segura'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 
                Password::min(8)
                    // ->letters()
                    // ->mixedCase()
                    // ->symbols()
                    // ->numbers()
                    // ->uncompromised()
                ]
        ];
    }
}
