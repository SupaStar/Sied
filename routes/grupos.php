<?php
Route::get('', 'Grupos@index');
Route::get('getData', 'Grupos@getData');
Route::get('create', 'Grupos@create');
Route::post('store', 'Grupos@store');
