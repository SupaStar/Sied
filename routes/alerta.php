<?php

Route::get('alertas', 'Alerta@todo');
Route::post('nuevaAlerta', 'Alerta@nueva');
Route::get('encontrar/{id}', 'Alerta@encontrar');
Route::post('editar/', 'Alerta@editar');
Route::delete('eliminar/{id}', 'Alerta@eliminar');
Route::get('alerta','Alerta@getAlertas');
