<?php
Route::get("todos","BuzonController@todo");
Route::get("buzon","BuzonController@new");
Route::post("nuevo","BuzonController@nuevo");
Route::get("encontrar/{id}","BuzonController@encontrar");
Route::put("editar/{id}","BuzonController@editar");
Route::delete("eliminar/{id}","BuzonController@eliminar");

