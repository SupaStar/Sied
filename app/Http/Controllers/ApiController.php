<?php

namespace App\Http\Controllers;

use App\Client;
use App\ConfigAlertas;
use App\Credito;
use App\Edad;
use App\Riesgo;
use App\Riesgos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

  public function alertasfecha(Request $request)
  {


    $finicio = Carbon::parse($request->fechainicio)->format('Y-m-d');
    $ffinal = Carbon::parse($request->fechafinal)->format('Y-m-d');
    $result = \App\Alerta::where("created_at", ">=", $finicio)->where("created_at", "<=", $ffinal)->get();

    foreach ($result as $r) {
      if ($r->estatus == 1) {
        $r->estatus = "Nuevo";
      } elseif ($r->estatus == 2) {
        $r->estatus = "Recabando información";
      } elseif ($r->estatus == 3) {
        $r->estatus = "En proceso";
      } elseif ($r->estatus == 4) {
        $r->estatus = "Observaciones";
      } elseif ($r->estatus == 5) {
        $r->estatus = "Concluido";
      } elseif ($r->estatus == 0) {
        $r->estatus = "Sin estatus";
      }
      if ($r->envio == 1) {
        $r->operacion = "Operación no preocupante";
        $r->cliente;
        $r->credito;
      } elseif ($r->envio == 2) {
        $r->operacion = "Operación inusual";
        $r->cliente;
        $r->credito;
      } elseif ($r->envio == 3) {
        $r->operacion = "Clientes Clasificados en el mayor grado de mayor riesgo";
        $r->cliente;
        $r->credito;
      } elseif ($r->envio == 4) {
        $r->operacion = "Operación clientes Clasificados en el mayor grado de mayor riesgo";
        $r->cliente;
        $r->credito;
      } elseif ($r->envio == 5) {
        $r->operacion = "Operaciones relevantes";
        $r->cliente;
        $r->credito;
      } elseif ($r->envio == 0) {
        $r->operacion = "Sin operación";
        $r->cliente;
        $r->credito;
      }
    }


    return datatables()->of($result)->addColumn('actions', function ($query) {

      return '
              <a href="#"  title="Editar"><button style="z-index:999" id="btnedita" value="' . $query->estatus . '"  aria-label="' . $query->observacion . '" name="' . $query->id . '" type="button" data-toggle="modal" data-target="#inlineForm" class="btn btn-default"><i class="feather icon-edit primary"></i></button></a>
              ';
    })->rawColumns(['actions'])->toJson();
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

  public function llenarRiesgos()
  {
    $clientes = Client::all();
    foreach ($clientes as $client) {
      $id = $client->id;
      $riesgoB = Riesgos::where('id_cliente', $id)->first();
      if ($riesgoB == null) {
        $credito = \App\Creditos::where('client_id', $id)->where('status', 'Aprobado')->first();
        if ($credito != null) {
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
          foreach ($pdestino as $value) {
            $destinores = $destinores + $value->resultado;
          }
          $destinorespon = $destinores * ($origenpon / 100);
          $origenrespon = $origenres * ($origenpon / 100);
          $actividadrespon = $actividadres * ($actividadpon / 100);
          $antecedentesponres = $antecedentesres * ($antecedentespon / 100);
          $totalrespon = $antecedentesponres + $actividadrespon + $origenrespon + $destinorespon;
          $riesgo = new Riesgos();
          $riesgo->id_cliente = $id;
          $riesgo->valor = $totalrespon;
          $riesgo->save();
        }
      }
    }
  }
}
