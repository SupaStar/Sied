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
    $perfil = Perfil::find($request->id);
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
      $alerta->descripcion = "Moneda de pago registrada: " . $registrado->descripcion . "|Se uso: " . $divisaP . " con nombre: " . $request->nmoneda;
      $alerta->save();
    }
    if ($request->origen == "No identificado") {
      $alerta = new Alerta();
      $alerta->cliente_id = $request->id;
      $alerta->credito_id = $creditoId;
      $alerta->estatus = 1;
      $alerta->observacion = "";
      $alerta->prioridad = "Alta";
      $alerta->tipo_alerta = "Origen de recursos";
      $alerta->titulo = "Origen de recursos peligroso";
      $alerta->descripcion = "Se registro un origen de recursos: " . $request->origen;
      $alerta->save();
    } else {
      if ($request->origen == "Cuentas de terceros") {
        if ($request->cterceros == "Relacionados en listas negras") {
          $alerta = new Alerta();
          $alerta->cliente_id = $request->id;
          $alerta->credito_id = $creditoId;
          $alerta->estatus = 1;
          $alerta->observacion = "";
          $alerta->prioridad = "Alta";
          $alerta->tipo_alerta = "Origen de recursos";
          $alerta->titulo = "Origen de recursos peligroso";
          $alerta->descripcion = "Se registro un origen de recursos: " . $request->origen . "|De tipo: " . $request->cterceros;
          $alerta->save();
        } else {
          $alerta = new Alerta();
          $alerta->cliente_id = $request->id;
          $alerta->credito_id = $creditoId;
          $alerta->estatus = 1;
          $alerta->observacion = "";
          $alerta->prioridad = "Alta";
          $alerta->tipo_alerta = "Origen de recursos";
          $alerta->titulo = "Origen de recursos peligroso";
          $alerta->descripcion = "Se registro un origen de recursos: " . $request->origen . "|De tipo: Otros ," . $request->cterceros;
          $alerta->save();
        }
      }
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
      $alerta->descripcion = "Se pueden realizar: " . $numeroPagoMes . " pagos por mes|Se han realizado: " . $nPagosMes;
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
      $alerta->descripcion = "El monto debe ser: " . $pagomesGlobal->monto . "|Se pago: " . $request->monto;
      $alerta->save();
    }
    if ($request->forma === 0) {
      $alerta = new Alerta();
      $alerta->cliente_id = $request->id;
      $alerta->credito_id = $creditoId;
      $alerta->estatus = 1;
      $alerta->observacion = "";
      $alerta->prioridad = "Alta";
      $alerta->tipo_alerta = "FormaPago";
      $alerta->titulo = "Forma de pago seleccionada diferente";
      $registrado = InstrumentoMonetario::find($perfil->instrumento_monetario);
      $alerta->descripcion = "Forma de pago registrada: " . $registrado->descripcion . "|Se uso: " . $request->nforma;
      $alerta->save();
    } else {
      $forma = $request->forma == "Nacional" || $request->forma == "Internacional" ? "Transferencia" : $request->forma;
      $formaPago = InstrumentoMonetario::where("descripcion", $forma)->first();
      if ($formaPago->id == 2) {
        $alerta = new Alerta();
        $alerta->cliente_id = $request->id;
        $alerta->credito_id = $creditoId;
        $alerta->estatus = 1;
        $alerta->observacion = "";
        $alerta->prioridad = "Alta";
        $alerta->tipo_alerta = "FormaPago";
        $alerta->titulo = "Forma de pago seleccionada diferente";
        $registrado = InstrumentoMonetario::find($perfil->instrumento_monetario);
        if ($perfil->instrumento_monetario !== $formaPago->id) {
          $alerta->descripcion = "Forma de pago registrada: " . $registrado->descripcion . "|Se uso: " . $request->forma;
          $alerta->save();
        }
        if ($request->forma == "Nacional") {
          if ($request->lnacional !== "En la plaza") {
            $alerta->descripcion = "Forma de pago registrada: " . $registrado->descripcion . "|Se uso: " . $request->forma . "|En el lugar: " . $request->lnacional;
            $alerta->save();
          }
        } else {
          $alerta->descripcion = "Forma de pago registrada: " . $registrado->descripcion . "|Se uso: " . $request->forma . "|En el lugar: " . $request->lnacional;
          $alerta->save();
        }
      } else {
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
          $alerta->descripcion = "Forma de pago registrada: " . $registrado->descripcion . "|Se uso: " . $request->forma;
          $alerta->save();
        }
      }
    }
  }
  public function cliente()
  {
    return $this->hasOne('\App\Client', 'id','cliente_id');
  }
  public function credito()
  {
    return $this->hasOne('\App\Credito', 'id', 'credito_id');
  }
}
