<?php
Route::get('morales', 'Morales@morales');
Route::post('fisica', 'Morales@fisicas');
Route::get('get', 'Morales@getmorales');
Route::get('/perfil/{id}/{redireccion?}', 'Morales@getPerfil')->name('perfil_web_ebr');
Route::post('activar', 'Morales@activar');
Route::post('archivar', 'Morales@archivar');
Route::post('crear', 'Morales@create');
Route::post('data', 'Morales@data');

Route::post('morales/eperfil', 'Morales@eperfil');
Route::post('eebr', 'Morales@eebr');
Route::get('morales/ebr/{id}', 'Morales@ebr');
Route::get('editarmoral/{id}', 'Morales@editarmoral');
Route::get('editarmoral/{id}', 'Morales@editarmoral');


Route::get('nuevo/empresa', 'Morales@NuevaEmpresa');
Route::get('continuar/{id}', 'Morales@continuar')->name("Morales.Registro.Credito");
Route::get('listaNegraPDF/{id}', 'Morales@listaNegraPDF');
Route::get('editar/fisica/{id}', 'Morales@editarfisica');


Route::get('info/{id}', 'Morales@info');
Route::get('editar/{id}', 'Morales@editar');
Route::get('perfil/{id}', 'Morales@fperfil');
Route::get('riesgo/{id}', 'Morales@friesgo');


Route::post('files/{id}', 'Morales@getfiles');
Route::post('editado', 'Morales@editado');
Route::post('eperfil', 'Morales@eperfil');
Route::post('continuar/credito/{id}', 'Morales@credito');

Route::post('credito/pago', 'Morales@pago');
Route::get('info/amortizacion/{id}', 'Morales@infoamortizacion');
Route::get('info/pagos/{id}', 'Morales@infopagos');
Route::get('info/pagos/aplicados/{id}', 'Morales@infopagosaplicados');
Route::get('info/condonar/flujo/{id}', 'Morales@condonarFlujo');
Route::get('info/historial/flujo/{id}', 'Morales@infohistorialflujo');
Route::get('info/credito/{id}', 'Morales@infocredito');
Route::get('info/tasas/{id}', 'Morales@infotasas');
