<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApiController extends Controller
{
  public function encontrarApi($id)
  {
    $alerta = \App\Alerta::find($id);
    return response()->json($alerta);
  }

  public function editarAPI(Request $request)
  {
    $alerta = \App\Alerta::find($request->id);
    $obs = "";
    $estaus = 1;
    if ($request->observacion != null) {
      $obs = $request->observacion;
    }
    $alerta->observacion = $obs;
    $alerta->sustento = $request->sustento;
    $estaus=$request->sustengo!=null?2:$estaus;
    $alerta->archivo_sustento = $this->agregarArchivo($request->file('Fsustento'), $request->inid, $alerta->archivo_sustento);
    $alerta->dictamen = $request->dictamen;
    $estaus=$request->dictamen!=null?3:$estaus;
    $alerta->archivo_dictamen = $this->agregarArchivo($request->file('Fdictamen'), $request->inid, $alerta->archivo_dictamen);
    $alerta->acuse = $request->acuse;
    $estaus=$request->acuse!=null?4:$estaus;
    $alerta->archivo_acuse = $this->agregarArchivo($request->file('Facuse'), $request->inid, $alerta->archivo_acuse);
    $estaus=$request->estatus==5?5:$estaus;
    $alerta->estatus = $estaus;
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
}
