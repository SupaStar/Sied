<?php
Route::get('fisica', 'Clients@fisicas');

Route::get('/fisica/espera', function () {
    return redirect('/clientes/fisica')->with('espera', 'Cliente agregado a lista de espera');;
});


Route::post('fisica', 'Clients@fisicas');
Route::get('get', 'Clients@getfisicas');
Route::get('pendientes', 'Clients@pendientes');
Route::post('activar', 'Clients@activar');
Route::post('archivar', 'Clients@archivar');
Route::post('crear', 'Clients@create');
Route::post('crearcontinuar', 'Clients@createcontinuar');
Route::post('data', 'Clients@data');

Route::get('nuevo/fisica', 'Clients@newfisica');
Route::get('continuar/{id}', 'Clients@continuar');
Route::get('amortizacion', 'Clients@amortizacion');
Route::get('info/amortizacion/{id}', 'Clients@infoamortizacion');
Route::get('info/pagos/{id}', 'Clients@infopagos');
Route::get('info/pagos/aplicados/{id}', 'Clients@infopagosaplicados');
Route::get('info/historial/flujo/{id}', 'Clients@infohistorialflujo');
Route::get('info/condonar/flujo/{id}', 'Clients@condonarFlujo');
Route::get('info/credito/{id}', 'Clients@infocredito');
Route::get('info/tasas/{id}', 'Clients@infotasas');
Route::post('credito/pago', 'Clients@pago');

Route::post('amortizacion/restaurar', 'Clients@restaurarAmortizacion');


Route::get('listaNegraPDF/{id}', 'Clients@listaNegraPDF');
Route::get('editar/fisica/{id}', 'Clients@editarfisica');


Route::get('fisicas/info/{id}', 'Clients@info');
Route::get('fisicas/editar/{id}', 'Clients@editar');
Route::get('fisicas/continuar/registro/{id}', 'Clients@ContinuarRegistro');
Route::get('fisicas/perfil/{id}', 'Clients@fperfil');
Route::get('fisicas/riesgo/{id}', 'Clients@friesgo');
Route::get('fisicas/ebr/{id}', 'Clients@ebr');



Route::post('fisicas/files/{id}', 'Clients@getfiles');
Route::post('fisicas/editado', 'Clients@editado');
Route::post('fisicas/eperfil', 'Clients@eperfil');
Route::post('fisicas/eebr', 'Clients@eebr');

Route::post('continuar/credito/{id}', 'Clients@credito');
