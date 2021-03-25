<?php

namespace App\Http\Controllers;

use App\DestinoCredito;
use App\Riesgos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Files;
use Image;
use App\Client;
use App\ListaNegra;
use App\Perfil;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use PDF;
use App\Creditos;
use App\Pago;
use App\OrigenRecursos;
use App\DestinoRecursos;
use App\InstrumentoMonetario;
use App\Divisa;
use App\Profesion;
use App\ActividadGiro;
use App\Antiguedad;
use App\Riesgo;
use App\NacionalidadAntecedentes;
use App\EFResidencia;
use App\EntidadFederativa;
use App\Edad;
use Carbon\Carbon;
use App\Pld;
use App\PepExtranjeras;
use App\PepMexicanas;
use App\PersonalidadJuridica;
use App\ComprobantePago;
use App\Amortizacion;
use App\RelacionPagos;
use App\HistorialFlujos;

class Clients extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('checkStatus');
  }

  public function fisicas()
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageName' => 'Personas Fisicas'
    ];

    return view('/clients/fisicas', [
      'pageConfigs' => $pageConfigs
    ]);
  }

  public function info($id)
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageName' => 'Personas Fisicas'
    ];
    $datos = db::table('clientes')->where('id', $id)->first();
    $credito = db::table('credito')->where('client_id', $id)->where('status', 'Aprobado')->first();
    $contrato = isset($credito->contrato) ? $credito->contrato : 'S / C';
    $nacionalidades = db::table('nacionalidades')->get();
    $paises = db::table('paises')->get();
    $entidad = db::table('entidad_federativa')->get();
    $datos = db::table('clientes')->where('id', $id)->first();
    $divisa = Divisa::get();

    $images = db::table('files')->where('client_id', $id)->where('type', 'INE')->get();

    $ine1 = null;
    $ine2 = null;

    $cc = 0;
    foreach ($images as $ines) {
      if ($cc == 0) {
        $ine1 = $ines->name;

      } else {
        $ine2 = $ines->name;
      }
      $cc++;
    }

    $gpasaporte = db::table('files')->where('client_id', $id)->where('type', 'PASAPORTE')->first();
    $pasaporte = null;
    if (isset($gpasaporte->name)) {
      $pasaporte = $gpasaporte->name;
    }

    return view('/clients/info', [
      'pageConfigs' => $pageConfigs,
      'nacionalidades' => $nacionalidades,
      'nombre' => $datos->name . ' ' . $datos->lastname . ' ' . $datos->o_lastname,
      'contrato' => $contrato,
      'paises' => $paises,
      'divisa' => $divisa,
      'entidad' => $entidad,
      'datos' => $datos,
      'miid' => $id,
      'ine1' => $ine1,
      'ine2' => $ine2,
      'pasaporte' => $pasaporte,
      'id' => $id
    ]);
  }

  public function fperfil($id, $redireccion = 0)
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageName' => 'Personas Fisicas'
    ];
    $datos = Perfil::where('cliente_id', '=', $id)->first();
    $origen = OrigenRecursos::get();
    $destino = DestinoRecursos::get();
    $instrumento = InstrumentoMonetario::get();
    $divisa = Divisa::get();
    $profesiones = Profesion::get();
    $actividad = ActividadGiro::get();
    $profesion = DB::TABLE('clientes')->where('id', $id)->first()->job;


    if (isset($datos)) {
      return view('/clients/perfil', compact(
        'pageConfigs',
        'id',
        'datos',
        'origen',
        'instrumento',
        'divisa',
        'destino',
        'profesiones',
        'profesion',
        'actividad',
        'redireccion'
      ));
    } else {
      return view('/clients/perfil', compact(
        'pageConfigs',
        'id',
        'origen',
        'instrumento',
        'divisa',
        'destino',
        'profesiones',
        'profesion',
        'actividad',
        'redireccion'
      ));
    }
  }

  public function ebr($id)
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageName' => 'Criterios de Riesgos'
    ];
    $datos = Perfil::where('cliente_id', '=', $id)->first();
    $origen = OrigenRecursos::get();
    $destino = DestinoRecursos::get();
    $instrumento = InstrumentoMonetario::get();
    $divisa = Divisa::get();
    $profesiones = Profesion::get();
    $actividad = ActividadGiro::get();
    $efresidencia = EFResidencia::get();
    $gresidencia = Client::where('id', $id)->first()->ef;
    $residencia = EntidadFederativa::where('code', $gresidencia)->first()->entity;
    $profesion = DB::TABLE('clientes')->where('id', $id)->first()->job;
    $redureccion = true;
    if (isset($datos)) {
      return view('/clients/ebr', compact(
        'pageConfigs',
        'id',
        'datos',
        'origen',
        'instrumento',
        'divisa',
        'destino',
        'profesiones',
        'profesion',
        'efresidencia',
        'residencia',
        'actividad'
      ));
    } else {
      return view('/clients/ebr', compact(
        'pageConfigs',
        'id',
        'origen',
        'instrumento',
        'divisa',
        'destino',
        'profesiones',
        'profesion',
        'efresidencia',
        'residencia',
        'actividad',
        'redureccion'
      ));
    }
  }

  public function friesgo($id)
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageName' => 'Personas Fisicas'
    ];

    $riesgo = Riesgo::orderby('id', 'asc')->get();
    $destino = DestinoRecursos::orderby('id', 'asc')->get();
    $origen = OrigenRecursos::orderby('id', 'asc')->get();
    $divisa = Divisa::orderby('id', 'asc')->get();
    $imonetario = InstrumentoMonetario::orderby('id', 'asc')->get();
    $actividad = ActividadGiro::orderby('id', 'asc')->get();
    $profesion = Profesion::orderby('id', 'asc')->get();
    $edad = Edad::orderby('id', 'asc')->get();
    $pld = Pld::orderby('id', 'asc')->get();
    $pepmx = PepMexicanas::orderby('id', 'asc')->get();
    $pepex = PepExtranjeras::orderby('id', 'asc')->get();
    $anacionalidad = NacionalidadAntecedentes::orderby('id', 'asc')->get();
    $antiguedad = Antiguedad::orderby('id', 'asc')->get();
    $personalidad = PersonalidadJuridica::orderby('id', 'asc')->get();
    $entidad = EFResidencia::orderby('id', 'asc')->get();

    $gedad = Client::where('id', $id)->first()->date_birth;

    $currentDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d'));
    $birthDate = Carbon::createFromFormat('Y-m-d', $gedad);
    $diferencia = $currentDate->diffInYears($birthDate);

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


    $pdestino = DB::SELECT("select factor, descripcion, ponderacion, puntaje, format((puntaje*(ponderacion/100)),2) as resultado
                            from ponderacion
                            LEFT JOIN perfil_transacional on perfil_transacional.cliente_id=$id
                            LEFT JOIN destino_recursos on destino_recursos.id=perfil_transacional.destino_recursos
                            where tipo='destino'");

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

    $antecedentesres = 0;
    $antecedentespon = 50;
    foreach ($pantecedentes as $value) {
      $antecedentesres = $antecedentesres + $value->resultado;
    }
    $antecedentesponres = $antecedentesres * ($antecedentespon / 100);


    $actividadres = 0;
    $actividadpon = 17;
    foreach ($pactividad as $value) {
      $actividadres = $actividadres + $value->resultado;
    }
    $actividadrespon = $actividadres * ($actividadpon / 100);


    $origenres = 0;
    $origenpon = 25;
    foreach ($porigen as $value) {
      $origenres = $origenres + $value->resultado;
    }
    $origenrespon = $origenres * ($origenpon / 100);

    $destinores = 0;
    $destinopon = 8;
    foreach ($pdestino as $value) {
      $destinores = $destinores + $value->resultado;
    }
    $destinorespon = $destinores * ($origenpon / 100);

    $totalres = $antecedentesres + $actividadres + $origenres + $destinores;
    $totalpon = $antecedentespon + $actividadpon + $origenpon + $destinopon;
    $totalrespon = $antecedentesponres + $actividadrespon + $origenrespon + $destinorespon;

    $riesgos = array();
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

    if ($totalrespon < $riesgos['BAJO']) {
      $criesgo = 'BAJO';
    } elseif ($totalrespon < $riesgos['MEDIO']) {
      $criesgo = 'MEDIO';
    } else {
      $criesgo = 'ALTO';
    }

    return view('/clients/riesgo',
      compact('pageConfigs', 'riesgo', 'destino', 'origen', 'divisa', 'imonetario', 'actividad', 'profesion', 'pdestino', 'porigen', 'pactividad', 'pantecedentes',
        'edad', 'pld', 'pepmx', 'pepex', 'anacionalidad', 'antiguedad', 'personalidad', 'entidad',
        'antecedentesres', 'antecedentespon', 'antecedentesponres', 'actividadres', 'actividadpon',
        'actividadrespon', 'origenres', 'origenpon', 'origenrespon', 'destinores', 'destinopon', 'destinorespon',
        'totalres', 'totalpon', 'totalrespon', 'criesgo')
    );
  }

  public function getfisicas(Request $request)
  {

    if ($request->filtro == 'Archivados') {
      $result = Client::with('listasNegras', 'grupo')->where('status', 'Archivado')->whereNotNull('email')->where(function ($query) {
        $query->where('suma_estado', '<>', 'ManualChecking')
          ->orWhereNull('suma_estado');
      });
    } elseif ($request->filtro == 'H') {
      $result = Client::with('listasNegras', 'grupo')->where('gender', 'H')->whereNotNull('email')->where(function ($query) {
        $query->where('suma_estado', '<>', 'ManualChecking')
          ->orWhereNull('suma_estado');
      });
    } elseif ($request->filtro == 'M') {
      $result = Client::with('listasNegras', 'grupo')->where('gender', 'M')->whereNotNull('email')->where(function ($query) {
        $query->where('suma_estado', '<>', 'ManualChecking')
          ->orWhereNull('suma_estado');
      });
    } else {
      $result = Client::with('listasNegras', 'grupo')->where('status', '<>', 'Archivado')->whereNotNull('email')->where(function ($query) {
        $query->where('suma_estado', '<>', 'ManualChecking')
          ->orWhereNull('suma_estado');
      });
    }

    return datatables()->of($result)
      ->addColumn('names', function ($query) {
        return strtoupper($query->name . ' ' . $query->lastname . ' ' . $query->o_lastname);
      })
      ->addColumn('blacklist', function ($query) {
        $result = '<button title="Listas Negras" onclick="noblacklist()" style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-check-circle success"></i></button>';
        if (sizeof($query->listasNegras) > 0) {
          $result = '<a title="Listas Negras" target="_blank" href="/clientes/listaNegraPDF/' . $query->id . '" style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-alert-circle danger"></i></a>';
        }
        return $result;
      })
      ->addColumn('grupo', function ($query) {
        $text = " - ";
        if (isset($query->grupo)) {
          $text = $query->grupo->nombre;
        }
        return $text;
      })
      ->addColumn('status', function ($query) {

        $perfil = DB::TABLE('perfil_transacional')->where('cliente_id', $query->id)->first();
        $credito = DB::TABLE('credito')->where('client_id', $query->id)->where('status', '<>', 'liquidado')->first();
        $text = " - ";

        if (isset($perfil)) {
          if (empty($perfil->monto) || empty($perfil->tcredito) || empty($perfil->frecuencia) || empty($perfil->instrumento_monetario) || empty($perfil->origen_recursos) || empty($perfil->destino_recursos) || empty($perfil->divisa)) {
            $text = 'Pendiente <br> <a href="/clientes/fisicas/perfil/' . $query->id . '" class="warning">Perfil Transacional</a>';
          } else if (empty($perfil->profesion) || empty($perfil->actividad_giro) || empty($perfil->efr)) {
            $text = 'Pendiente <br> <a href="/clientes/fisicas/ebr/' . $query->id . '" class="warning">Criterios de Riesgo</a>';
          } else if (isset($credito)) {
            $text = 'Aprobado <br> <a href="/clientes/fisicas/info/' . $query->id . '" class="warning">Información</a>';
          } else {
            $text = 'Pendiente <br> <a href="/clientes/continuar/' . $query->id . '" class="warning">Credito</a>';
          }
        } else {
          $text = 'Pendiente <br> <a href="/clientes/fisicas/perfil/' . $query->id . '" class="warning">Perfil Transacional</a>';
        }

        return $text;

      })
      ->addColumn('actions', function ($query) {
        $user = Auth::user();
        return '
              <a href="/clientes/fisicas/info/' . $query->id . '" title="Información"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-eye"></i></button></a>
              <a href="/clientes/fisicas/perfil/' . $query->id . '" title="Perfil Transacional"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-file success"></i></button></a>
              <a href="/clientes/fisicas/ebr/' . $query->id . '" title="Criterios de Riesgos"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-info info"></i></button></a>
              <a href="/clientes/fisicas/riesgo/' . $query->id . '" title="Grado de Riesgo"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-bar-chart-2 warning"></i></button></a>
              <button title="Descarga Archivos" onclick="files(' . $query->id . ');" style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-archive primary"></i></button>
              <a href="/clientes/fisicas/editar/' . $query->id . '" title="Editar"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-edit primary"></i></button></a>
              <button title="Archivar" onclick="del(' . $query->id . ');" style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-trash danger"></i></button>';
      })
      ->rawColumns(['actions', 'names', 'status', 'blacklist'])
      ->toJson();

    if ($request->filtro == 'Archivados') {
      $result = DB::table('clientes')->where('status', 'Archivado');
    } elseif ($request->filtro == 'H') {
      $result = DB::table('clientes')->where('gender', 'H');
    } elseif ($request->filtro == 'M') {
      $result = DB::table('clientes')->where('gender', 'M');
    } else {
      $result = DB::table('clientes')->where('status', '<>', 'Archivado');;
    }

    return datatables()->of($result)
      ->addColumn('names', function ($query) {
        return strtoupper($query->name . ' ' . $query->lastname . ' ' . $query->o_lastname);
      })
      ->addColumn('actions', function ($query) {
        $user = Auth::user();
        return '
              <a href="/clientes/fisicas/info/' . $query->id . '" title="Información"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-eye"></i></button></a>
              <a href="/clientes/fisicas/perfil/' . $query->id . '" title="Perfil Transacional"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-file success"></i></button></a>
              <a href="/clientes/fisicas/riesgo/' . $query->id . '" title="Grado de Riesgo"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-bar-chart-2 warning"></i></button></a>
              <button title="Descarga Archivos" onclick="files(' . $query->id . ');" style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-archive primary"></i></button>
              <a href="/clientes/fisicas/editar/' . $query->id . '" title="Editar"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-edit primary"></i></button></a>
              <button title="Archivar" onclick="del(' . $query->id . ');" style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-trash danger"></i></button>';
      })
      ->rawColumns(['actions', 'names'])
      ->toJson();
  }

  public function pendientes()
  {
    $result = Client::with('listasNegras', 'grupo')->whereNull('email')->where(function ($query) {
      $query->where('status', '<>', 'Archivado')
        ->orWhereNull('status');
    });

    return datatables()->of($result)
      ->addColumn('blacklist', function ($query) {
        $result = '<button title="Listas Negras" onclick="noblacklist()" style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-check-circle success"></i></button>';
        if (sizeof($query->listasNegras) > 0) {
          $result = '<a title="Listas Negras" target="_blank" href="/clientes/listaNegraPDF/' . $query->id . '" style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-alert-circle danger"></i></a>';
        }
        return $result;
      })
      ->addColumn('identificacion', function ($query) {

        $images = db::table('files')->where('client_id', $query->id)->where('type', 'INE')->get();

        $ine1 = null;
        $ine2 = null;

        $cc = 0;
        foreach ($images as $ines) {
          if ($cc == 0) {
            $ine1 = $ines->name;

          } else {
            $ine2 = $ines->name;
          }
          $cc++;
        }

        $gpasaporte = db::table('files')->where('client_id', $query->id)->where('type', 'PASAPORTE')->first();
        $pasaporte = null;
        if (isset($gpasaporte->name)) {
          $pasaporte = $gpasaporte->name;
        }
        $images = '';


        if ($ine1) {
          $images .= '<div class="col-3 text-center">
            <a href="/uploads/fisicas/ine/' . $ine1 . '" target="_blank"> <img src="/uploads/fisicas/ine/' . $ine1 . '" alt="INE" height="100"></a>
          </div>';
        }
        if ($ine2) {
          $images .= '<div class="col-3 text-center">
            <a href="/uploads/fisicas/ine/' . $ine2 . '" target="_blank"> <img src="/uploads/fisicas/ine/' . $ine2 . '" alt="INE" height="100"></a>
          </div>';
        }
        if ($pasaporte) {
          $images .= '<div class="col-3 text-center">
          <a href="/uploads/fisicas/pasaporte/' . $pasaporte . '" target="_blank"> <img src="/uploads/fisicas/pasaporte/' . $pasaporte . '" alt="PASAPORTE" height="100"></a>
        </div>';
        }
        return $images;
      })
      ->addColumn('acciones', function ($query) {
        $user = Auth::user();
        if ($query->suma_estado == 'Checked') {
          return '
        <a href="/clientes/fisicas/continuar/registro/' . $query->id . '" title="Continuar Registro"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-edit primary"></i></button></a>
        <button title="Archivar" onclick="del(' . $query->id . ');" style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-trash danger"></i></button>';
        } else {
          return '<button title="Archivar" onclick="del(' . $query->id . ');" style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-trash danger"></i></button>';
        }
      })
      ->rawColumns(['blacklist', 'identificacion', 'acciones'])
      ->toJson();
  }


  public function eperfil(Request $request)
  {
    $cid = $request->id;

    $user = Auth::user();

    $args = array(
      'monto' => $request->monto ? $request->monto : null,
      'tcredito' => $request->tcredito ? $request->tcredito : null,
      'frecuencia' => $request->frecuencia ? $request->frecuencia : null,
      'actividad' => false,
      'propietario' => false,
      'proovedor' => false,
      'dactividad' => false,
      'dpasivos' => false,
      'dotro' => false,
      'total' => false,
      'aceptable' => false,
      'difisil' => false,
      'conducta' => $request->conducta ? $request->conducta : null,
      'ingreso' => $request->ingreso ? $request->ingreso : null,
      'comentario' => $request->comentario ? $request->comentario : null,
      'origen_recursos' => $request->orecursos ? $request->orecursos : null,
      'destino_recursos' => $request->drecursos ? $request->drecursos : null,
      'instrumento_monetario' => $request->imonetario ? $request->imonetario : null,
      'divisa' => $request->divisa ? $request->divisa : null

    );

    switch ($request->disponibilidad) {
      case 'total':
        $args['total'] = true;
        break;
      case 'aceptable':
        $args['aceptable'] = true;
        break;
      case 'difisil':
        $args['difisil'] = true;
        break;
    }


    $fields = array(
      'cliente_id' => $cid
    );

    $update = Perfil::updateOrCreate($fields, $args);
    $riesgos = new Riesgos();
    $riesgos->editarGrado($cid);
    return redirect('/clientes/fisica')->with('perfil', 'OK');
  }

  public function eebr(Request $request)
  {
    $cid = $request->id;

    $user = Auth::user();

    $args = array(
      'profesion' => $request->profesion ? $request->profesion : null,
      'actividad_giro' => $request->actividad ? $request->actividad : null,
      'efr' => $request->efr ? $request->efr : null
    );

    $fields = array(
      'cliente_id' => $cid
    );

    $update = Perfil::updateOrCreate($fields, $args);
    $riesgos = new Riesgos();
    $riesgos->editarGrado($cid);
    return redirect('/clientes/fisica')->with('ebr', 'OK');
  }

  public function restaurarAmortizacion(Request $request)
  {
    $id = $request->id;

    Amortizacion::where('cliente_id', $id)->delete();

    $pagos = Pago::where('client_id', $id)->get();

    foreach ($pagos as $pago) {
      RelacionPagos::where('pago_id', $pago->id)->delete();
      ComprobantePago::where('pago_id', $pago->id)->delete();
    }

    Pago::where('client_id', $id)->delete();

    return response()->json(['message' => 'ok'], 200);
  }

  public function editado(Request $request)
  {

    $bith = substr($request->fnacimiento, 6, 4) . '-' . substr($request->fnacimiento, 0, 2) . '-' . substr($request->fnacimiento, 3, 2);
    $dd = date('Y-m-d', strtotime($bith));
    $cid = $request->id;
    if ($request->id = !null) {
      $cliente = Client::find($request->id);
    } else {
      $cliente = new Client;
    }

    $user = Auth::user();

    $cliente->name = strtoupper($request->nombre);
    $cliente->lastname = strtoupper($request->apellidop);
    $cliente->o_lastname = strtoupper($request->apellidom);
    $cliente->gender = strtoupper($request->genero);
    $cliente->date_birth = $dd;
    $cliente->country_birth = $request->pais_nacimiento;
    $cliente->nationality = strtoupper($request->lnacimiento);
    $cliente->place_birth = strtoupper($request->nacionalidad);
    $cliente->job = strtoupper($request->ocupacion);
    $cliente->street = strtoupper($request->calle);
    $cliente->exterior = $request->exterior;
    $cliente->inside = $request->interior;
    $cliente->pc = $request->cp;
    $cliente->colony = strtoupper($request->colonia);
    $cliente->town = strtoupper($request->municipio);
    $cliente->city = strtoupper($request->ciudad);
    $cliente->ef = strtoupper($request->entidad);
    $cliente->country = strtoupper($request->pais);
    $cliente->phone1 = $request->telefono1;
    $cliente->phone2 = $request->telefono2;
    $cliente->email = strtoupper($request->memail);
    $cliente->curp = strtoupper($request->curp);
    $cliente->rfc = strtoupper($request->rfc);
    $cliente->c_name = strtoupper($request->cnombre);
    $cliente->c_lastname = strtoupper($request->capellidop);
    $cliente->c_o_lastname = strtoupper($request->capellidom);
    $cliente->c_phone = $request->ctelefono;
    $cliente->c_email = strtoupper($request->cemail);
    $cliente->save();
    $riesgos = new Riesgos();
    $riesgos->editarGrado($cid);
    $fileine = $request->file('inefront') ? $request->file('inefront') : 1;
    $ineback = $request->file('ineback') ? $request->file('ineback') : 1;
    $filecurp = $request->file('filecurp') ? $request->file('filecurp') : 1;
    $filedom = $request->file('filedom') ? $request->file('filedom') : 1;
    $filecom1 = $request->file('filecom1') ? $request->file('filecom1') : 1;
    $filecom2 = $request->file('filecom2') ? $request->file('filecom2') : 1;
    $filecom3 = $request->file('filecom3') ? $request->file('filecom3') : 1;
    $filerfc = $request->file('filerfc') ? $request->file('filerfc') : 1;

    if ($fileine != 1) {
      $path = 'fisicas/ine';
      $extension = ("jpg");
      //$extension = strtolower($fileine->getClientOriginalExtension());
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '-frontal.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'INE';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->save();

        $image = Image::make(File::get($fileine));
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });

        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode($extension, 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'INE';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '-frontal.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($fileine));
      }
    }

    if ($ineback != 1) {
      $path = 'fisicas/ine';
      $extension = ("jpg");
      // $extension = strtolower($ineback->getClientOriginalExtension());
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '-trasera.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'INE';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->save();

        $image = Image::make(File::get($ineback));
        $image->insert(public_path('images/confidencial.png'), 'center');
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });

        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode($extension, 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'INE';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '-trasera.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($ineback));
      }
    }

    if ($filecurp != 1) {
      $path = 'fisicas/curp';
      $extension = ("jpg");
      //$extension = strtolower($filecurp->getClientOriginalExtension());
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'CURP';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->save();

        $image = Image::make(File::get($filecurp));
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        })->insert(public_path('images/confidencial.png'), 'center');
        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode($extension, 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'CURP';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($filecurp));
      }
    }

    if ($filedom != 1) {
      $path = 'fisicas/dom';
      $extension = ("jpg");
      //$extension = strtolower($filedom->getClientOriginalExtension());
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'COMPROBANTE DOMICILIO';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->save();

        $image = Image::make(File::get($filedom));
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode($extension, 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'COMPROBANTE DOMICILIO';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($filedom));
      }
    }

    if ($filecom1 != 1) {
      $path = 'fisicas/com1';
      $extension = ("jpg");
      //  $extension = strtolower($filecom1->getClientOriginalExtension());
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'COMPROBANTE DE INGRESO';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->save();

        $image = Image::make(File::get($filecom1));
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode($extension, 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'COMPROBANTE DE INGRESO';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($filecom1));
      }
    }

    if ($filecom2 != 1) {
      $path = 'fisicas/com2';
      $extension = ("jpg");
      //$extension = strtolower($filecom2->getClientOriginalExtension());
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = '2 COMPROBANTE DE INGRESO';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->save();

        $image = Image::make(File::get($filecom2));
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode($extension, 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = '2 COMPROBANTE DE INGRESO';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($filecom2));
      }
    }

    if ($filecom3 != 1) {
      $path = 'fisicas/com3';
      $extension = ("jpg");
      //$extension = strtolower($filecom3->getClientOriginalExtension());
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = '3 COMPROBANTE DE INGRESO';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->save();

        $image = Image::make(File::get($filecom3));
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode($extension, 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = '3 COMPROBANTE DE INGRESO';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($filecom3));
      }
    }

    if ($filerfc != 1) {
      $path = 'fisicas/rfc';
      $extension = ("jpg");
      // $extension = strtolower($filerfc->getClientOriginalExtension());
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'RFC';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->save();

        $image = Image::make(File::get($filerfc));
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode($extension, 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'RFC';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($filerfc));
      }
    }

    return redirect('/clientes/fisica')->with('message', 'OK');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function editar($id)
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageHeader' => true,
      'pageName' => 'Editar Cliente'
    ];

    $nacionalidades = db::table('nacionalidades')->get();
    $paises = db::table('paises')->get();
    $entidad = db::table('entidad_federativa')->get();
    $datos2 = db::table('clientes')->where('id', $id)->first();
    $datos = Perfil::where('cliente_id', '=', $id)->first();
    $origen = OrigenRecursos::get();
    $destino = DestinoRecursos::get();
    $instrumento = InstrumentoMonetario::get();
    $divisa = Divisa::get();
    $profesiones = Profesion::get();
    $actividad = ActividadGiro::get();
    $profesion = DB::TABLE('clientes')->where('id', $id)->first()->job;
    $actividad = ActividadGiro::get();
    $efresidencia = EFResidencia::get();
    $gresidencia = Client::where('id', $id)->first()->ef;
    $residencia = EntidadFederativa::where('code', $gresidencia)->first()->entity;

    if (isset($datos)) {

      return view('/clients/fisicas-editar', compact(
        'pageConfigs',
        'id',
        'datos',
        'datos2',
        'origen',
        'instrumento',
        'divisa',
        'destino',
        'profesiones',
        'profesion',
        'actividad',
        'nacionalidades',
        'paises',
        'entidad', 'profesion',
        'efresidencia',
        'residencia',
        'actividad'
      ));
    }
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function newfisica()
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageHeader' => true,
      'pageName' => 'Nueva Persona Fisica'
    ];

    $nacionalidades = db::table('nacionalidades')->get();
    $paises = db::table('paises')->get();
    $entidad = db::table('entidad_federativa')->get();
    $antiguedad = Antiguedad::get();

    return view('/clients/new-fisica', [
      'pageConfigs' => $pageConfigs,
      'nacionalidades' => $nacionalidades,
      'paises' => $paises,
      'entidad' => $entidad,
      'antiguedad' => $antiguedad
    ]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function ContinuarRegistro($id)
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageHeader' => true,
      'pageName' => 'Continuar Registro'
    ];

    $nacionalidades = db::table('nacionalidades')->get();
    $paises = db::table('paises')->get();
    $entidad = db::table('entidad_federativa')->get();
    $antiguedad = Antiguedad::get();
    $cliente = Client::where('id', $id)->first();

    $images = db::table('files')->where('client_id', $id)->where('type', 'INE')->get();

    $ine1 = null;
    $ine2 = null;

    $cc = 0;
    foreach ($images as $ines) {
      if ($cc == 0) {
        $ine1 = $ines->name;

      } else {
        $ine2 = $ines->name;
      }
      $cc++;
    }

    $gpasaporte = db::table('files')->where('client_id', $id)->where('type', 'PASAPORTE')->first();
    $pasaporte = null;
    if (isset($gpasaporte->name)) {
      $pasaporte = $gpasaporte->name;
    }


    return view('/clients/continuar-fisica', [
      'pageConfigs' => $pageConfigs,
      'nacionalidades' => $nacionalidades,
      'paises' => $paises,
      'entidad' => $entidad,
      'antiguedad' => $antiguedad,
      'cliente' => $cliente,
      'ine1' => $ine1,
      'ine2' => $ine2,
      'pasaporte' => $pasaporte,
    ]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function editarfisica()
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageHeader' => true,
      'pageName' => 'Editar'
    ];

    $nacionalidades = db::table('nacionalidades')->get();
    $paises = db::table('paises')->get();
    $entidad = db::table('entidad_federativa')->get();

    return view('/clients/new-fisica', [
      'pageConfigs' => $pageConfigs,
      'nacionalidades' => $nacionalidades,
      'paises' => $paises,
      'entidad' => $entidad,
    ]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $user = DB::TABLE('users')->where('id', $id)->first();

    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageHeader' => true,
      'pageName' => 'Editar Usuario '
    ];
    return view('/users/edit-user', [
      'pageConfigs' => $pageConfigs,
      'user' => $user
    ]);
  }

  public function create(Request $request)
  {
    /*$request->validate([
        'email' => 'required|string|email|unique:users'
      ]);*/

    $idc = $request->idc ? $request->idc : null;

    $bith = substr($request->fnacimiento, 6, 4) . '-' . substr($request->fnacimiento, 0, 2) . '-' . substr($request->fnacimiento, 3, 2);
    $dd = date('Y-m-d', strtotime($bith));

    if (!empty($idc)) {
      $cliente = Client::find($idc);
    } else {
      $cliente = new Client();
    }

    $cliente->name = strtoupper($request->nombre);
    $cliente->lastname = strtoupper($request->apellidop);
    $cliente->o_lastname = strtoupper($request->apellidom);
    $cliente->gender = strtoupper($request->genero);
    $cliente->date_birth = $dd;
    $cliente->country_birth = $request->pais_nacimiento;
    $cliente->nationality = strtoupper($request->lnacimiento);
    $cliente->place_birth = strtoupper($request->nacionalidad);
    $cliente->job = strtoupper($request->ocupacion);
    $cliente->street = strtoupper($request->calle);
    $cliente->exterior = $request->exterior;
    $cliente->inside = $request->interior;
    $cliente->pc = $request->cp;
    $cliente->colony = strtoupper($request->colonia);
    $cliente->town = strtoupper($request->municipio);
    $cliente->city = strtoupper($request->ciudad);
    $cliente->ef = strtoupper($request->entidad);
    $cliente->country = strtoupper($request->pais);
    $cliente->phone1 = $request->telefono1;
    $cliente->phone2 = $request->telefono2;
    $cliente->email = strtoupper($request->memail);
    $cliente->curp = strtoupper($request->curp);
    $cliente->rfc = strtoupper($request->rfc);
    $cliente->c_name = strtoupper($request->cnombre);
    $cliente->c_lastname = strtoupper($request->capellidop);
    $cliente->c_o_lastname = strtoupper($request->capellidom);
    $cliente->c_phone = $request->ctelefono;
    $cliente->c_email = strtoupper($request->cemail);
    $cliente->antiguedad = $request->antiguedad;
    DB::beginTransaction();
    try {


      $cliente->save();


      if (isset($request->listasNegras)) {
        $cliente->listasNegras()->createMany($request->listasNegras);
      }
      DB::commit();
    } catch (\Exception $ex) {
      DB::rollback();
      return response()->json(['error' => $ex->getMessage()], 500);
    }
    // if (isset($request->listasNegras)) {
    //   foreach ($request->listasNegras as $entrada) {
    //     $nuevaEntrada = new ListaNegra($entrada);
    //     $cliente->listasNegras[] = $nuevaEntrada;
    //   }
    // }
    // $cliente->save();


    $cid = $cliente->id;
    $user = $request->user();
    $userid = $user->id;

    $random = Str::random(50);
    $category = new User;
    $category->name = $request->nombre;
    $category->lastname = $request->apellidop;
    $category->o_lastname = $request->apellidom;
    $category->email = $request->memail;
    $category->role = 3;
    $category->status = 'Activo';
    $category->password = bcrypt('123456');
    $category->activate = $random;
    $category->save();
    //Mail::to(array($request->memail))->send(new EmailVerification($random));

    $pasaporte = $request->file('pasaportefront') ? $request->file('pasaportefront') : 1;

    $fileine = $request->file('inefront') ? $request->file('inefront') : 1;
    $ineback = $request->file('ineback') ? $request->file('ineback') : 1;
    $filecurp = $request->file('filecurp') ? $request->file('filecurp') : 1;
    $filedom = $request->file('filedom') ? $request->file('filedom') : 1;
    $filecom1 = $request->file('filecom1') ? $request->file('filecom1') : 1;
    $filecom2 = $request->file('filecom2') ? $request->file('filecom2') : 1;
    $filecom3 = $request->file('filecom3') ? $request->file('filecom3') : 1;
    $filerfc = $request->file('filerfc') ? $request->file('filerfc') : 1;

    if ($pasaporte != 1) {
      $path = 'fisicas/pasaporte';
      $extension = ("jpg");
      //$extension = strtolower($pasaporte->getClientOriginalExtension());
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '-pasaporte.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'PASAPORTE';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->save();

        $image = Image::make(File::get($pasaporte));
        $image->insert(public_path('images/confidencial.png'), 'center');
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });

        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode('jpg', 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'PASAPORTE';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '-pasaporte.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($pasaporte));
      }
    }


    if ($fileine != 1) {
      $path = 'fisicas/ine';
      $extension = ("jpg");
      //$extension = strtolower($fileine->getClientOriginalExtension());
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '-frontal.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'INE';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->save();

        $image = Image::make(File::get($fileine));
        $image->insert(public_path('images/confidencial.png'), 'center');
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });

        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode('jpg', 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'INE';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '-frontal.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($fileine));
      }
    }

    if ($ineback != 1) {
      $path = 'fisicas/ine';
      $extension = ("jpg");
      //$extension = strtolower($ineback->getClientOriginalExtension());
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '-trasera.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'INE';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->save();

        $image = Image::make(File::get($ineback));
        $image->insert(public_path('images/confidencial.png'), 'center');
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });

        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode('jpg', 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'INE';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '-trasera.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($ineback));
      }
    }

    if ($filecurp != 1) {
      $path = 'fisicas/curp';
      $extension = ("jpg");
      //$extension = strtolower($filecurp->getClientOriginalExtension());
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'CURP';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->save();

        $image = Image::make(File::get($filecurp));
        $image->insert(public_path('images/confidencial.png'), 'center');
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode($extension, 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'CURP';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($filecurp));
      }
    }

    if ($filedom != 1) {
      $path = 'fisicas/dom';
      $extension = ("jpg");
      //$extension = strtolower($filedom->getClientOriginalExtension());
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'COMPROBANTE DOMICILIO';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->save();

        $image = Image::make(File::get($filedom));
        $image->insert(public_path('images/confidencial.png'), 'center');
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode($extension, 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'COMPROBANTE DOMICILIO';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($filedom));
      }
    }

    if ($filecom1 != 1) {
      $path = 'fisicas/com1';
      $extension = ("jpg");
      //$extension = strtolower($filecom1->getClientOriginalExtension());
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'COMPROBANTE DE INGRESO';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->save();

        $image = Image::make(File::get($filecom1));
        $image->insert(public_path('images/confidencial.png'), 'center');
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode($extension, 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'COMPROBANTE DE INGRESO';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($filecom1));
      }
    }

    if ($filecom2 != 1) {
      $path = 'fisicas/com2';
      $extension = ("jpg");
      // $extension = strtolower($filecom2->getClientOriginalExtension());
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = '2 COMPROBANTE DE INGRESO';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->save();

        $image = Image::make(File::get($filecom2));
        $image->insert(public_path('images/confidencial.png'), 'center');
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode($extension, 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = '2 COMPROBANTE DE INGRESO';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($filecom2));
      }
    }

    if ($filecom3 != 1) {
      $path = 'fisicas/com3';
      $extension = ("jpg");
      //$extension = strtolower($filecom3->getClientOriginalExtension());
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = '3 COMPROBANTE DE INGRESO';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->save();

        $image = Image::make(File::get($filecom3));
        $image->insert(public_path('images/confidencial.png'), 'center');
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode($extension, 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = '3 COMPROBANTE DE INGRESO';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($filecom3));
      }
    }

    if ($filerfc != 1) {
      $path = 'fisicas/rfc';
      $extension = ("jpg");
      // $extension = strtolower($filerfc->getClientOriginalExtension());
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'RFC';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->save();

        $image = Image::make(File::get($filerfc));
        $image->insert(public_path('images/confidencial.png'), 'center');
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode($extension, 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'RFC';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($filerfc));
      }
    }


    return redirect('/clientes/fisica')->with('message', 'OK');
  }

  public function edited(Request $request)
  {
    $request->validate([
      'name' => 'required|string',
      'lastname' => 'required|string',
      'olastname' => 'required|string',
      'status' => 'required|string|',
      'rol' => 'required|string|'
    ]);

    $guser = DB::TABLE('users')->where('email', $request->email)->first();

    $id = $guser->id;
    $user = User::find($id);
    $user->name = $request->name;
    $user->lastname = $request->lastname;
    $user->o_lastname = $request->olastname;
    $user->email = $request->email;
    $user->role = $request->rol;
    $user->status = $request->status;
    $user->save();

    return redirect('/usuarios/lista')->with('message', 'ED');
  }


  public function activar(Request $request)
  {
    $id = $request->id;
    $user = Client::find($id);
    $user->status = 'Activo';
    $user->save();

    return response('OK', 200);
  }

  public function archivar(Request $request)
  {
    $id = $request->id;
    $user = Client::find($id);
    $user->status = 'Archivado';
    $user->save();

    return response('OK', 200);
  }


  public function data(Request $request)
  {
    $id = $request->id;
    $guser = DB::TABLE('clientes')->where('id', $id)->first();
    $data = array(
      'nombre' => strtoupper($guser->name . ' ' . $guser->lastname . ' ' . $guser->o_lastname),
      'genero' => $guser->gender,
      'fnac' => $guser->date_birth,
      'pnac' => $guser->place_birth,
      'ocupacion' => $guser->job,
      'direccion' => $guser->street . ' ' . $guser->exterior . ' ' . $guser->inside . ' ' . $guser->town . ' ' . $guser->city . ' ' . $guser->pc,
      'telefonos' => $guser->phone1 . ' ' . $guser->phone2,
      'email' => strtoupper($guser->email),
      'curp' => strtoupper($guser->curp),
      'rfc' => strtoupper($guser->rfc),
      'cnombre' => strtoupper($guser->c_name . ' ' . $guser->c_lastname . ' ' . $guser->c_o_lastname),
      'ctelefono' => $guser->c_phone,
      'cemail' => strtoupper($guser->c_email),
      'images' => array()
    );

    $images = DB::TABLE('files')->where('client_id', $id)->get();

    foreach ($images as $img) {
      array_push($data['images'], array('extension' => $img->extension, 'name' => $img->name, 'path' => $img->full));
    }

    return response($data, 200);
  }

  public function desactivar(Request $request)
  {
    $id = $request->id;
    $user = User::find($id);
    $user->status = 'Desactivado';
    $user->save();

    return response('OK', 200);
  }

  public function getfiles($id)
  {

    $images = DB::TABLE('files')->where('client_id', $id)->get();
    $data = '';
    foreach ($images as $img) {
      $data .= '<tr><td>' . $img->type . '</td><td>' . $img->extension . '</td><td>' . $img->created_at . '</td><td><a href="/uploads/' . $img->full . '" target="popup" onclick="window.open(\'/uploads/' . $img->full . '\',\'popup\',\'width=600,height=600\'); return false;"><button  style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-eye primary"></i></button></a></td><td><a href="/storage/' . $img->full . '" target="_blank"><button  style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-download primary"></i></button></a></td></tr>';
    }

    return $data;
  }

  public function amortizacion(Request $request)
  {
    $result = array();
    $data = $request->data;

    if (!empty($data)) {
      $plazo = $data['plazoSliderInput'];
      $tinteres = $data['tinteres'];
      $monto = $data['sliderInput'];
      $frecuencia = $data['frecuencia'];
      $amortizaciones = $data['amortizaciones'];
      $forma = $data['fpago'];
      $disposicion = $data['disposicion'];
      $nuevafecha = '';
      $dias = '';
      $mdis = number_format($monto * -1, 2);
      $saldo = $monto;
      $comision = doubleval(number_format($monto * 0.01, 2));
      $civa = $data['iva'];
      $intereses = 0;
      $amortizacion = 0;
      $iva = '';
      $flujo = 0;
      $addt = '';
      $add = 1;
      $sumintereses = 0;
      $sumiva = 0;
      $sumflujo = 0;
      if ($frecuencia == 'semanales') {
        $rplazo = round(abs($plazo * 4));
        $addt = 'week';
      }

      if ($frecuencia == 'quincenales') {
        $rplazo = round(abs($plazo * 2));
        $add = 15;
        $addt = 'days';
      }

      if ($frecuencia == 'menusales') {
        $rplazo = round(abs($plazo * 1));
        $addt = 'month';
      }

      if ($frecuencia == 'trimestrales') {
        $rplazo = round(abs($plazo / 3));
        $add = 3;
        $addt = 'month';
      }

      if ($frecuencia == 'semestrales') {
        $rplazo = round(abs($plazo / 3));
        $add = 6;
        $addt = 'month';
      }

      if ($frecuencia == 'anuales') {
        $rplazo = round(abs($plazo / 12));
        $addt = 'year';
      }

      $tp = ($tinteres / 100) / 12;
      $pp = ($tp * pow((1 + $tp), $rplazo)) * $monto / ((pow((1 + $tp), $rplazo)) - 1);
      $pp = ($tp * pow((1 + $tp), $rplazo)) * $monto / ((pow((1 + $tp), $rplazo)) - 1);
      //(A1*(1+A1)^B1)*C1/(((1+A1)^B1)-1)


      for ($i = 0; $i <= $rplazo; $i++) {
        if ($forma == 'VENCIMIENTO') {
          if ($i == 0) {
            $fecha = date('d/m/Y', strtotime($disposicion));
            if ($civa == 'SI') {
              $iva = ($intereses + $comision) * 0.16;
            }

            $flujo = ($monto * -1) + $comision + $amortizacion + $intereses + $iva;

          } else {
            $fecha1 = date_create($disposicion);

            $fecha = date('d/m/Y', strtotime($disposicion));

            $nuevafecha = strtotime('+' . $add . ' ' . $addt, strtotime($disposicion));
            $disposicion = date('Y-m-d', $nuevafecha);
            $nuevafecha = date('d/m/Y', $nuevafecha);

            $fecha2 = date_create($disposicion);

            $dias = str_replace('+', '', date_diff($fecha1, $fecha2)->format('%R%a'));

            $intereses = ($saldo * ($tinteres / 100) / 360) * $dias;

            if ($rplazo == $i) {
              $amortizacion = $saldo;

              $saldo = 0;

            } else {
              $saldo = $saldo;
              $amortizacion = 0;
            }

            $mdis = '';
            $comision = '';


            if ($civa == 'SI') {
              $iva = $intereses * 0.16;
            }


            $flujo = $amortizacion + $intereses + $iva;

          }

          $sumintereses = $sumintereses + round($intereses);
          $sumiva = $sumiva + round($iva);
          if ($i > 1) {
            $sumflujo = $sumflujo + round($flujo);
          }

          $arr = array(
            'periodo' => $i,
            'fecha' => $fecha . ' - ' . $nuevafecha,
            'dias' => $dias,
            'disposición' => $mdis,
            'saldo' => number_format(round($saldo), 0),
            'comisión' => $comision,
            'amortización' => number_format(round($amortizacion), 0),
            'intereses' => number_format(round($intereses), 0),
            'moratorios' => '',
            'iva' => number_format(round($iva), 0),
            'flujo' => number_format(round($flujo), 0)
          );
          array_push($result, (object)$arr);

        } else {
          if ($amortizaciones == 'Pagos iguales') {
            if ($i == 0) {
              $fecha = date('d/m/Y', strtotime($disposicion));
              if ($civa == 'SI') {
                $iva = ($intereses + $comision) * 0.16;
              }

              $flujo = ($monto * -1) + $comision + $amortizacion + $intereses + $iva;

            } else {
              $fecha1 = date_create($disposicion);

              $fecha = date('d/m/Y', strtotime($disposicion));

              $nuevafecha = strtotime('+' . $add . ' ' . $addt, strtotime($disposicion));
              $disposicion = date('Y-m-d', $nuevafecha);
              $nuevafecha = date('d/m/Y', $nuevafecha);

              $fecha2 = date_create($disposicion);
              $dias = str_replace('+', '', date_diff($fecha1, $fecha2)->format('%R%a'));
              $mdis = '';
              $comision = '';

              $intereses = (($saldo * ($tinteres / 100)) / 360) * 30;

              if ($civa == 'SI') {
                $iva = $intereses * 0.16;
              }

              $amortizacion = $pp - $intereses;

              $saldo = $saldo - $amortizacion;
              $flujo = $pp + $iva;

            }

            $arr = array(
              'periodo' => $i,
              'fecha' => $fecha . ' - ' . $nuevafecha,
              'dias' => $dias,
              'disposición' => $mdis,
              'saldo' => number_format(round($saldo), 0),
              'comisión' => $comision,
              'amortización' => number_format(round($amortizacion), 0),
              'intereses' => number_format(round($intereses), 0),
              'moratorios' => '',
              'iva' => number_format(round($iva), 0),
              'flujo' => number_format(round($flujo), 0)
            );
            array_push($result, (object)$arr);
          } elseif ($amortizaciones == 'Amortizaciones iguales') {
            if ($i == 0) {
              $fecha = date('d/m/Y', strtotime($disposicion));
              if ($civa == 'SI') {
                $iva = ($intereses + $comision) * 0.16;

              }

              $flujo = ($monto * -1) + $comision + $amortizacion + $intereses + $iva;

            } else {
              $fecha1 = date_create($disposicion);

              $fecha = date('d/m/Y', strtotime($disposicion));

              $nuevafecha = strtotime('+' . $add . ' ' . $addt, strtotime($disposicion));
              $disposicion = date('Y-m-d', $nuevafecha);
              $nuevafecha = date('d/m/Y', $nuevafecha);

              $fecha2 = date_create($disposicion);
              $dias = str_replace('+', '', date_diff($fecha1, $fecha2)->format('%R%a'));
              $mdis = '';
              $comision = '';

              $intereses = $saldo * ($tinteres / 100) / 360 * $dias;

              if ($civa == 'SI') {
                $iva = $intereses * 0.16;
              }

              $amortizacion = ($monto / $rplazo);


              $saldo = $saldo - $amortizacion;
              $flujo = $amortizacion + $intereses + $iva;

            }

            $arr = array(
              'periodo' => $i,
              'fecha' => $fecha . ' - ' . $nuevafecha,
              'dias' => $dias,
              'disposición' => $mdis,
              'saldo' => number_format(round($saldo), 0),
              'comisión' => $comision,
              'amortización' => number_format(round($amortizacion), 0),
              'intereses' => number_format(round($intereses), 0),
              'moratorios' => '',
              'iva' => number_format(round($iva), 0),
              'flujo' => number_format(round($flujo), 0)
            );
            array_push($result, (object)$arr);

          }
        }
      }

      if ($forma == 'VENCIMIENTO') {
        $arr = array(
          'periodo' => 'Totales',
          'fecha' => '',
          'dias' => '',
          'disposición' => '',
          'saldo' => '',
          'comisión' => '',
          'amortización' => number_format(round($monto), 0),
          'intereses' => number_format(round($sumintereses), 0),
          'moratorios' => '',
          'iva' => number_format(round($sumiva), 0),
          'flujo' => number_format(round($sumflujo), 0)
        );
        array_push($result, (object)$arr);
      }
    }

    return datatables()->of($result)
      /* ->addColumn('names', function ($query) {
         return strtoupper($query->name . ' ' . $query->lastname . ' ' . $query->o_lastname);
       })
       ->rawColumns(['actions', 'names'])*/
      ->toJson();

  }

  public function pago(Request $request)
  {

    $cid = creditos::where('client_id', $request->id)->where('status', 'Aprobado')->first()->id;

    if ($request->moneda == 0) {
      $moneda = $request->nmoneda;
    } else {
      $moneda = $request->moneda;
    }
    if ($request->forma == 0) {
      $forma = $request->nforma;
    } else {
      $forma = $request->forma;
    }

    $npago = new Pago();
    $npago->client_id = $request->id;
    $npago->credito_id = $cid;
    $npago->pago = $request->monto;
    $npago->fpago = $request->fecha;
    $npago->forma = $forma;
    $npago->moneda = $moneda;
    $npago->origen = $request->origen;
    $npago->save();
    $alertas = new \App\Alerta();
    $alertas->verificar($request, $cid);
    $alertas->validarRiesgo($request->id, $cid, "Pago");
    $user = Auth::user();

    $comprobante = $request->file('comprobante') ? $request->file('comprobante') : 1;

    $amortizacion = Amortizacion::where('cliente_id', $request->id)->where('credito_id', $cid)->where('liquidado', 0)->where('flujo', '>', 0)->orderBy('periodo', 'asc')->orderBy('id', 'asc')->get();

    $pago = $request->monto;
    $gcobranza = $request->gcobranza ? $request->gcobranza : 0;
    $tasa = (creditos::where('id', $cid)->first()->tasa) / 100;

    if ($gcobranza > 0) {
      $gc = Amortizacion::where('cliente_id', $request->id)->where('credito_id', $cid)->where('liquidado', 0)->where('flujo', '>', 0)->orderBy('periodo', 'asc')->orderBy('id', 'asc')->first();
      $flujoc = $gc->flujo + $gcobranza;
      $lcob = $gc->gcobranza ? $gc->gcobranza : 0;
      $ncobranza = $gcobranza + $lcob;
      Amortizacion::where('id', $gc->id)->update(['gcobranza' => $gcobranza, 'flujo' => $flujoc]);
      $amortizacion = Amortizacion::where('cliente_id', $request->id)->where('credito_id', $cid)->where('liquidado', 0)->where('flujo', '>', 0)->orderBy('periodo', 'asc')->orderBy('id', 'asc')->get();
    }
    $cc = 0;
    $pagoid = $npago->id;
    $rfecha = $request->fecha;
    $rmonto = $request->monto;
    $rperiodo = 0;
    foreach ($amortizacion as $data) {

      if ($pago > 0) {
        $fecha1 = date_create(date('d-m-Y', strtotime($data->fin)));
        $fecha2 = date_create(date('d-m-Y', strtotime($request->fecha)));

        $dias = str_replace('+', '', date_diff($fecha1, $fecha2)->format('%R%a'));

        $fecha11 = date_create(date('d-m-Y', strtotime($request->fecha)));
        $fecha22 = date_create(date('d-m-Y', strtotime($data->inicio)));

        $dias2 = str_replace('+', '', date_diff($fecha11, $fecha22)->format('%R%a'));

        if ($dias <= 0) {
          if ($dias2 > 0) {
            $amortizacion2 = Amortizacion::where('cliente_id', $request->id)->where('credito_id', $cid)->where('liquidado', 0)->orderBy('periodo', 'desc')->orderBy('id', 'asc')->get();
            foreach ($amortizacion2 as $data2) {
              if ($pago > 0) {
                if ($data2->pagos != 0) {
                  $flujo = $data2->flujo - $data2->pagos;

                  if ($flujo > $pago) {
                    $lam = Amortizacion::where('id', $data2->id)->first();
                    $lpagos = $lam->pagos ? $lam->pagos : 0;
                    $restante = ($lam->flujo - $lpagos) - $pago;

                    $rpagos = new RelacionPagos;
                    $rpagos->periodo_id = $data2->id;
                    $rpagos->pago_id = $pagoid;
                    $rpagos->fecha_pago = $rfecha;
                    $rpagos->monto = $pago;
                    $rpagos->monto_total = $rmonto;
                    $rpagos->restante = $restante;
                    $rpagos->pago_restante = 0;
                    $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                    $rpagos->save();
                    $rmonto = 0;
                    Amortizacion::where('id', $data2->id)->update(['pagos' => $pago]);
                    $pago = 0;
                  } else {
                    if ($flujo == $pago) {
                      $lam = Amortizacion::where('id', $data2->id)->first();
                      $lpagos = $lam->pagos ? $lam->pagos : 0;
                      $restante = ($lam->flujo - $lpagos) - $pago;

                      $rpagos = new RelacionPagos;
                      $rpagos->periodo_id = $data2->id;
                      $rpagos->pago_id = $pagoid;
                      $rpagos->fecha_pago = $rfecha;
                      $rpagos->monto = $pago;
                      $rpagos->monto_total = $rmonto;
                      $rpagos->restante = $restante;
                      $rpagos->pago_restante = 0;
                      $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                      $rpagos->save();
                      $rmonto = 0;

                      Amortizacion::where('id', $data2->id)->update(['pagos' => $pago, 'liquidado' => 1]);
                      $pago = 0;
                    } else {
                      $lam = Amortizacion::where('id', $data2->id)->first();

                      $pagos = $lam->pagos ? $lam->pagos : 0;

                      $apago = $lam->flujo - $pagos;

                      $prest = $pago - $apago;

                      $rpagos = new RelacionPagos;
                      $rpagos->periodo_id = $data2->id;
                      $rpagos->pago_id = $pagoid;
                      $rpagos->fecha_pago = $rfecha;
                      $rpagos->monto = $apago;
                      $rpagos->monto_total = $rmonto;
                      $rpagos->restante = 0;
                      $rpagos->pago_restante = $prest;
                      $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                      $rpagos->save();
                      $rmonto = $prest;

                      Amortizacion::where('id', $data2->id)->update(['pagos' => $lam->flujo, 'liquidado' => 1]);

                      $pago = $pago - $flujo;
                    }
                  }
                } else {
                  if ($data2->flujo > $pago) {
                    $lam = Amortizacion::where('id', $data2->id)->first();
                    $lpagos = $lam->pagos ? $lam->pagos : 0;
                    $restante = ($lam->flujo - $lpagos) - $pago;


                    $rpagos = new RelacionPagos;
                    $rpagos->periodo_id = $data2->id;
                    $rpagos->pago_id = $pagoid;
                    $rpagos->fecha_pago = $rfecha;
                    $rpagos->monto = $pago;
                    $rpagos->monto_total = $rmonto;
                    $rpagos->restante = $restante;
                    $rpagos->pago_restante = 0;
                    $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                    $rpagos->save();
                    $rmonto = 0;

                    Amortizacion::where('id', $data2->id)->update(['pagos' => $pago]);
                    $pago = 0;
                  } else {
                    if ($data2->flujo == $pago) {

                      $lam = Amortizacion::where('id', $data2->id)->first();
                      $lpagos = $lam->pagos ? $lam->pagos : 0;
                      $restante = ($lam->flujo - $lpagos) - $pago;


                      $rpagos = new RelacionPagos;
                      $rpagos->periodo_id = $data2->id;
                      $rpagos->pago_id = $pagoid;
                      $rpagos->fecha_pago = $rfecha;
                      $rpagos->monto = $pago;
                      $rpagos->monto_total = $rmonto;
                      $rpagos->restante = $restante;
                      $rpagos->pago_restante = 0;
                      $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                      $rpagos->save();
                      $rmonto = 0;

                      Amortizacion::where('id', $data2->id)->update(['pagos' => $pago, 'liquidado' => 1]);
                      $pago = 0;
                    } else {
                      $lam = Amortizacion::where('id', $data2->id)->first();

                      $pagos = $lam->pagos ? $lam->pagos : 0;

                      $apago = $lam->flujo - $pagos;

                      $prest = $pago - $apago;

                      $rpagos = new RelacionPagos;
                      $rpagos->periodo_id = $data2->id;
                      $rpagos->pago_id = $pagoid;
                      $rpagos->fecha_pago = $rfecha;
                      $rpagos->monto = $apago;
                      $rpagos->monto_total = $rmonto;
                      $rpagos->restante = 0;
                      $rpagos->pago_restante = $prest;
                      $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                      $rpagos->save();
                      $rmonto = $prest;

                      Amortizacion::where('id', $data2->id)->update(['pagos' => $lam->flujo, 'liquidado' => 1]);
                      $pago = $pago - $lam->flujo;
                    }
                  }
                }
              }
              $rperiodo = $data2->periodo;
            }

          } else {
            if ($data->pagos != 0) {
              $flujo = $data->flujo - $data->pagos;
              $pagos = $data->pagos;
              if ($flujo > $pago) {

                $lam = Amortizacion::where('id', $data->id)->first();
                $lpagos = $lam->pagos ? $lam->pagos : 0;
                $restante = ($lam->flujo - $lpagos) - $pago;


                $rpagos = new RelacionPagos;
                $rpagos->periodo_id = $data->id;
                $rpagos->pago_id = $pagoid;
                $rpagos->fecha_pago = $rfecha;
                $rpagos->monto = $pago;
                $rpagos->monto_total = $rmonto;
                $rpagos->restante = $restante;
                $rpagos->pago_restante = 0;
                $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                $rpagos->save();
                $rmonto = 0;

                Amortizacion::where('id', $data->id)->update(['pagos' => ($pagos + $pago)]);
                $pago = 0;
              } else {
                if ($flujo == $pago) {

                  $lam = Amortizacion::where('id', $data->id)->first();
                  $lpagos = $lam->pagos ? $lam->pagos : 0;
                  $restante = ($lam->flujo - $lpagos) - $pago;


                  $rpagos = new RelacionPagos;
                  $rpagos->periodo_id = $data->id;
                  $rpagos->pago_id = $pagoid;
                  $rpagos->fecha_pago = $rfecha;
                  $rpagos->monto = $pago;
                  $rpagos->monto_total = $rmonto;
                  $rpagos->restante = $restante;
                  $rpagos->pago_restante = 0;
                  $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                  $rpagos->save();
                  $rmonto = 0;

                  Amortizacion::where('id', $data->id)->update(['pagos' => ($pagos + $pago), 'liquidado' => 1]);
                  $pago = 0;
                } else {
                  $lam = Amortizacion::where('id', $data->id)->first();

                  $pagos = $lam->pagos ? $lam->pagos : 0;

                  $apago = $lam->flujo - $pagos;

                  $prest = $pago - $apago;

                  $rpagos = new RelacionPagos;
                  $rpagos->periodo_id = $data->id;
                  $rpagos->pago_id = $pagoid;
                  $rpagos->fecha_pago = $rfecha;
                  $rpagos->monto = $apago;
                  $rpagos->monto_total = $rmonto;
                  $rpagos->restante = 0;
                  $rpagos->pago_restante = $prest;
                  $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                  $rpagos->save();
                  $rmonto = $prest;

                  Amortizacion::where('id', $data->id)->update(['pagos' => $lam->flujo, 'liquidado' => 1]);
                  $pago = $pago - $flujo;
                }
              }
            } else {
              if ($data->flujo > $pago) {
                $lam = Amortizacion::where('id', $data->id)->first();
                $lpagos = $lam->pagos ? $lam->pagos : 0;
                $restante = ($lam->flujo - $lpagos) - $pago;


                $rpagos = new RelacionPagos;
                $rpagos->periodo_id = $data->id;
                $rpagos->pago_id = $pagoid;
                $rpagos->fecha_pago = $rfecha;
                $rpagos->monto = $pago;
                $rpagos->monto_total = $rmonto;
                $rpagos->restante = $restante;
                $rpagos->pago_restante = 0;
                $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                $rpagos->save();
                $rmonto = 0;

                Amortizacion::where('id', $data->id)->update(['pagos' => $pago]);
                $pago = 0;
              } else {
                if ($data->flujo == $pago) {
                  $lam = Amortizacion::where('id', $data->id)->first();
                  $lpagos = $lam->pagos ? $lam->pagos : 0;
                  $restante = ($lam->flujo - $lpagos) - $pago;

                  $rpagos = new RelacionPagos;
                  $rpagos->periodo_id = $data->id;
                  $rpagos->pago_id = $pagoid;
                  $rpagos->fecha_pago = $rfecha;
                  $rpagos->monto = $pago;
                  $rpagos->monto_total = $rmonto;
                  $rpagos->restante = $restante;
                  $rpagos->pago_restante = 0;
                  $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                  $rpagos->save();
                  $rmonto = 0;


                  Amortizacion::where('id', $data->id)->update(['pagos' => $pago, 'liquidado' => 1]);
                  $pago = 0;
                } else {
                  $lam = Amortizacion::where('id', $data->id)->first();

                  $pagos = $lam->pagos ? $lam->pagos : 0;

                  $apago = $lam->flujo - $pagos;

                  $prest = $pago - $apago;

                  $rpagos = new RelacionPagos;
                  $rpagos->periodo_id = $data->id;
                  $rpagos->pago_id = $pagoid;
                  $rpagos->fecha_pago = $rfecha;
                  $rpagos->monto = $apago;
                  $rpagos->monto_total = $rmonto;
                  $rpagos->restante = 0;
                  $rpagos->pago_restante = $prest;
                  $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                  $rpagos->save();
                  $rmonto = $prest;

                  Amortizacion::where('id', $data->id)->update(['pagos' => $lam->flujo, 'liquidado' => 1]);
                  $pago = $pago - $lam->flujo;
                }
              }
            }
          }


        } else {
          if ($data->pagos != 0) {
            $flujo = $data->flujo - $data->pagos;
            $pagos = $data->pagos;
            if ($flujo > $pago) {
              $lam = Amortizacion::where('id', $data->id)->first();
              $lpagos = $lam->pagos ? $lam->pagos : 0;
              $restante = ($lam->flujo - $lpagos) - $pago;


              $rpagos = new RelacionPagos;
              $rpagos->periodo_id = $data->id;
              $rpagos->pago_id = $pagoid;
              $rpagos->fecha_pago = $rfecha;
              $rpagos->monto = $pago;
              $rpagos->monto_total = $rmonto;
              $rpagos->restante = $restante;
              $rpagos->pago_restante = 0;
              $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
              $rpagos->save();
              $rmonto = 0;

              Amortizacion::where('id', $data->id)->update(['pagos' => ($pagos + $pago)]);
              $pago = 0;
            } else {
              if ($flujo == $pago) {
                $lam = Amortizacion::where('id', $data->id)->first();
                $lpagos = $lam->pagos ? $lam->pagos : 0;
                $restante = ($lam->flujo - $lpagos) - $pago;


                $rpagos = new RelacionPagos;
                $rpagos->periodo_id = $data->id;
                $rpagos->pago_id = $pagoid;
                $rpagos->fecha_pago = $rfecha;
                $rpagos->monto = $pago;
                $rpagos->monto_total = $rmonto;
                $rpagos->restante = $restante;
                $rpagos->pago_restante = 0;
                $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                $rpagos->save();
                $rmonto = 0;

                Amortizacion::where('id', $data->id)->update(['pagos' => ($pagos + $pago), 'liquidado' => 1]);
                $pago = 0;
              } else {


                $lam = Amortizacion::where('id', $data->id)->first();

                $pagos = $lam->pagos ? $lam->pagos : 0;

                $apago = $lam->flujo - $pagos;

                $prest = $pago - $apago;

                $rpagos = new RelacionPagos;
                $rpagos->periodo_id = $data->id;
                $rpagos->pago_id = $pagoid;
                $rpagos->fecha_pago = $rfecha;
                $rpagos->monto = $apago;
                $rpagos->restante = 0;
                $rpagos->monto_total = $rmonto;
                $rpagos->pago_restante = $prest;
                $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                $rpagos->save();
                $rmonto = $prest;

                Amortizacion::where('id', $data->id)->update(['pagos' => $lam->flujo, 'liquidado' => 1]);
                $pago = $pago - $flujo;
              }
            }
          } else {
            if ($data->flujo > $pago) {
              $lam = Amortizacion::where('id', $data->id)->first();
              $lpagos = $lam->pagos ? $lam->pagos : 0;
              $restante = ($lam->flujo - $lpagos) - $pago;


              $rpagos = new RelacionPagos;
              $rpagos->periodo_id = $data->id;
              $rpagos->pago_id = $pagoid;
              $rpagos->fecha_pago = $rfecha;
              $rpagos->monto = $pago;
              $rpagos->monto_total = $rmonto;
              $rpagos->restante = $restante;
              $rpagos->pago_restante = 0;
              $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
              $rpagos->save();
              $rmonto = 0;

              Amortizacion::where('id', $data->id)->update(['pagos' => $pago]);
              $pago = 0;
            } else {
              if ($data->flujo == $pago) {
                $lam = Amortizacion::where('id', $data->id)->first();
                $lpagos = $lam->pagos ? $lam->pagos : 0;
                $restante = ($lam->flujo - $lpagos) - $pago;


                $rpagos = new RelacionPagos;
                $rpagos->periodo_id = $data->id;
                $rpagos->pago_id = $pagoid;
                $rpagos->fecha_pago = $rfecha;
                $rpagos->monto = $pago;
                $rpagos->monto_total = $rmonto;
                $rpagos->restante = $restante;
                $rpagos->pago_restante = 0;
                $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                $rpagos->save();
                $rmonto = 0;

                Amortizacion::where('id', $data->id)->update(['pagos' => $pago, 'liquidado' => 1]);
                $pago = 0;
              } else {
                $lam = Amortizacion::where('id', $data->id)->first();

                $pagos = $lam->pagos ? $lam->pagos : 0;

                $apago = $lam->flujo - $pagos;

                $prest = $pago - $apago;

                $rpagos = new RelacionPagos;
                $rpagos->periodo_id = $data->id;
                $rpagos->pago_id = $pagoid;
                $rpagos->fecha_pago = $rfecha;
                $rpagos->monto = $apago;
                $rpagos->restante = 0;
                $rpagos->monto_total = $rmonto;
                $rpagos->pago_restante = $prest;
                $rpagos->descripcion = 'Monto pagado por el cliente';
                $rpagos->save();
                $rmonto = $prest;

                Amortizacion::where('id', $data->id)->update(['pagos' => $lam->flujo, 'liquidado' => 1]);
                $pago = $pago - $lam->flujo;
              }
            }
          }

        }
      }
      $rperiodo = $data->periodo;
    }

    if ($comprobante != 1) {
      $path = 'credito/pagos/fisica/comprobante';
      $extension = ("jpg");
      //$extension = strtolower($comprobante->getClientOriginalExtension());
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '-' . $npago->periodo . '-' . uniqid() . '.' . $extension;
        $uploads = new ComprobantePago();
        $uploads->pago_id = $npago->id;
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->save();

        $image = Image::make(File::get($comprobante));
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });

        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode('jpg', 30));
      } else {
        $filename = $cid . '-' . $npago->periodo . '-' . uniqid() . '.' . $extension;

        $uploads = new ComprobantePago();
        $uploads->pago_id = $npago->id;
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $filename, File::get($comprobante));
      }
    }

    return redirect('/clientes/fisicas/info/' . $request->id)->with('pago', 'OK');
  }


  public function infoamortizacion($id)
  {
    $result = array();

    $data = Creditos::where('client_id', $id)->first();
    $hoy = date('Y-m-d');

    if (!empty($data)) {

      $amortizacion = Amortizacion::where('cliente_id', $id)->where('credito_id', $data->id)->orderBy('id', 'asc')->get();

      if (!empty(count($amortizacion))) {
        foreach ($amortizacion as $gdata) {
          if ($gdata->liquidado == 0) {
            $fecha1 = date_create(date('Y-m-d'));
            $fecha2 = date_create($gdata->fin);
            $dias = str_replace('+', '', date_diff($fecha1, $fecha2)->format('%R%a'));
            $tasa = (creditos::where('id', $gdata->credito_id)->first()->tasa) / 100;

            if ($dias < 0 && $gdata->dia_mora != $hoy) {
              $dias = abs($dias);
              $intmora = ((($gdata->amortizacion * $tasa) * 2) / 360) * $dias;
              $ivamora = $intmora * 0.16;
              $moratorios = number_format($intmora, 2) + number_format($ivamora, 2);
              $lgcobranza = $gdata->gcobranza ? $gdata->gcobranza : 0;
              $gcobranza = 200;
              $ivacobranza = number_format($gcobranza * 0.16, 2);
              if (empty($lgcobranza)) {
                $nflujo = $gdata->amortizacion + $gdata->intereses + $gdata->iva + $moratorios + $gcobranza + $ivacobranza;

                $mflujo = $gdata->amortizacion + $gdata->intereses + $gdata->iva + $gcobranza + $ivacobranza;

                $nhistorialflujo = new HistorialFlujos;
                $nhistorialflujo->periodo_id = $gdata->id;
                $nhistorialflujo->monto = $mflujo;
                $nhistorialflujo->cambio = $gcobranza + $ivacobranza;
                $nhistorialflujo->descripcion = 'Gastos De Cobranza';
                $nhistorialflujo->save();

              } else {
                $nflujo = $gdata->flujo + $moratorios;
              }

              Amortizacion::where('id', $gdata->id)->update(['flujo' => $nflujo, 'dias_mora' => $dias, 'int_mora' => $intmora, 'iva_mora' => $ivamora, 'gcobranza' => $gcobranza, 'dia_mora' => $hoy, 'iva_cobranza' => $ivacobranza]);

              HistorialFlujos::where('periodo_id', $gdata->id)->where('descripcion', 'Gastos Moratorios')->delete();
              $nhistorialflujo = new HistorialFlujos;
              $nhistorialflujo->periodo_id = $gdata->id;
              $nhistorialflujo->monto = $nflujo;
              $nhistorialflujo->cambio = $moratorios;
              $nhistorialflujo->descripcion = 'Gastos Moratorios (Interes mora: $' . number_format($intmora, 2) . ' + Iva mora: $' . number_format($ivamora, 2) . ')';
              $nhistorialflujo->save();
            }
          }
        }

        $amortizacion = Amortizacion::selectRaw("
              id,
              cliente_id,
              credito_id,
              periodo,
              fechas,
              inicio,
              fin,
              dias,
              format(disposicion,2) as disposicion,
              format(saldo_insoluto,2) as saldo_insoluto,
              format(comision,2) as comision,
              format(amortizacion,2) as amortizacion,
              format(intereses,2) as intereses,
              format(moratorios,2) as moratorios,
              format(iva,2) as iva,
              format(gcobranza,2) as gcobranza,
              format(iva_cobranza,2) as ivacobranza,
              format(int_mora,2) as int_mora,
              format(iva_mora,2) as iva_mora,
              pagos,
              liquidado,
              flujo,
              dias_mora"
        )->where('cliente_id', $id)->where('credito_id', $data->id)->orderBy('id', 'asc')->get();
        $result = $amortizacion;
      } else {

        $plazo = $data->plazo;
        $tinteres = $data->tasa;
        $monto = $data->monto;
        $frecuencia = $data->frecuencia;
        $amortizaciones = $data->amortizacion;
        $forma = $data->fpago;
        $disposicion = $data->disposicion;
        $nuevafecha = '';
        $dias = '';
        $mdis = number_format($monto * -1, 2);
        $saldo = $monto;
        $comision = number_format($monto * 0.01, 2);
        $civa = $data->iva;
        $intereses = 0;
        $amortizacion = 0;
        $iva = '';
        $flujo = 0;
        $addt = '';
        $add = 1;
        $sumintereses = 0;
        $sumiva = 0;
        $sumflujo = 0;
        $cid = $data->id;

        if ($frecuencia == 'semanales') {
          $rplazo = round(abs($plazo * 4));
          $addt = 'week';
        }

        if ($frecuencia == 'quincenales') {
          $rplazo = round(abs($plazo * 2));
          $add = 15;
          $addt = 'days';
        }

        if ($frecuencia == 'menusales') {
          $rplazo = round(abs($plazo * 1));
          $addt = 'month';
        }

        if ($frecuencia == 'trimestrales') {
          $rplazo = round(abs($plazo / 3));
          $add = 3;
          $addt = 'month';
        }

        if ($frecuencia == 'semestrales') {
          $rplazo = round(abs($plazo / 3));
          $add = 6;
          $addt = 'month';
        }

        if ($frecuencia == 'anuales') {
          $rplazo = round(abs($plazo / 12));
          $addt = 'year';
        }

        $tp = ($tinteres / 100) / 12;
        $pp = ($tp * pow((1 + $tp), $rplazo)) * $monto / ((pow((1 + $tp), $rplazo)) - 1);
        $pp = ($tp * pow((1 + $tp), $rplazo)) * $monto / ((pow((1 + $tp), $rplazo)) - 1);

        for ($i = 0; $i <= $rplazo; $i++) {
          if ($forma == 'VENCIMIENTO') {
            if ($i == 0) {
              $fecha = date('d/m/Y', strtotime($disposicion));
              if ($civa == 'SI') {
                $iva = ($intereses + $comision) * 0.16;
              }

              $flujo = ($monto * -1) + $comision + $amortizacion + $intereses + $iva;

            } else {
              $fecha1 = date_create($disposicion);

              $fecha = date('d/m/Y', strtotime($disposicion));

              $nuevafecha = strtotime('+' . $add . ' ' . $addt, strtotime($disposicion));
              $disposicion = date('Y-m-d', $nuevafecha);
              $nuevafecha = date('d/m/Y', $nuevafecha);

              $fecha2 = date_create($disposicion);

              $dias = str_replace('+', '', date_diff($fecha1, $fecha2)->format('%R%a'));

              $intereses = ($saldo * ($tinteres / 100) / 360) * $dias;

              if ($rplazo == $i) {
                $amortizacion = $saldo;

                $saldo = 0;

              } else {
                $saldo = $saldo;
                $amortizacion = 0;
              }

              $mdis = '';
              $comision = '';


              if ($civa == 'SI') {
                $iva = $intereses * 0.16;
              }


              $flujo = $amortizacion + $intereses + $iva;

            }

            $sumintereses = $sumintereses + round($intereses);
            $sumiva = $sumiva + round($iva);
            if ($i > 1) {
              $sumflujo = $sumflujo + round($flujo);
            }


            $arr = array(
              'periodo' => $i,
              'fecha' => $fecha . ' - ' . $nuevafecha,
              'inicio' => $fecha,
              'fin' => $nuevafecha,
              'dias' => $dias,
              'disposicion' => $mdis,
              'saldo' => number_format(round($saldo), 0),
              'comision' => $comision,
              'amortizacion' => number_format(round($amortizacion), 0),
              'intereses' => number_format(round($intereses), 0),
              'moratorios' => '',
              'iva' => number_format(round($iva), 0),
              'flujo' => number_format(round($flujo), 0)
            );
            array_push($result, (object)$arr);

          } else {
            if ($amortizaciones == 'Pagos iguales') {
              $npp = 0;

              if ($i == 0) {
                $fecha = date('d/m/Y', strtotime($disposicion));
                if ($civa == 'SI') {
                  $iva = ($intereses + $comision) * 0.16;
                }

                $flujo = ($monto * -1) + $comision + $amortizacion + $intereses + $iva;

                $arr = array(
                  'periodo' => $i,
                  'fecha' => $fecha . ' - ' . $nuevafecha,
                  'inicio' => $fecha,
                  'fin' => $nuevafecha,
                  'dias' => $dias,
                  'disposicion' => $mdis,
                  'saldo' => number_format(round($saldo), 0),
                  'comision' => $comision,
                  'amortizacion' => number_format(round($amortizacion), 0),
                  'intereses' => number_format(round($intereses), 0),
                  'moratorios' => '',
                  'iva' => number_format(round($iva), 0),
                  'flujo' => number_format(round($flujo), 0)
                );
                array_push($result, (object)$arr);

              } else {
                $fecha1 = date_create($disposicion);

                $fecha = date('d/m/Y', strtotime($disposicion));
                $d1 = date('Y-m-d', strtotime($disposicion));

                $nuevafecha = strtotime('+' . $add . ' ' . $addt, strtotime($disposicion));
                $disposicion = date('Y-m-d', $nuevafecha);
                $d2 = date('Y-m-d', $nuevafecha);
                $nuevafecha = date('d/m/Y', $nuevafecha);

                $fecha2 = date_create($disposicion);
                $dias = str_replace('+', '', date_diff($fecha1, $fecha2)->format('%R%a'));
                $mdis = '';
                $comision = '';

                $intereses = (($saldo * ($tinteres / 100)) / 360) * 30;

                if ($civa == 'SI') {
                  $iva = $intereses * 0.16;
                }

                $amortizacion = $pp - $intereses;

                $saldo = $saldo - $amortizacion;
                $flujo = $pp + $iva;

                if (round($npp) < round($flujo)) {
                  $flujo = ($pp - $npp) + $iva;

                  $arr = array(
                    'periodo' => $i,
                    'fecha' => $fecha . ' - ' . $nuevafecha,
                    'inicio' => $fecha,
                    'fin' => $nuevafecha,
                    'dias' => $dias,
                    'disposicion' => $mdis,
                    'saldo' => number_format(round($saldo), 0),
                    'comision' => $comision,
                    'amortizacion' => number_format(round($amortizacion), 0),
                    'intereses' => number_format(round($intereses), 0),
                    'moratorios' => '',
                    'iva' => number_format(round($iva), 0),
                    'flujo' => number_format(round($flujo), 0)
                  );
                  array_push($result, (object)$arr);

                }

              }


            } elseif ($amortizaciones == 'Amortizaciones iguales') {
              if ($i == 0) {
                $fecha = date('d/m/Y', strtotime($disposicion));
                if ($civa == 'SI') {
                  $iva = ($intereses + $comision) * 0.16;
                }

                $flujo = ($monto * -1) + $comision + $amortizacion + $intereses + $iva;

              } else {
                $fecha1 = date_create($disposicion);

                $fecha = date('d/m/Y', strtotime($disposicion));

                $nuevafecha = strtotime('+' . $add . ' ' . $addt, strtotime($disposicion));
                $disposicion = date('Y-m-d', $nuevafecha);
                $nuevafecha = date('d/m/Y', $nuevafecha);

                $fecha2 = date_create($disposicion);
                $dias = str_replace('+', '', date_diff($fecha1, $fecha2)->format('%R%a'));
                $mdis = '';
                $comision = '';

                $intereses = $saldo * ($tinteres / 100) / 360 * $dias;

                if ($civa == 'SI') {
                  $iva = $intereses * 0.16;
                }

                $amortizacion = ($monto / $rplazo);


                $saldo = $saldo - $amortizacion;
                $flujo = $amortizacion + $intereses + $iva;

              }

              $arr = array(
                'periodo' => $i,
                'fecha' => $fecha . ' - ' . $nuevafecha,
                'inicio' => $fecha,
                'fin' => $nuevafecha,
                'dias' => $dias,
                'disposicion' => $mdis,
                'saldo' => number_format(round($saldo), 0),
                'comision' => $comision,
                'amortizacion' => number_format(round($amortizacion), 0),
                'intereses' => number_format(round($intereses), 0),
                'moratorios' => '',
                'iva' => number_format(round($iva), 0),
                'flujo' => number_format(round($flujo), 0)
              );
              array_push($result, (object)$arr);

            }
          }
        }

        if ($forma == 'VENCIMIENTO') {
          $arr = array(
            'periodo' => 'Totales',
            'fecha' => '',
            'inicio' => '',
            'fin' => '',
            'dias' => '',
            'disposicion' => '',
            'saldo' => '',
            'comision' => '',
            'amortizacion' => number_format(round($monto), 0),
            'intereses' => number_format(round($sumintereses), 0),
            'moratorios' => '',
            'iva' => number_format(round($sumiva), 0),
            'flujo' => number_format(round($sumflujo), 0)
          );
          array_push($result, (object)$arr);
        }

        foreach ($result as $key) {
          $amm = new Amortizacion;
          $amm->cliente_id = $id;
          $amm->credito_id = $data->id;
          $amm->periodo = $key->periodo;
          $amm->fechas = $key->fecha;
          $amm->inicio = $key->inicio ? date('Y-m-d', strtotime(substr($key->inicio, 6, 4) . '-' . substr($key->inicio, 3, 2) . '-' . substr($key->inicio, 0, 2))) : null;
          $amm->fin = $key->fin ? date('Y-m-d', strtotime(substr($key->fin, 6, 4) . '-' . substr($key->fin, 3, 2) . '-' . substr($key->fin, 0, 2))) : null;
          $amm->dias = $key->dias ? $key->dias : null;
          $amm->disposicion = $key->disposicion ? str_replace(',', '', $key->disposicion) : null;
          $amm->saldo_insoluto = $key->saldo ? str_replace(',', '', $key->saldo) : null;
          $amm->comision = $key->comision ? str_replace(',', '', $key->comision) : null;
          $amm->amortizacion = $key->amortizacion ? str_replace(',', '', $key->amortizacion) : null;
          $amm->intereses = $key->intereses ? str_replace(',', '', $key->intereses) : null;;
          $amm->moratorios = $key->moratorios ? str_replace(',', '', $key->moratorios) : null;
          $amm->iva = $key->iva;
          $amm->flujo = $key->flujo ? str_replace(',', '', $key->flujo) : null;
          $amm->dias_mora = 0;
          $amm->save();

          $nhistorialflujo = new HistorialFlujos;
          $nhistorialflujo->periodo_id = $amm->id;
          $nhistorialflujo->monto = $key->flujo ? str_replace(',', '', $key->flujo) : null;
          $nhistorialflujo->cambio = $key->flujo ? str_replace(',', '', $key->flujo) : null;
          $nhistorialflujo->descripcion = 'Flujo Original De Amortización';
          $nhistorialflujo->save();

        }


        $amortizacion = Amortizacion::where('cliente_id', $id)->where('credito_id', $data->id)->orderBy('id', 'asc')->get();
        foreach ($amortizacion as $gdata) {
          if ($gdata->liquidado == 0) {
            $fecha1 = date_create(date('Y-m-d'));
            $fecha2 = date_create($gdata->fin);
            $dias = str_replace('+', '', date_diff($fecha1, $fecha2)->format('%R%a'));
            $tasa = (creditos::where('id', $gdata->credito_id)->first()->tasa) / 100;

            if ($dias < 0 && $gdata->dia_mora != $hoy) {
              $dias = abs($dias);
              $intmora = ((($gdata->amortizacion * $tasa) * 2) / 360) * $dias;
              $ivamora = $intmora * 0.16;
              $moratorios = number_format($intmora, 2) + number_format($ivamora, 2);
              $lgcobranza = $gdata->gcobranza ? $gdata->gcobranza : 0;
              $gcobranza = 200;
              $ivacobranza = number_format($gcobranza * 0.16, 2);
              if (empty($lgcobranza)) {
                $nflujo = $gdata->amortizacion + $gdata->intereses + $gdata->iva + $moratorios + $gcobranza + $ivacobranza;

                $mflujo = $gdata->amortizacion + $gdata->intereses + $gdata->iva + $gcobranza + $ivacobranza;

                $nhistorialflujo = new HistorialFlujos;
                $nhistorialflujo->periodo_id = $gdata->id;
                $nhistorialflujo->monto = $mflujo;
                $nhistorialflujo->cambio = $gcobranza + $ivacobranza;
                $nhistorialflujo->descripcion = 'Gastos De Cobranza';
                $nhistorialflujo->save();


              } else {
                $nflujo = $gdata->flujo + $moratorios;
              }
              Amortizacion::where('id', $gdata->id)->update(['flujo' => $nflujo, 'dias_mora' => $dias, 'int_mora' => $intmora, 'iva_mora' => $ivamora, 'gcobranza' => $gcobranza, 'dia_mora' => $hoy, 'iva_cobranza' => $ivacobranza]);

              HistorialFlujos::where('periodo_id', $gdata->id)->where('descripcion', 'Gastos Moratorios')->delete();
              $nhistorialflujo = new HistorialFlujos;
              $nhistorialflujo->periodo_id = $gdata->id;
              $nhistorialflujo->monto = $nflujo;
              $nhistorialflujo->cambio = $moratorios;
              $nhistorialflujo->descripcion = 'Gastos Moratorios (Interes mora: $' . number_format($intmora, 2) . ' + Iva mora: $' . number_format($ivamora, 2) . ')';
              $nhistorialflujo->save();

            }
          }
        }
        $amortizacion = Amortizacion::selectRaw("
              id,
              cliente_id,
              credito_id,
              periodo,
              fechas,
              inicio,
              fin,
              dias,
              format(disposicion,2) as disposicion,
              format(saldo_insoluto,2) as saldo_insoluto,
              format(comision,2) as comision,
              format(amortizacion,2) as amortizacion,
              format(intereses,2) as intereses,
              format(moratorios,2) as moratorios,
              format(iva,2) as iva,
              format(gcobranza,2) as gcobranza,
              format(iva_cobranza,2) as ivacobranza,
              format(int_mora,2) as int_mora,
              format(iva_mora,2) as iva_mora,
              pagos,
              liquidado,
              flujo,
              dias_mora"
        )->where('cliente_id', $id)->where('credito_id', $data->id)->orderBy('id', 'asc')->get();
        $result = $amortizacion;


      }

    }

    return datatables()->of($result)
      ->addColumn('saldo_pendiente', function ($query) {

        if ($query->flujo > 0) {
          $pagos = $query->pagos ? $query->pagos : 0;

          $pendiente = number_format($query->flujo - $pagos, 2);
        } else {
          $pendiente = '';
        }

        return $pendiente;
      })
      ->addColumn('pagos', function ($query) {

        $pagos = $query->pagos ? $query->pagos : 0;
        $pagos = number_format($pagos, 2);
        if ($pagos > 0) {
          $pagos = '<button onclick="verpagos(' . $query->id . ');" type="button" class="btn btn-flat-dark" style="position: relative;">
                    $' . $pagos . '
                    </button>';
        } else {
          $pagos = '<button type="button" class="btn btn-flat-dark" style="position: relative;">
                    $0.00
                    </button>';
        }
        return $pagos;
      })
      ->addColumn('flujos', function ($query) {

        $flujo = number_format($query->flujo, 2);

        $bflujo = '<button onclick="verflujos(' . $query->id . ');" type="button" class="btn btn-flat-dark" style="position: relative;">
      $' . $flujo . '
      </button>';

        return $bflujo;
      })
      ->addColumn('cflujos', function ($query) {

        $flujo = number_format($query->flujo, 2);


        return $flujo;
      })
      ->addColumn('cstatus', function ($query) {
        if ($query->liquidado == 1 || $query->flujo < 0) {
          $status = 1;
        } elseif (strtotime(date('Y-m-d')) > strtotime($query->fin)) {
          $status = 2;

          $carbon = new Carbon();
          $to = $carbon::createFromFormat('Y-m-d', $query->fin);
          $from = $carbon->now();
          $diff = $to->diffInMonths($from);

          if ($diff > 0) {
            $status = 3;
          }

        } else {
          $status = 0;
        }
        return $status;
      })
      ->addColumn('condonar', function ($query) {
        $bflujo = '';
        if ($query->flujo > 0) {
          if ($query->liquidado == 0) {
            $bflujo = '<button onclick="condonar(' . $query->id . ');" type="button" class="btn btn-primary" style="position: relative;">
          Condonar
          </button>';
          }
        }


        return $bflujo;
      })
      ->rawColumns(['pagos', 'cstatus', 'saldo_pendiente', 'flujos', 'cflujos', 'condonar'])
      ->toJson();

  }

  public function infopagos($id)
  {
    $data = Creditos::where('client_id', $id)->first();
    $creditoid = 0;
    if (isset($data)) {
      $creditoid = $data->id;
    }

    $result = DB::SELECT("SELECT periodo,fpago,0 as mora,0 as imora,0 as condonacion, 0 as iva,pago, `full`
                          FROM `pagos`
                          LEFT JOIN comprobantes_pago on pago_id=pagos.id
                          WHERE credito_id=$creditoid");

    return datatables()->of($result)
      ->addColumn('comprobante', function ($query) {
        return '<a href="/uploads/' . $query->full . '" target="popup" onclick="window.open(\'/uploads/' . $query->full . '\',\'popup\',\'width=600,height=600\'); return false;"><button  style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-eye primary"></i></button></a>  <a href="/storage/' . $query->full . '" target="_blank"><button  style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-download primary"></i></button></a>';
      })
      ->rawColumns(['comprobante'])
      ->toJson();
  }


  public function infopagosaplicados(Request $request)
  {
    $id = $request->id;

    $result = RelacionPagos::where('periodo_id', $id)->get();

    return datatables()->of($result)
      ->addColumn('fecha', function ($query) {
        $fecha = date('d-m-Y', strtotime($query->fecha_pago));
        return $fecha;
      })
      ->addColumn('vmonto', function ($query) {
        $monto = '$' . number_format($query->monto, 2);
        return $monto;
      })
      ->addColumn('vmonto_total', function ($query) {
        $monto = '$' . number_format($query->monto_total, 2);
        return $monto;
      })
      ->addColumn('monto_restante', function ($query) {
        $monto = '$' . number_format($query->restante, 2);
        return $monto;
      })
      ->addColumn('vpago_restante', function ($query) {
        $monto = '$' . number_format($query->pago_restante, 2);
        return $monto;
      })
      ->rawColumns(['fecha', 'vmonto', 'vmonto_total', 'monto_restante', 'vpago_restante'])
      ->toJson();
  }

  public function infohistorialflujo(Request $request)
  {
    $id = $request->id;

    $result = HistorialFlujos::where('periodo_id', $id)->get();

    return datatables()->of($result)
      ->addColumn('fecha', function ($query) {
        $fecha = date('d-m-Y', strtotime($query->created_at));
        return $fecha;
      })
      ->addColumn('vmonto', function ($query) {
        $monto = '$' . number_format($query->monto, 2);
        return $monto;
      })
      ->addColumn('vcambio', function ($query) {
        $monto = '$' . number_format($query->cambio, 2);
        return $monto;
      })
      ->rawColumns(['fecha', 'vmonto', 'vcambio'])
      ->toJson();
  }

  public function condonarFlujo(Request $request)
  {
    $id = $request->id;

    $result = Amortizacion::where('id', $id)->get();

    return datatables()->of($result)
      ->addColumn('dintereses', function ($query) {
        $bflujo = '$' . ($query->intereses + $query->iva) . '<button onclick="cintereses(' . $query->id . ');" type="button" class="btn btn-primary" style="position: relative;">
      Condonar
      </button>';
        return $bflujo;
      })
      ->addColumn('dmoratorios', function ($query) {
        $bflujo = '$' . ($query->int_mora + $query->iva_mora) . '<button onclick="cintereses(' . $query->id . ');" type="button" class="btn btn-primary" style="position: relative;">
      Condonar
      </button>';
        return $bflujo;
      })
      ->addColumn('dcobranza', function ($query) {
        $bflujo = '$' . ($query->gcobranza + $query->iva_cobranza) . '<button onclick="cintereses(' . $query->id . ');" type="button" class="btn btn-primary" style="position: relative;">
      Condonar
      </button>';
        return $bflujo;
      })
      ->addColumn('dtodo', function ($query) {
        $bflujo = '$' . ($query->intereses + $query->iva + $query->int_mora + $query->iva_mora + $query->gcobranza + $query->iva_cobranza) . '<button onclick="cintereses(' . $query->id . ');" type="button" class="btn btn-primary" style="position: relative;">
      Condonar
      </button>';
        return $bflujo;
      })
      ->rawColumns(['dintereses', 'dmoratorios', 'dcobranza', 'dtodo'])
      ->toJson();
  }


  public function infocredito($id)
  {
    $result = Creditos::where('client_id', $id)->get();
    return datatables()->of($result)
      ->toJson();
  }

  public function infotasas($id)
  {
    $result = Creditos::where('client_id', $id)->get();
    return datatables()->of($result)
      ->addColumn('moratorio', function ($query) {
        $monto = '(((Flujo Inicial * Tasa) / 2)/360) * dias de mora ';
        return $monto;
      })
      ->rawColumns(['moratorio'])
      ->toJson();
  }

  public function credito(Request $request, $id)
  {
    $ncredito = new Creditos;
    $ncredito->client_id = $id;
    $ncredito->tcredito = $request->tcredito;
    $ncredito->contrato = $request->ncontrato;
    $ncredito->monto = $request->sliderInput;
    $ncredito->fpago = $request->fpago;
    $ncredito->frecuencia = $request->frecuencia;
    $ncredito->plazo = $request->numero_plazo;
    $ncredito->amortizacion = $request->amortizaciones;
    $ncredito->iva = $request->iva;
    $ncredito->tasa = $request->tinteres;
    $ncredito->disposicion = $request->disposicion;
    $ncredito->save();


    Client::where('id', $id)->update(['status' => 'credito']);
    $detinoC = new DestinoCredito();
    $destino = $detinoC::all();
    $detinoC->id_credito = $ncredito->id;
    $detinoC->id_destino_recursos = $request->recurso;
    $detinoC->titular = $request->titular;
    $detinoC->numero_cuenta_clabe = $request->numero_cuenta_clabe;
    $detinoC->tipo_cuenta = $request->tipo_cuenta;
    $detinoC->save();
    $alerta = new \App\Alerta();
    $alerta->validarDestino($request, $id, $ncredito->id);
    $alerta->validarRiesgo($id, $ncredito->id, "Nuevo credito");
    return redirect('/clientes/fisica')->with('credito', 'OK');

  }

  public function continuar($id)
  {
    $detinoC = new DestinoCredito();
    $client = Client::where('id', $id)->first();
    //$destino=$detinoC::all();
    //$destino=$detinoC::all();
    $destino = DestinoRecursos::get();
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageHeader' => true,
      'pageName' => 'Continuar Registro'
    ];


    return view('/clients/continuar', [
      'pageConfigs' => $pageConfigs,
      'client' => $client,
      'id' => $id, 'destino' => $destino
    ]);
  }

  public function listaNegraPDF($id)
  {
    $cliente = Client::where('id', $id)->with('listasNegras')->first();
    $documentos = Files::where('client_id', '=', $id)->get();

    return PDF::loadView('/clients/listaNegraPDF', compact('cliente', 'documentos'))->stream();
    //return view('/clients/listaNegraPDF', compact('cliente', 'documentos'));
  }
}
