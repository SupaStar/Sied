<?php

namespace App\Http\Controllers;

use App\ConfigAlertas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApiController extends Controller
{
  public function encontrarApi($id)
  {
    $alerta = \App\Alerta::find($id);
    $alerta->cliente;
    return response()->json($alerta);
  }

  public function editarAPI(Request $request)
  {
    $alerta = \App\Alerta::find($request->id);
    $obs = "";
    $envio = 0;
    $estatus = 1;
    if ($request->observacion != null) {
      $obs = $request->observacion;
    }
    if ($request->envio != null) {
      $envio = $request->envio;
    }
    $alerta->observacion = $obs;
    $alerta->sustento = $request->sustento;
    $alerta->envio = $envio;
    $estatus = $request->sustento != null ? 2 : $estatus;
    $alerta->archivo_sustento = $this->agregarArchivo($request->file('Fsustento'), $request->inid, $alerta->archivo_sustento);
    $alerta->dictamen = $request->dictamen;
    $estatus = $request->dictamen != null ? 3 : $estatus;
    $alerta->archivo_dictamen = $this->agregarArchivo($request->file('Fdictamen'), $request->inid, $alerta->archivo_dictamen);
    $alerta->acuse = $request->acuse;
    $estatus = $request->acuse != null ? 4 : $estatus;
    $alerta->archivo_acuse = $this->agregarArchivo($request->file('Facuse'), $request->inid, $alerta->archivo_acuse);
    $estatus = $request->estatus == 5 ? 5 : $estatus;
    $alerta->estatus = $estatus;

    $alerta->save();
    return response()->json(["estado" => true]);
  }

  public function alertasTerminadas()
  {
    $alertas = \App\Alerta::where('estatus', 5)->get();
    foreach ($alertas as $alerta) {
      $alerta->cliente;
    }
    return response()->json($alertas);
  }

  public function cambiarValoresMonedas()
  {
    $db = ConfigAlertas::find(1);
    $hoy = Carbon::now()->format("Y-m-d");
    $ayer = Carbon::now()->addDays(-1)->format("Y-m-d");
    $endpoint = "https://www.banxico.org.mx/SieAPIRest/service/v1/series/SP68257,SF60648,SF43718/datos/" . $ayer . "/" . $hoy;
    $client = curl_init();
    curl_setopt($client, CURLOPT_URL, $endpoint);
    curl_setopt($client, CURLOPT_HTTPHEADER, array(
      'Bmx-Token:ba350db5f68fde993e7979d888bef54441414955063870c0fc04681ec87a86bd'
    ));
    curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
    $data = json_decode(curl_exec($client));
    $series = $data->bmx->series;
    $ids = array_column($series, 'idSerie');
    $idTiie = array_search("SF60648", $ids);
    $idFix = array_search("SF43718", $ids);
    $idUid = array_search("SP68257", $ids);
    $db->valor = $series[$idUid]->datos[0]->dato;
    $db->tiie28 = $series[$idTiie]->datos[0]->dato;
    $db->fix = $series[$idFix]->datos[0]->dato;
    $semanaPas = Carbon::now()->addDay(-7)->format("Y-m-d");
    $endpoint = "https://www.banxico.org.mx/SieAPIRest/service/v1/series/SF60633/datos/" . $semanaPas . "/" . $hoy;
    curl_setopt($client, CURLOPT_URL, $endpoint);
    $data = json_decode(curl_exec($client));
    $db->cetes28 = $data->bmx->series[0]->datos[0]->dato;
    $db->actualizacionUid = Carbon::now();
    $db->save();
    curl_close($client);
    return response()->json($db);
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
