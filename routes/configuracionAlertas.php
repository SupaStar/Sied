<?php

Route::get("configuraciones", "ConfigAlertas@todos");
Route::get("buscarConfigurtacion/{id}", "ConfigAlertas@encontrar");
Route::post("nuevaConfiguracion", "ConfigAlertas@nueva");
Route::put("editarConfiguracion/{id}", "ConfigAlertas@editar");
Route::delete("eliminarConfiguracion/{id}", "ConfigAlertas@eliminar");
