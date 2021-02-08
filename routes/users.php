<?php
Route::get('lista', 'Users@index');
Route::get('get', 'Users@GetUsers');
Route::post('activar', 'Users@activar');
Route::post('desactivar', 'Users@desactivar');
Route::get('nuevo', 'Users@new');
Route::post('crear', 'Users@create');
Route::get('editar/{id}', 'Users@edit');
Route::post('editado', 'Users@edited');
Route::post('reactivar', 'Users@reactivar');





