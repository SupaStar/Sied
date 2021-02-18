<?php

namespace App\Http\Controllers;

use App\Buzon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BuzonController extends Controller
{

  public function nuevo(Request $request)
  {
    $this->validate($request, [
      'titulo' => 'required',
      'descripcion' => 'required',
    ]);
    $buzon = new Buzon();
    $buzon->titulo = $request->titulo;
    $buzon->descripcion = $request->descripcion;
    $buzon->prioridad = $request->prioridad == "" ? "Alta" : $request->prioridad;
    $buzon->observacion="";
    $buzon->save();
    return redirect('/buzon/')->with('message', 'OK');;
  }

  public function new()
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageName' => 'Enviar Buzón'
    ];

    return view('buzon/nuevo', [
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

  public function editar(Request $request)
  {
    $this->validate($request, [
      'estatus' => 'required',
      'observacion' => 'required'
    ]);
    $buzon = Buzon::find($request->id);
    $buzon->estatus = $request->estatus;
    $buzon->observacion = $request->observacion;
    $buzon->save();
    return redirect('/alertas/alertasinternas')->with('message', 'OK');
  }

  public function getBuzones(Request $request)
  {

    if ($request->filtro == 'Prioridad') {
      $result = DB::table('buzon')->where('prioridad', 'prioridad');
    } else {
      $result = \App\Buzon::all();
      foreach ($result as $r) {
        if ($r->estatus == 1) {
          $r->estatus = "Nuevo";
        } elseif ($r->estatus == 2) {
          $r->estatus = "En proceso";
        } elseif($r->estatus == 3) {
          $r->estatus = "Revisado";
        }
        $r->usuario;
      }
    }

    return datatables()->of($result)->addColumn('actions', function ($query) {

      return '
              <a href="#"  title="Editar"><button style="z-index:999" value="' . $query->estatus . '"  id="' . $query->id . '" type="button" data-toggle="modal" data-target="#inlineForm" class="btn btn-default"><i class="feather icon-edit primary"></i></button></a>
              ';
    })->rawColumns(['actions'])->toJson();
  }
  public function getBuzones2(Request $request)
  {


      $result2 = \App\Buzon::where("estatus",3)->get();
      foreach ($result2 as $r) {
       $r->estatus="Revisado";
        $r->usuario;
      }


    return datatables()->of($result2)->addColumn('actions', function ($query) {

      return '
              <a href="#"  title="Editar"><button style="z-index:999" value="' . $query->estatus . '"  id="' . $query->id . '" type="button" data-toggle="modal" data-target="#inlineForm" class="btn btn-default"><i class="feather icon-edit primary"></i></button></a>
              ';
    })->rawColumns(['actions'])->toJson();
  }
}
