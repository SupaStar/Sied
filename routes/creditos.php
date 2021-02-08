<?php
Route::get('', 'Creditos@index');
Route::get('get', 'Creditos@getData');
Route::get('nuevo', 'Creditos@new');
Route::post('crear', 'Creditos@create');
