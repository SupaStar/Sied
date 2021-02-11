<?php

namespace App;

use Carbon\Carbon;
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
    $pagomesGlobal = ConfigAlertas::all()->first();
    $numeroPagoMes = $perfil->nPagosMes + $pagomesGlobal->pagosMes;
    $carbon = Carbon::now()->addMonth(-1);
    $mesAnterior = $carbon->format('d-m-Y');
    $nPagosMes = count(Pago::where("client_id", $request->id)->where("fpago", ">", $mesAnterior)->get());
    if ($nPagosMes > $numeroPagoMes) {
      $alerta = new Alerta();
      $alerta->cliente_id = $request->id;
      $alerta->credito_id = $creditoId;
      $alerta->estatus = 1;
      $alerta->observacion = "";
      $alerta->prioridad = "Alta";
      $alerta->tipo_alerta = "Numero de pagos";
      $alerta->titulo = "Numero de pagos mayor";
      $alerta->descripcion = "Se pueden realizar: " . $numeroPagoMes . " pagos por mes\nSe han realizado: " . $nPagosMes;
      $alerta->save();
    }
    if ($pagomesGlobal->monto > $request->monto) {
      $alerta = new Alerta();
      $alerta->cliente_id = $request->id;
      $alerta->credito_id = $creditoId;
      $alerta->estatus = 1;
      $alerta->observacion = "";
      $alerta->prioridad = "Alta";
      $alerta->tipo_alerta = "Monto de pago";
      $alerta->titulo = "Monto de pago menor";
      $alerta->descripcion = "El monto debe ser: " . $pagomesGlobal->monto . "\nSe pago: " . $request->monto;
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
