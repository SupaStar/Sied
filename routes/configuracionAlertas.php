<?php
Route::get("configuraciones", "ConfigAlertas@todos")->name('configuraciones_inicio');
Route::post("configuracionesAmorti", "ConfigAlertas@editarConfigGenerales")->name('configuraciones_editarGenerales');
Route::post("configuracionesAlertas", "ConfigAlertas@editarConfigAlertas")->name('configuraciones_editarAlertas');
