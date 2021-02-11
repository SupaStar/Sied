<?php

Route::prefix('usuarios')->group(base_path('routes/users.php'));
Route::prefix('mi-cuenta')->group(base_path('routes/account.php'));
Route::prefix('clientes')->group(base_path('routes/clients.php'));
Route::prefix('morales')->group(base_path('routes/morales.php'));
Route::prefix('cliente')->group(base_path('routes/cliente.php'));
Route::prefix('creditos')->group(base_path('routes/creditos.php'));
Route::prefix('grupos')->group(base_path('routes/grupos.php'));
Route::prefix('alertas')->group(base_path('routes/alerta.php'));

Route::view('/desactivado', 'not-authorized');

Route::get('/twofactor', 'Home@invcaptcha');
Route::get('/', 'Home@index');
Route::get('/inicio', 'Home@inicio');

Auth::routes();

Route::get('/logout', 'Auth\LoginController@logout');

Route::post('/util/imgto64', 'Utilities@imgto64');

Route::post('/util/checkemail/{email}', 'Utilities@checkemail');

Route::post('/util/checkcurp/{curp}', 'Utilities@checkcurp');

Route::post('/util/checkstate/{state}', 'Utilities@checkstate');

Route::post('/util/generateContract', 'Utilities@generateContract');


Route::get('storage/{clase}/{tipo}/{archivo}', function ($clase, $tipo, $archivo) {
  $public_path = public_path();
  $url = $public_path . '/uploads/' . $clase . '/' . $tipo . '/' . $archivo;
  if (Storage::disk('public')->exists($clase . '/' . $tipo . '/' . $archivo)) {
    return response()->download($url);
  }
  abort(404);
});

Route::get('storage/credito/pagos/fisica/comprobante/{archivo}', function ($archivo) {
  $public_path = public_path();
  $url = $public_path . '/uploads/credito/pagos/fisica/comprobante/' . $archivo;
  if (Storage::disk('public')->exists('credito/pagos/fisica/comprobante/' . $archivo)) {
    return response()->download($url);
  }
  abort(404);
});

if (env('APP_ENV') === 'production') {
    URL::forceScheme('https');
}
