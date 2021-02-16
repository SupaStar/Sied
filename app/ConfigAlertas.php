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
      $endpoint = "https://www.banxico.org.mx/SieAPIRest/service/v1/series/SP68257/datos/" . $hoy . "/" . $hoy;
      $client = curl_init();
      curl_setopt($client, CURLOPT_URL, $endpoint);
      curl_setopt($client, CURLOPT_HTTPHEADER, array(
        'Bmx-Token:ba350db5f68fde993e7979d888bef54441414955063870c0fc04681ec87a86bd'
      ));
      curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
      $data = json_decode(curl_exec($client));
      curl_close($client);
      $db->valor = $data->bmx->series[0]->datos[0]->dato;
      $db->actualizacionUid = Carbon::now();
      $db->save();
    }
  }
}
