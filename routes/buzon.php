<?php
Route::get("buzones","BuzonController@todo");
Route::get("buzon","BuzonController@new");
Route::get("getbuzon","BuzonController@getBuzones");
Route::post("nuevo","BuzonController@nuevo");
Route::get("encontrar/{id}","BuzonController@encontrar");
Route::put("editar/{id}","BuzonController@editar");
Route::delete("eliminar/{id}","BuzonController@eliminar");

