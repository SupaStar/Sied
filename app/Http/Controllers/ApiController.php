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
    $obs="";
    if($request->observacion != null){$obs=$request->observacion;}
    $alerta->observacion = $obs;
    $alerta->sustento = $request->sustento;
    $alerta->archivo_sustento = $this->agregarArchivo($request->file('Fsustento'), $request->inid, $alerta->archivo_sustento);
    $alerta->dictamen = $request->dictamen;
    $alerta->archivo_dictamen = $this->agregarArchivo($request->file('Fdictamen'), $request->inid, $alerta->archivo_dictamen);
    $alerta->acuse = $request->acuse;
    $alerta->archivo_acuse = $this->agregarArchivo($request->file('Facuse'), $request->inid, $alerta->archivo_acuse);
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
}
