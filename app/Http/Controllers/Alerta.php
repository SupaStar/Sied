<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    return view('alertas',[
      'pageConfigs' => $pageConfigs
    ]);
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
  public function getAlertas(Request $request)
  {

    if($request->filtro == 'Prioridad'){
      $result = DB::table('alertas_pld')->where('prioridad', 'prioridad');
    }elseif($request->filtro == 'Titulo'){
      $result = DB::table('alertas_pld')->where('titulo', 'titulo');
    }else{
      $result=\App\Alerta::all();
      foreach ($result as $r)
      {
        $r->cliente;
        $r->credito;
      }
    }

    return datatables()->of($result)->toJson();

  }
}
