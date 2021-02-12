<?php

namespace App\Http\Controllers;

use App\Buzon;
use Illuminate\Http\Request;

class BuzonController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('checkStatus');
  }

  public function nuevo(Request $request)
  {
    $this->validate($request, [
      'id' => 'required',
      'titulo' => 'required',
      'descripcion' => 'required',
    ]);
    $buzon = new Buzon();
    $buzon->usuario_id = $request->id;
    $buzon->titulo = $request->titulo;
    $buzon->descripcion = $request->descripcion;
    $buzon->prioridad = $request->prioridad == "" ? "Alta" : $request->prioridad;
    $buzon->save();
    return response()->json($buzon);
  }

  public function todos()
  {
    $buzones = Buzon::all();
    return response()->json($buzones);
  }

  public function encontrar($id)
  {
    $buzon = Buzon::find($id);
    return response()->json($buzon);
  }

  public function eliminar($id)
  {
    $buzon = Buzon::find($id);
    $buzon->estatus = 0;
    return response()->json($buzon);
  }

  public function editar($id, Request $request)
  {
    $this->validate($request, [
      'titulo' => 'required',
      'descripcion' => 'required',
    ]);
    $buzon = Buzon::find($id);
    $buzon->titulo = $request->titulo;
    $buzon->descripcion = $request->descripcion;
    $buzon->prioridad = $request->prioridad == "" ? "Alta" : $request->prioridad;
    return response()->json($buzon);
  }
}
