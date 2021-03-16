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
Route::get('continuar', 'Morales@continuar');
Route::get('listaNegraPDF/{id}', 'Morales@listaNegraPDF');
Route::get('editar/fisica/{id}', 'Morales@editarfisica');


Route::get('info/{id}', 'Morales@info');
Route::get('editar/{id}', 'Morales@editar');
Route::get('perfil/{id}', 'Morales@fperfil');
Route::get('riesgo/{id}', 'Morales@friesgo');

Route::post('files/{id}', 'Morales@getfiles');
Route::post('editado', 'Morales@editado');
Route::post('eperfil', 'Morales@eperfil');
