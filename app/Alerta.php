<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Alerta extends Model
{
  protected $table = "alertas_pld";

  public function verificar(Request $request, $creditoId)
  {
    $perfil = Perfil::find($request->id)->first();
    $divisaP = $request->moneda == "Nacional" ? 1 : 0;
    if ($perfil->divisa !== $divisaP) {
      $alerta = new Alerta();
      $alerta->cliente_id = $request->id;
      $alerta->credito_id = $creditoId;
      $alerta->estatus = 1;
      $alerta->observacion = "";
      $alerta->prioridad = "Alta";
      $alerta->tipo_alerta = "Moneda";
      $alerta->titulo = "Moneda seleccionada diferente";
      $registrado = Divisa::find($perfil->divisa);
      $divisaP = $divisaP == 0 ? "Otro" : "Moneda nacional";
      $alerta->descripcion = "Moneda de pago registrada: " . $registrado->descripcion . "\nSe uso: " . $divisaP . " con nombre: " . $request->nmoneda;
      $alerta->save();
    }
    $formaPago = InstrumentoMonetario::where("descripcion", $request->forma)->first();
    if ($perfil->instrumento_monetario !== $formaPago->id) {
      $alerta = new Alerta();
      $alerta->cliente_id = $request->id;
      $alerta->credito_id = $creditoId;
      $alerta->estatus = 1;
      $alerta->observacion = "";
      $alerta->prioridad = "Alta";
      $alerta->tipo_alerta = "FormaPago";
      $alerta->titulo = "Forma de pago seleccionada diferente";
      $registrado = InstrumentoMonetario::find($perfil->instrumento_monetario);
      $alerta->descripcion = "Forma de pago registrada: " . $registrado->descripcion . "\nSe uso: " . $request->forma;
      $alerta->save();
    }
//    $origen = OrigenRecursos::where("descripcion",$request->origen)->first();
//    echo json_encode($request->origen);
//    return;
//    if ($perfil->origen_recursos !== $origen->id) {
//      $alerta = new Alerta();
//      $alerta->cliente_id = $request->id;
//      $alerta->credito_id = $creditoId;
//      $alerta->estatus = 1;
//      $alerta->observacion = "";
//      $alerta->prioridad = "Alta";
//      $alerta->tipo_alerta = "Origen de los recursos";
//      $alerta->titulo = "Origen de recursos diferente";
//      $alerta->descripcion = "Origen de recursos diferente";
//      $alerta->save();
//    }
  }
}
