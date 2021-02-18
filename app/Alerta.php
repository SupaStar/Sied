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
    $formita=$request->forma=="0"?0:$request->forma;
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

  public function validarRiesgo($id,$creditoId)
  {
    $edad = Edad::orderby('id', 'asc')->get();
    $edades = array();
    foreach ($edad as $value) {
      switch ($value->descripcion) {
        case 'MENORES 22 AÑOS':
          $edades['22'] = $value->id;
          break;
        case 'DE 23 A 30 AÑOS':
          $edades['23'] = $value->id;
          break;
        case 'DE 31 A 50 AÑOS':
          $edades['31'] = $value->id;
          break;
        case 'DE 51 A 99 AÑOS':
          $edades['51'] = $value->id;
          break;
        default:
          break;
      }
    }
    $gedad = Client::where('id', $id)->first()->date_birth;
    $birthDate = Carbon::createFromFormat('Y-m-d', $gedad);
    $currentDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d'));
    $diferencia = $currentDate->diffInYears($birthDate);
    $eid = 1;
    switch ($diferencia) {
      case $diferencia < 22:
        $eid = $edades['22'];
        break;
      case $diferencia < 31:
        $eid = $edades['23'];
        break;
      case $diferencia < 51:
        $eid = $edades['31'];
        break;
      case $diferencia > 50:
        $eid = $edades['51'];
        break;
      default:
        break;
    }
    $pactividad = DB::SELECT("select factor,
                      case when factor='Actividad o Giro' then actividad_giro.descripcion
                           when factor='Profesion' then profesion.descripcion
                      		 end as descripcion,
                      ponderacion,
                      case when factor='Actividad o Giro' then actividad_giro.puntaje
                           when factor='Profesion' then profesion.puntaje
                      		 end as puntaje,
                      format((
                      case when factor='Actividad o Giro' then actividad_giro.puntaje
                           when factor='Profesion' then profesion.puntaje
                      		 end
                      *(ponderacion/100)),2) as resultado
                      from ponderacion
                      LEFT JOIN perfil_transacional on perfil_transacional.cliente_id=$id
                      LEFT JOIN actividad_giro on actividad_giro.id=perfil_transacional.actividad_giro
                      LEFT JOIN profesion on profesion.id=perfil_transacional.profesion
                      where tipo='actividad'");
    $porigen = DB::SELECT("select factor,
                                case when factor='Origen de Recursos' then origen_recursos.descripcion
                                     when factor='Divisa' then divisa.descripcion
                                     when factor='Instrumento Monetario' then instrumento_monetario.descripcion
                                		 end as descripcion,
                                ponderacion,
                                case when factor='Origen de Recursos' then origen_recursos.puntaje
                                     when factor='Divisa' then divisa.puntaje
                                     when factor='Instrumento Monetario' then instrumento_monetario.puntaje
                                		 end as puntaje,

                                format((
                                case when factor='Origen de Recursos' then origen_recursos.puntaje
                                     when factor='Divisa' then divisa.puntaje
                                     when factor='Instrumento Monetario' then instrumento_monetario.puntaje
                                		 end
                                *(ponderacion/100)),2) as resultado
                                from ponderacion
                                LEFT JOIN perfil_transacional on perfil_transacional.cliente_id=$id
                                LEFT JOIN origen_recursos on origen_recursos.id=perfil_transacional.origen_recursos
                                LEFT JOIN divisa on divisa.id=perfil_transacional.divisa
                                LEFT JOIN instrumento_monetario on instrumento_monetario.id=perfil_transacional.instrumento_monetario
                                where tipo='origen'");
    $pantecedentes = DB::SELECT("select factor,
                            case when factor='Alertas PLD/FT' then pld.descripcion
                                 when factor='Edad' then edad.descripcion
                                 when factor='Antiguedad' then antiguedad.descripcion
                                 when factor='Personalidad Juridica' then personalidad_juridica.descripcion
                                 when factor='Confirmado Listas PEP Mexicano' then pep_mexicanas.descripcion
                                 when factor='Confirmado Listas PEP Extranjero' then pep_extranjeras.descripcion
                                 when factor='Nacionalidad' then nacionalidad_antecedentes.descripcion
                                 when factor='Entidad Federativa Residencia' then entidad_federativa_residencia.descripcion
                            		 end as descripcion,
                            ponderacion,
                            case when factor='Alertas PLD/FT' then pld.puntaje
                                 when factor='Edad' then edad.puntaje
                                 when factor='Antiguedad' then antiguedad.puntaje
                                 when factor='Personalidad Juridica' then personalidad_juridica.puntaje
                                 when factor='Confirmado Listas PEP Mexicano' then pep_mexicanas.puntaje
                                 when factor='Confirmado Listas PEP Extranjero' then pep_extranjeras.puntaje
                                 when factor='Nacionalidad' then nacionalidad_antecedentes.puntaje
                                 when factor='Entidad Federativa Residencia' then entidad_federativa_residencia.puntaje
                            		 end as puntaje,
                            format((
                            case when factor='Alertas PLD/FT' then pld.puntaje
                                 when factor='Edad' then edad.puntaje
                                 when factor='Antiguedad' then antiguedad.puntaje
                                 when factor='Personalidad Juridica' then personalidad_juridica.puntaje
                                 when factor='Confirmado Listas PEP Mexicano' then pep_mexicanas.puntaje
                                 when factor='Confirmado Listas PEP Extranjero' then pep_extranjeras.puntaje
                                 when factor='Nacionalidad' then nacionalidad_antecedentes.puntaje
                                 when factor='Entidad Federativa Residencia' then entidad_federativa_residencia.puntaje
                            		 end
                            *(ponderacion/100)),2) as resultado
                            from ponderacion
                            LEFT JOIN clientes on clientes.id=$id
                            LEFT JOIN perfil_transacional on perfil_transacional.cliente_id=$id
                            LEFT JOIN pld on pld.id=perfil_transacional.pld
                            LEFT JOIN edad on edad.id=$eid
                            LEFT JOIN antiguedad on antiguedad.id=clientes.antiguedad
                            LEFT JOIN personalidad_juridica on personalidad_juridica.descripcion='Persona Fisica'
                            LEFT JOIN pep_mexicanas on pep_mexicanas.descripcion='No'
                            LEFT JOIN pep_extranjeras on pep_extranjeras.descripcion='No'
                            LEFT JOIN nacionalidad_antecedentes on nacionalidad_antecedentes.descripcion='Mexicana'
                            LEFT JOIN entidad_federativa_residencia on entidad_federativa_residencia.id=perfil_transacional.efr
                            where tipo='antecedentes'");
    $pdestino = DB::SELECT("select factor, descripcion, ponderacion, puntaje, format((puntaje*(ponderacion/100)),2) as resultado
                            from ponderacion
                            LEFT JOIN perfil_transacional on perfil_transacional.cliente_id=$id
                            LEFT JOIN destino_recursos on destino_recursos.id=perfil_transacional.destino_recursos
                            where tipo='destino'");
    $antecedentesres = 0;
    $antecedentespon = 50;
    foreach ($pantecedentes as $value) {
      $antecedentesres = $antecedentesres + $value->resultado;
    }
    $actividadres = 0;
    $actividadpon = 17;
    foreach ($pactividad as $value) {
      $actividadres = $actividadres + $value->resultado;
    }
    $origenres = 0;
    $origenpon = 25;
    foreach ($porigen as $value) {
      $origenres = $origenres + $value->resultado;
    }
    $destinores = 0;
    $destinopon = 8;
    foreach ($pdestino as $value) {
      $destinores = $destinores + $value->resultado;
    }
    $destinorespon = $destinores * ($origenpon / 100);
    $origenrespon = $origenres * ($origenpon / 100);
    $actividadrespon = $actividadres * ($actividadpon / 100);
    $antecedentesponres = $antecedentesres * ($antecedentespon / 100);
    $totalrespon = $antecedentesponres + $actividadrespon + $origenrespon + $destinorespon;
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
    if ($totalrespon > $riesgos['MEDIO']) {
      $alerta = new Alerta();
      $alerta->cliente_id = $id;
      $alerta->credito_id = $creditoId;
      $alerta->estatus = 1;
      $alerta->observacion = "";
      $alerta->prioridad = "Alta";
      $alerta->tipo_alerta = "Cliente de riesgo alto";
      $alerta->titulo = "Cliente con un riesgo alto";
      $alerta->descripcion = "El cliente tiene una puntuacion de: " . $totalrespon."|El cual esta considerado como alto";
      $alerta->save();
    }
  }
}
