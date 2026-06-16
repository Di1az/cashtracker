<?php

use App\Models\User;
use App\Notifications\VerifyEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

//Para ejecutar las migraciones
uses(RefreshDatabase::class);

test('shows the registration screen', function() {
    $response = $this->get(route('register'));

    //para saber si la página existe
    $response->assertStatus(200);
    //tiene que ver algo, si lo ve pasa la prueba (es key sensitive)
    $response->assertSee('Crear Cuenta');
    $response->assertSee('Registrarme');

    //Otra forma (el orden importa)
    // $response->assertSeeInOrder([
    //     'Crear Cuenta',
    //     'Registrarme'
    // ]);
});

test('registers a new user as unverified and dispatches the registered event', function() {

    //Simula el evento
    Event::fake();

    //Enviamos los datos al store
    $response = $this->post(route('register.store', [
        'name' => 'Kai Havertz',
        'email' => 'khaveertz@gmail.com',
        'password' => 'worldcup71',
        'password_confirmation' => 'worldcup71'
    ]));

    //Nos lleva a la ruta
    $response->assertRedirect(route('verification.notice'));

    //Hacemos una consulta para traernos ese registro
    $user = User::where('email', 'khaveertz@gmail.com')->first();

    //Nos aseguramos de que exista el registro, que los datos coincidan y que aún no este verificada la cuenta
    expect($user)->not()->toBeNull();
    expect($user->name)->toBe('Kai Havertz');
    expect($user->email)->toBe('khaveertz@gmail.com');
    expect($user->hasVerifiedEmail())->toBeFalse();

    Event::assertDispatched(Registered::class);

});

test('should validate required fields when the request body is empty', function() {

    $response = $this->post(route('register.store', [
        'name' => '',
        'email' => '',
        'password' => '',
        'password_confirmation' => ''
    ]));

    $response->assertSessionHasErrors([
        'name' => 'El Nombre es obligatorio', 
        'email' => 'El Email es obligatiorio',
        'password'=> 'El Password es obligatiorio'
    ]);

});

test('prevents duplicate email addresses', function() {

    User::factory()->create([
        'email' => 'khaveertz@gmail.com'
    ]);

    $response = $this->post(route('register.store', [
        'name' => 'Kai Havertz',
        'email' => 'khaveertz@gmail.com',
        'password' => 'worldcup71',
        'password_confirmation' => 'worldcup71'
    ]));

    $response->assertRedirect();

    $response->assertSessionHasErrors([
        'email' => 'Este correo ya esta registrado'
    ]);

});

test('sends the verification email notification after registration', function() {
    
    Notification::fake();

    $response = $this->post(route('register.store', [
        'name' => 'Kai Havertz',
        'email' => 'khaveertz@gmail.com',
        'password' => 'worldcup71',
        'password_confirmation' => 'worldcup71'
    ]));

    $user = User::where('email', 'khaveertz@gmail.com')->first();

    Notification::assertSentTo($user, VerifyEmail::class);
});

test('verifies the user email from a signed verification link', function() {

    //Creamos un usuario sin verificar
    $user = User::factory()->unverified()->create();

    //Creamos la URL
    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]
    );

    //El usuario simula presionar el enlace de confirmación de correo
    $response = $this->actingAs($user)->get($verificationUrl);

    //Redirige al dashboard
    $response->assertRedirect(route('dashboard'));

    // dd($user);

    //Esperamos el usuario verificado 
    expect($user->hasVerifiedEmail())->toBeTrue();

});

test('does not allow an unverified user to access the dashboard', function() {
    
    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertRedirect(route('verification.notice'));

});

test('allows a verified user to access the dashboard', function() {

    $user = User::factory()->create([
        'email_verified_at' => now() 
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertStatus(200);

});
