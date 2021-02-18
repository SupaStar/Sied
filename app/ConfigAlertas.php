<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ConfigAlertas extends Model
{
  protected $table = "configuracionalerta";

  public function valorUID()
  {
    $db = ConfigAlertas::find(1);
    $fechaBd=Carbon::make($db->actualizacionUid)->format("Y-m-d");
    if($fechaBd<Carbon::now()){
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
      $series=$data->bmx->series;
      $ids = array_column($series, 'idSerie');
      $idTiie=array_search("SF60648",$ids);
      $idFix=array_search("SF43718",$ids);
      $idUid=array_search("SP68257",$ids);
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
    }
  }
}
