<?php
Route::get("buzones","BuzonController@todo");
Route::get("/","BuzonController@new");
Route::get("getbuzon","BuzonController@getBuzones");
Route::get("getbuzon2","BuzonController@getBuzones2");
Route::post("nuevo","BuzonController@nuevo");
Route::get("encontrar/{id}","BuzonController@encontrar");
Route::post("editar/","BuzonController@editar");
Route::delete("eliminar/{id}","BuzonController@eliminar");

