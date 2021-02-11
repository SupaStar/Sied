<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Alerta extends Controller
{
  public function nueva(Request $request)
  {
    $this->validate($request, [
      'cliente_id' => 'required',
      'credito_id' => 'required',
      'tipo_alerta' => 'required',
      'titulo' => 'required',
      'descripcion' => 'required',
      'prioridad' => 'required',
    ]);
    $alerta = new \App\Alerta();
    $alerta->cliente_id = $request->cliente_id;
    $alerta->credito_id = $request->credito_id;
    $alerta->tipo_alerta = $request->tipo_alerta;
    $alerta->titulo = $request->titulo;
    $alerta->descripcion = $request->descripcion;
    $alerta->estatus = 1;
    $alerta->observacion = $request->observacion;
    $alerta->prioridad = $request->prioridad;
    $alerta->save();
    return response()->json($alerta);
  }

  public function todo()
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageName' => 'Alertas'
    ];
    $alertas = \App\Alerta::all();
    foreach($alertas as $alerta)
    {
      $alerta->cliente;
      $alerta->credito;
    }



    return view('alertas',[
      'pageConfigs' => $pageConfigs
    ])->with('alertas',$alertas);
  }
  public function encontrar($id){
    $alerta=\App\Alerta::find($id);
    return response()->json($alerta);
  }
  public function editar($id,Request $request){
    $this->validate($request, [
      'tipo_alerta' => 'required',
      'titulo' => 'required',
      'descripcion' => 'required',
      'prioridad' => 'required',
    ]);
    $alerta=\App\Alerta::find($id);
    $alerta->tipo_alerta = $request->tipo_alerta;
    $alerta->titulo = $request->titulo;
    $alerta->descripcion = $request->descripcion;
    $alerta->observacion = $request->observacion;
    $alerta->prioridad = $request->prioridad;
    $alerta->save();
    return response()->json($alerta);
  }
  public function eliminar($id){
    $alerta=\App\Alerta::find($id);
    $alerta->estatus=0;
    return response()->json(true);
  }
}
