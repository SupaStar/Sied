<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Alerta extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('checkStatus');
  }

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

    return view('alertas', [
      'pageConfigs' => $pageConfigs
    ]);
  }

  public function encontrar($id)
  {
    $alerta = \App\Alerta::find($id);
    return response()->json($alerta);
  }

  public function editar(Request $request)
  {
    $this->validate($request, [

      'observacion' => 'required',
    ]);
    $alerta = \App\Alerta::find($request->id);
    $alerta->observacion = $request->observacion;
    $alerta->save();
    return redirect('/alertas/alertas')->with('message', 'OK');
  }

  public function eliminar($id)
  {
    $alerta = \App\Alerta::find($id);
    $alerta->estatus = 0;
    return response()->json(true);
  }

  public function getAlertas(Request $request)
  {

    if ($request->filtro == 'Prioridad') {
      $result = DB::table('alertas_pld')->where('prioridad', 'prioridad');
    } elseif ($request->filtro == 'Titulo') {
      $result = DB::table('alertas_pld')->where('titulo', 'titulo');
    } else {
      $result = \App\Alerta::all();
      foreach ($result as $r) {
        if($r->estatus==1)
        {
          $r->estatus="Nuevo";
        }
        elseif($r->estatus==2)
        {
          $r->estatus="En proceso";
        }
        $r->cliente;
        $r->credito;
      }

    }
    return datatables()->of($result)->addColumn('actions', function($query) {

      return '
              <a href="#"  title="Editar"><button style="z-index:999" id="' . $query->id . '" type="button" data-toggle="modal" data-target="#inlineForm" class="btn btn-default"><i class="feather icon-edit primary"></i></button></a>
              ';
    })->rawColumns(['actions'])->toJson();

  }
}
