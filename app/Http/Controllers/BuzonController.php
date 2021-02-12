<?php

namespace App\Http\Controllers;

use App\Buzon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
      //'id' => 'required',
      'titulo' => 'required',
      'descripcion' => 'required',
    ]);
    $buzon = new Buzon();
    $id = Auth::id();
    $buzon->usuario_id = $id;
    $buzon->titulo = $request->titulo;
    $buzon->descripcion = $request->descripcion;
    $buzon->prioridad = $request->prioridad == "" ? "Alta" : $request->prioridad;
    $buzon->save();
    return redirect('/buzon/buzon')->with('message', 'OK');;
  }
  public function new()
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageName' => 'Enviar Buzón'
    ];

    return view('buzon/nuevo',[
      'pageConfigs' => $pageConfigs
    ]);
  }

  public function todo()
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageName' => 'Buzón'
    ];

    return view('buzon/buzon', [
      'pageConfigs' => $pageConfigs
    ]);
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
      'prioridad'=>'required',
    ]);
    $buzon = Buzon::find($id);
    $buzon->titulo = $request->titulo;
    $buzon->descripcion = $request->descripcion;
    $buzon->prioridad = $request->prioridad == "" ? "Alta" : $request->prioridad;
    return response()->json($buzon);
  }
  public function getBuzones(Request $request)
  {

    if ($request->filtro == 'Prioridad') {
      $result = DB::table('buzon')->where('prioridad', 'prioridad');
    } else {
      $result = \App\Buzon::all();
      foreach ($result as $r) {
        $r->usuario;
      }
    }

    return datatables()->of($result)->toJson();

  }
}
