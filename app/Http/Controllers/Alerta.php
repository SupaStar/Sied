<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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
      'estatus' => 'required'
    ]);
    $alerta = \App\Alerta::find($request->id);
    $alerta->observacion = $request->observacion;
    $alerta->sustento = $request->sustento;
    $alerta->archivo_sustento = $this->agregarArchivo($request->file('Fsustento'), Auth::id(), $alerta->archivo_sustento);
    $alerta->dictamen = $request->dictamen;
    $alerta->archivo_dictamen = $this->agregarArchivo($request->file('Fdictamen'), Auth::id(), $alerta->archivo_dictamen);
    $alerta->acuse = $request->acuse;
    $alerta->archivo_acuse = $this->agregarArchivo($request->file('Facuse'), Auth::id(), $alerta->archivo_acuse);
    $alerta->observacion = $request->observacion;
    $alerta->estatus = $request->estatus;
    $alerta->save();
    return redirect('/alertas/alertas')->with('message', 'OK');
  }

  public function editarAPI(Request $request)
  {
    $this->validate($request, [
      'observacion' => 'required',
      'estatus' => 'required'
    ]);
    $alerta = \App\Alerta::find($request->id);
    $alerta->observacion = $request->observacion;
    $alerta->sustento = $request->sustento;
    $alerta->archivo_sustento = $this->agregarArchivo($request->file('Fsustento'), $request->inid, $alerta->archivo_sustento);
    $alerta->dictamen = $request->dictamen;
    $alerta->archivo_dictamen = $this->agregarArchivo($request->file('Fdictamen'), $request->inid, $alerta->archivo_dictamen);
    $alerta->acuse = $request->acuse;
    $alerta->archivo_acuse = $this->agregarArchivo($request->file('Facuse'), $request->inid, $alerta->archivo_acuse);
    $alerta->observacion = $request->observacion;
    $alerta->estatus = $request->estatus;
    $alerta->save();
    return response()->json(["estado" => true]);
  }

  public function agregarArchivo($archivo, $clientid, $anterior)
  {
    if (isset($archivo)) {
      $path = 'fisicas/destino';
      $extension = strtolower($archivo->getClientOriginalExtension());
      if (strtolower($extension) == 'pdf') {
        $filename = $clientid . '-destino.' . $extension;
        $path = Storage::disk('public')->put($path . '/' . $filename, $archivo);
        return $path;
      }
      return $anterior;
    }
    return $anterior;
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
        if ($r->estatus == 1) {
          $r->estatus = "Nuevo";
        } elseif ($r->estatus == 2) {
          $r->estatus = "En proceso";
        }
        $r->cliente;
        $r->credito;
      }

    }
    return datatables()->of($result)->addColumn('actions', function ($query) {

      return '
              <a href="#"  title="Editar"><button style="z-index:999" id="btnedita" value="' . $query->estatus . '"  aria-label="' . $query->observacion . '" name="' . $query->id . '" type="button" data-toggle="modal" data-target="#inlineForm" class="btn btn-default"><i class="feather icon-edit primary"></i></button></a>
              ';
    })->rawColumns(['actions'])->toJson();

  }
}
