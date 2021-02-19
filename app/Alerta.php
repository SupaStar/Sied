<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Alerta extends Model
{
  protected $table = "alertas_pld";

  public function verificar(Request $request, $creditoId)
  {
    $perfil = Perfil::where('cliente_id', $request->id)->first();
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
    $pagomesGlobal = ConfigAlertas::find(1);
    $pagomesGlobal->valorUID();
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
    $pagomesGlobal = ConfigAlertas::find(1);
    $montoMax = $pagomesGlobal->valor * 7500;
    if ($request->monto > $montoMax) {
      $alerta = new Alerta();
      $alerta->cliente_id = $request->id;
      $alerta->credito_id = $creditoId;
      $alerta->estatus = 1;
      $alerta->observacion = "";
      $alerta->prioridad = "Alta";
      $alerta->tipo_alerta = "Cantidad de pago";
      $alerta->titulo = "El pago es muy grande";
      $alerta->descripcion = "Se realizo un pago de: " . $request->monto;
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
    $formita = $request->forma == "0" ? 0 : $request->forma;
    if ($formita === 0) {
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
          $alerta->descripcion = "Forma de pago registrada: " . $registrado->descripcion . "|Se uso: " . $formaPago->descripcion;
          $alerta->save();
        }
        if ($request->forma == "Nacional") {
          if ($request->lnacional !== "En la plaza") {
            $alerta->descripcion = "Forma de pago registrada: " . $registrado->descripcion . "|Se uso: " . $request->forma . "|En el lugar: " . $request->lnacional;
            $alerta->save();
          }
        } else {
          $alerta->descripcion = "Forma de pago registrada: " . $registrado->descripcion . "|Se uso: " . $request->forma . "|En el lugar: " . $request->linternacional;
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

  public function validarDestino(Request $request, $idC, $creditoId)
  {
    $perfil = Perfil::where("cliente_id", $idC)->first();
    if ($perfil->destino_recursos !== $request->recurso) {
      $alerta = new Alerta();
      $alerta->cliente_id = $idC;
      $alerta->credito_id = $creditoId;
      $alerta->estatus = 1;
      $alerta->observacion = "";
      $alerta->prioridad = "Alta";
      $alerta->tipo_alerta = "Destino";
      $alerta->titulo = "Destino recursos seleccionada diferente";
      $registrado = DestinoRecursos::find($perfil->destino_recursos);
      $seleccionado = DestinoRecursos::find($request->recurso);
      $alerta->descripcion = "Destino de pago registrado: " . $registrado->descripcion . "|Se uso: " . $seleccionado->descripcion;
      $alerta->save();
    }
  }

  public function cliente()
  {
    return $this->hasOne('\App\Client', 'id', 'cliente_id');
  }

  public function credito()
  {
    return $this->hasOne('\App\Creditos', 'id', 'credito_id');
  }

  public function validarRiesgo($id, $creditoId)
  {
    $riesgoBD = Riesgos::where('id_cliente', $id)->first();
    if ($riesgoBD != null) {
      $riesgos = array();
      $riesgo = Riesgo::orderby('id', 'asc')->get();
      foreach ($riesgo as $value) {
        switch ($value->riesgo) {
          case 'BAJO':
            $riesgos['BAJO'] = $value->maximo;
            break;
          case 'MEDIO':
            $riesgos['MEDIO'] = $value->maximo;
            break;
          default:
            // code...
            break;
        }
      }
      if ($riesgoBD->valor > $riesgos['MEDIO']) {
        $alerta = new Alerta();
        $alerta->cliente_id = $id;
        $alerta->credito_id = $creditoId;
        $alerta->estatus = 1;
        $alerta->observacion = "";
        $alerta->prioridad = "Alta";
        $alerta->tipo_alerta = "Cliente de riesgo alto";
        $alerta->titulo = "Cliente con un riesgo alto";
        $alerta->descripcion = "El cliente tiene una puntuacion de: " . $riesgoBD->valor . "|El cual esta considerado como alto";
        $alerta->save();
      }
    }

  }
}
