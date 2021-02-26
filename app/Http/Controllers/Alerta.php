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
      'pageName' => 'Operaciones inusuales'
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
    $alerta->envio=$request->envio;
    $alerta->acuse = $request->acuse;
    $estaus=$request->acuse!=null?4:$estaus;
    $alerta->archivo_acuse = $this->agregarArchivo($request->file('Facuse'), $request->inid, $alerta->archivo_acuse);
    $estaus=$request->estatus==5?5:$estaus;
    $alerta->estatus = $estaus;
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
    $alerta->envio = $request->envio;
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
        if ($request->filtro == 1) {
      $result = \App\Alerta::where('envio',1)->where("estatus","<>",5)->get();
      foreach ($result as $r) {
        if ($r->estatus == 1) {
          $r->estatus = "Nuevo";
        } elseif ($r->estatus == 2) {
          $r->estatus = "Recabando información";
        }elseif ($r->estatus == 3) {
          $r->estatus = "En proceso";
        }elseif ($r->estatus == 4) {
          $r->estatus = "Observaciones";
        }elseif($r->estatus==5){
          $r->estatus = "Concluido";
        }
        else{
          $r->estatus = "Sin estatus";
        }
        $r->operacion= "Operación no preocupante";
        $r->cliente;
        $r->credito;}
    }
    elseif ($request->filtro == 2) {
      $result = \App\Alerta::where('envio',2)->where("estatus","<",5)->get();
      foreach ($result as $r) {
        if ($r->estatus == 1) {
          $r->estatus = "Nuevo";
        } elseif ($r->estatus == 2) {
          $r->estatus = "Recabando información";
        }elseif ($r->estatus == 3) {
          $r->estatus = "En proceso";
        }elseif ($r->estatus == 4) {
          $r->estatus = "Observaciones";
        }elseif($r->estatus==5){
          $r->estatus = "Concluido";
        }
        else{
          $r->estatus = "Sin estatus";
        }
        $r->operacion= "Operación inusual";
        $r->cliente;
        $r->credito;}
    }elseif ($request->filtro == 3) {
      $result = \App\Alerta::where('envio',3)->where("estatus","<",5)->get();
      foreach ($result as $r) {
        if ($r->estatus == 1) {
          $r->estatus = "Nuevo";
        } elseif ($r->estatus == 2) {
          $r->estatus = "Recabando información";
        }elseif ($r->estatus == 3) {
          $r->estatus = "En proceso";
        }elseif ($r->estatus == 4) {
          $r->estatus = "Observaciones";
        }elseif($r->estatus==5){
          $r->estatus = "Concluido";
        }
        else{
          $r->estatus = "Sin estatus";
        }
        $r->operacion= "Clientes Clasificados en el mayor grado de mayor riesgo";
        $r->cliente;
        $r->credito;}
    }elseif ($request->filtro == 4) {
      $result = \App\Alerta::where('envio',4)->where("estatus","<",5)->get();
      foreach ($result as $r) {
        if ($r->estatus == 1) {
          $r->estatus = "Nuevo";
        } elseif ($r->estatus == 2) {
          $r->estatus = "Recabando información";
        }elseif ($r->estatus == 3) {
          $r->estatus = "En proceso";
        }elseif ($r->estatus == 4) {
          $r->estatus = "Observaciones";
        }elseif($r->estatus==5){
          $r->estatus = "Concluido";
        }
        else{
          $r->estatus = "Sin estatus";
        }
        $r->operacion= "Operación clientes Clasificados en el mayor grado de mayor riesgo";
        $r->cliente;
        $r->credito;}
    }elseif ($request->filtro == 5) {
      $result = \App\Alerta::where('envio',5)->where("estatus","<>",5)->get();
      foreach ($result as $r) {
        if ($r->estatus == 1) {
          $r->estatus = "Nuevo";
        } elseif ($r->estatus == 2) {
          $r->estatus = "Recabando información";
        }elseif ($r->estatus == 3) {
          $r->estatus = "En proceso";
        }elseif ($r->estatus == 4) {
          $r->estatus = "Observaciones";
        }elseif($r->estatus==5){
          $r->estatus = "Concluido";
        }
        else{
          $r->estatus = "Sin estatus";
        }
        $r->operacion= "Operaciones relevantes";
        $r->cliente;
        $r->credito;}
    }
    else {
      $result = \App\Alerta::where("estatus","<",5)->get();

      foreach ($result as $r) {
        if ($r->estatus == 1) {
          $r->estatus = "Nuevo";
        } elseif ($r->estatus == 2) {
          $r->estatus = "Recabando información";
        }elseif ($r->estatus == 3) {
          $r->estatus = "En proceso";
        }elseif ($r->estatus == 4) {
          $r->estatus = "Observaciones";
        }elseif($r->estatus == 5){
          $r->estatus = "Concluido";
        }
        elseif($r->estatus==0){
          $r->estatus = "Sin estatus";
        }
        if($r->envio==1) {
          $r->operacion = "Operación no preocupante";
          $r->cliente;
          $r->credito;
        } elseif($r->envio==2) {
          $r->operacion = "Operación inusual";
          $r->cliente;
          $r->credito;
        } elseif($r->envio==3) {
          $r->operacion = "Clientes Clasificados en el mayor grado de mayor riesgo";
          $r->cliente;
          $r->credito;
        } elseif($r->envio==4) {
          $r->operacion = "Operación clientes Clasificados en el mayor grado de mayor riesgo";
          $r->cliente;
          $r->credito;
        } elseif($r->envio==5) {
          $r->operacion = "Operaciones relevantes";
          $r->cliente;
          $r->credito;
        }
        elseif($r->envio==0){
          $r->operacion = "Sin operación";
          $r->cliente;
          $r->credito;
        }
      }

    }
    return datatables()->of($result)->addColumn('actions', function ($query) {

      return '
              <a href="#"  title="Editar"><button style="z-index:999" id="btnedita" value="' . $query->estatus . '"  aria-label="' . $query->observacion . '" name="' . $query->id . '" type="button" data-toggle="modal" data-target="#inlineForm" class="btn btn-default"><i class="feather icon-edit primary"></i></button></a>
              ';
    })->rawColumns(['actions'])->toJson();

  }
  public function getAlertas2(Request $request)
  {
        if ($request->filtro == 1) {
      $result = \App\Alerta::where('estatus',5)->where('envio',1)->get();
      foreach ($result as $r) {
        if ($r->estatus == 1) {
          $r->estatus = "Nuevo";
        } elseif ($r->estatus == 2) {
          $r->estatus = "Recabando información";
        }elseif ($r->estatus == 3) {
          $r->estatus = "En proceso";
        }elseif ($r->estatus == 4) {
          $r->estatus = "Observaciones";
        }else{
          $r->estatus = "Concluido";
        }
        $r->operacion= "Operación no preocupante";
        $r->cliente;
        $r->credito;}
    }
    elseif ($request->filtro == 2) {
      $result = \App\Alerta::where('estatus',5)->where('envio',2)->get();
      foreach ($result as $r) {
        if ($r->estatus == 1) {
          $r->estatus = "Nuevo";
        } elseif ($r->estatus == 2) {
          $r->estatus = "Recabando información";
        }elseif ($r->estatus == 3) {
          $r->estatus = "En proceso";
        }elseif ($r->estatus == 4) {
          $r->estatus = "Observaciones";
        }else{
          $r->estatus = "Concluido";
        }
        $r->operacion= "Operación inusual";
        $r->cliente;
        $r->credito;}
    }elseif ($request->filtro == 3) {
      $result = \App\Alerta::where('estatus',5)->where('envio',3)->get();
      foreach ($result as $r) {
        if ($r->estatus == 1) {
          $r->estatus = "Nuevo";
        } elseif ($r->estatus == 2) {
          $r->estatus = "Recabando información";
        }elseif ($r->estatus == 3) {
          $r->estatus = "En proceso";
        }elseif ($r->estatus == 4) {
          $r->estatus = "Observaciones";
        }else{
          $r->estatus = "Concluido";
        }
        $r->operacion= "Clientes Clasificados en el mayor grado de mayor riesgo";
        $r->cliente;
        $r->credito;}
    }elseif ($request->filtro == 4) {
      $result = \App\Alerta::where('estatus',5)->where('envio',4)->get();
      foreach ($result as $r) {
        if ($r->estatus == 1) {
          $r->estatus = "Nuevo";
        } elseif ($r->estatus == 2) {
          $r->estatus = "Recabando información";
        }elseif ($r->estatus == 3) {
          $r->estatus = "En proceso";
        }elseif ($r->estatus == 4) {
          $r->estatus = "Observaciones";
        }else{
          $r->estatus = "Concluido";
        }
        $r->operacion= "Operación clientes Clasificados en el mayor grado de mayor riesgo";
        $r->cliente;
        $r->credito;}
    }elseif ($request->filtro == 5) {
      $result = \App\Alerta::where('estatus',5)->where('envio',5)->get();
      foreach ($result as $r) {
        if ($r->estatus == 1) {
          $r->estatus = "Nuevo";
        } elseif ($r->estatus == 2) {
          $r->estatus = "Recabando información";
        }elseif ($r->estatus == 3) {
          $r->estatus = "En proceso";
        }elseif ($r->estatus == 4) {
          $r->estatus = "Observaciones";
        }else{
          $r->estatus = "Concluido";
        }
        $r->operacion= "Operaciones relevantes";
        $r->cliente;
        $r->credito;}
    }
    else {
      $result = \App\Alerta::where('estatus',5)->get();

      foreach ($result as $r) {
        if ($r->estatus == 1) {
          $r->estatus = "Nuevo";
        } elseif ($r->estatus == 2) {
          $r->estatus = "Recabando información";
        }elseif ($r->estatus == 3) {
          $r->estatus = "En proceso";
        }elseif ($r->estatus == 4) {
          $r->estatus = "Observaciones";
        }else{
          $r->estatus = "Concluido";
        }if($r->envio==1) {
          $r->estatus = "Concluido";
          $r->operacion = "Operación no preocupante";
          $r->cliente;
          $r->credito;
        } elseif($r->envio==2) {
          $r->estatus = "Concluido";
          $r->operacion = "Operación inusual";
          $r->cliente;
          $r->credito;
        } elseif($r->envio==3) {
          $r->estatus = "Concluido";
          $r->operacion = "Clientes Clasificados en el mayor grado de mayor riesgo";
          $r->cliente;
          $r->credito;
        } elseif($r->envio==4) {
          $r->estatus = "Concluido";
          $r->operacion = "Operación clientes Clasificados en el mayor grado de mayor riesgo";
          $r->cliente;
          $r->credito;
        } else {
          $r->estatus = "Concluido";
          $r->operacion = "Operaciones relevantes";
          $r->cliente;
          $r->credito;
        }
      }

    }
    return datatables()->of($result)->addColumn('actions', function ($query) {

      return '
              <a href="#"  title="Editar"><button style="z-index:999" id="btnedita" value="' . $query->estatus . '"  aria-label="' . $query->observacion . '" name="' . $query->id . '" type="button" data-toggle="modal" data-target="#inlineForm" class="btn btn-default"><i class="feather icon-edit primary"></i></button></a>
              ';
    })->rawColumns(['actions'])->toJson();

  }
  public function getAlertasfecha(Request $request)
  {
    return json_encode($request->fechafinal);

  }

  public function verbuzon()
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageName' => 'Operaciones internas preocupantes'
    ];

    return view('buzon/buzon', [
      'pageConfigs' => $pageConfigs
    ]);
  }
}
