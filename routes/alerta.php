<?php

Route::get('alertas', 'Alerta@todo');
Route::post('nuevaAlerta', 'Alerta@nueva');
Route::get('encontrar/{id}', 'Alerta@encontrar');
Route::put('editar/{id}', 'Alerta@editar');
Route::delete('eliminar/{id}', 'Alerta@eliminar');
