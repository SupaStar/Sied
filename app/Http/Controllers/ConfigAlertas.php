<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfigAlertas extends Controller
{
  public function todos()
  {
    $configuraciones = \App\ConfigAlertas::all();
    return response()->json($configuraciones);
  }

  public function nueva(Request $request)
  {
    $this->validate($request, [
      'pagosMes' => 'required',
      'monto' => 'required',
    ]);
    $configuracion = new \App\ConfigAlertas();
    $configuracion->pagosMes = $request->pagosMes;
    $configuracion->monto = $request->monto;
    $configuracion->save();
    return response()->json($configuracion);
  }

  public function editar($id, Request $request)
  {
    $this->validate($request, [
      'pagosMes' => 'required',
      'monto' => 'required',
    ]);
    $configuracion = \App\ConfigAlertas::find($id);
    $configuracion->pagosMes = $request->pagosMes;
    $configuracion->monto = $request->monto;
    $configuracion->save();
    return response()->json($configuracion);
  }

  public function encontrar($id)
  {
    $configuracion = \App\ConfigAlertas::find($id);
    return response()->json($configuracion);
  }

  public function eliminar($id)
  {
    \App\ConfigAlertas::destroy($id);
    return response()->json(true);
  }
}
