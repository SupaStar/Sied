<?php

Route::get('alertas', 'Alerta@todo')->name("verAlertas");
Route::get('alertasinternas', 'Alerta@verbuzon')->name("verAlertasinternas");
Route::post('nuevaAlerta', 'Alerta@nueva');
Route::get('encontrar/{id}', 'Alerta@encontrar');
Route::post('editar/', 'Alerta@editar');
Route::delete('eliminar/{id}', 'Alerta@eliminar');
Route::get('alerta','Alerta@getAlertas');
Route::get('alerta2','Alerta@getAlertas2');

Route::get('alertaf','Alerta@getAlertasfecha');

Route::get('excel/{fecha1}/{fecha2}','Alerta@generarExcel')->name('generar_excel');
Route::get('pdf/{fecha1}/{fecha2}','Alerta@generarPDF')->name('generar_pdf');
