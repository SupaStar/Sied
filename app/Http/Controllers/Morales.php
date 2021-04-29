<?php

namespace App\Http\Controllers;

use App\ActividadGiro;
use App\AlertasMorales;
use App\Amortizacion;
use App\Amortizacion_Morales;
use App\Credito_Moral;
use App\Creditos;
use App\DestinoCredito;
use App\DestinoCreditoMorales;
use App\DestinoRecursos;
use App\Divisa;
use App\EFResidencia;
use App\EntidadFederativa;
use App\HistorialFlujos;
use App\HistorialFlujos_Morales;
use App\InstrumentoMonetario;
use App\NacionalidadAntecedentes;
use App\OrigenRecursos;
use App\Pago_Moral;
use App\PepExtranjeras;
use App\PepMexicanas;
use App\PerfilMoral;
use App\PersonalidadJuridica;
use App\Pld;
use App\Ponderacion;
use App\Profesion;
use App\RelacionPago_Morales;
use App\RelacionPagos;
use App\Riesgo;
use App\Riesgos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Files;
use Image;
use App\Client;
use App\Moral;
use App\ListaNegra;
use App\Perfil;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use PDF;

class Morales extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('checkStatus');
  }

  public function morales()
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageName' => 'Personas Morales'
    ];

    return view('/morales/morales', [
      'pageConfigs' => $pageConfigs
    ]);
  }

  public function info($id)
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageName' => 'Personas Fisicas'
    ];

    $nacionalidades = db::table('nacionalidades')->get();
    $paises = db::table('paises')->get();
    $entidad = db::table('entidad_federativa')->get();
    $datos = Moral::where('id', '=', $id)->with('personasmorales')->first();
    $creditos = Credito_Moral::where('moral_id', $id)->get();
    $nacionalidadesantecedente = db::table('nacionalidad_antecedentes')->get();
    return view('/morales/info', [
      'pageConfigs' => $pageConfigs,
      'nacionalidades' => $nacionalidades,
      'paises' => $paises,
      'entidad' => $entidad,
      'datos' => $datos,
      'id' => $id,
      'creditos' => $creditos,
      'nacionantecedentes' => $nacionalidadesantecedente,


    ]);
  }

  public function fperfil($id)
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageName' => 'Personas Fisicas'
    ];
    $datos = PerfilMoral::where('id_moral', '=', $id)->first();

    if (isset($datos)) {
      return view('/clients/perfil', compact(
        'pageConfigs',
        'id',
        'datos'
      ));
    } else {
      return view('/clients/perfil', [
        'pageConfigs' => $pageConfigs,
        'id' => $id,

      ]);
    }
  }

  public function friesgo($id)
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageName' => 'Personas Fisicas'
    ];
    $moral = Moral::find($id);
    $riesgo = Riesgo::orderby('id', 'asc')->get();
    $pld = Pld::orderby('id', 'asc')->get();
    $pepmx = PepMexicanas::orderby('id', 'asc')->get();
    $pepex = PepExtranjeras::orderby('id', 'asc')->get();
    $personalidad = PersonalidadJuridica::orderby('id', 'asc')->get();
    $anacionalidad = NacionalidadAntecedentes::orderby('id', 'asc')->get();
    $entidad = EFResidencia::orderby('id', 'asc')->get();
    $antiguedad = $moral->antiguedades();
    $origen = OrigenRecursos::orderby('id', 'asc')->get();
    $destino = DestinoRecursos::orderby('id', 'asc')->get();
    $imonetario = InstrumentoMonetario::orderby('id', 'asc')->get();
    $divisa = Divisa::orderby('id', 'asc')->get();
    $ponderaciones = Ponderacion::orderby('id', 'asc')->get();
    $actividad = ActividadGiro::orderby('id', 'asc')->get();
    $profesion = Profesion::orderby('id', 'asc')->get();
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
    $actEconomica = $moral->actividad_profesion();
    $origenR = $moral->origen_recursos();
    $destinoR = $moral->destino_recursos();
    $antecedentes = $moral->llenarAntecedentes();
    $valorAntecedentes = 0;
    $sumaAntecedentes = 0;
    foreach ($antecedentes as $item) {
      $valorAntecedentes += $item['puntaje'] * $item['ponderacion'] / 100;
      $sumaAntecedentes = $item['puntaje'];
    }
    $valorActEconomica = 0;
    $sumaActEconomica = 0;
    foreach ($actEconomica as $item) {
      $valorActEconomica += $item['puntaje'] * $item['ponderacion'] / 100;
      $sumaActEconomica = $item['puntaje'];
    }
    $valorOrigenRecursos = 0;
    $sumaOrigenRecursos = 0;
    foreach ($origenR as $item) {
      $valorOrigenRecursos += $item['puntaje'] * $item['ponderacion'] / 100;
      $sumaOrigenRecursos = $item['puntaje'];
    }
    $valorDestino = $destinoR[0]['puntaje'] * 1;
    $sumatoria = $valorAntecedentes + $valorActEconomica + $valorOrigenRecursos + $valorDestino;
    $valorRes = ($valorAntecedentes * .5) + ($valorActEconomica * .17) + ($valorOrigenRecursos * .25) + ($valorDestino * .08);
    if ($valorRes < $riesgos['BAJO']) {
      $criesgo = 'BAJO';
    } elseif ($valorRes < $riesgos['MEDIO']) {
      $criesgo = 'MEDIO';
    } else {
      $criesgo = 'ALTO';
    }
    return view('/morales/riesgo', compact(
      'pageConfigs', 'actEconomica', 'origenR', 'destinoR', 'valorRes', 'valorAntecedentes', 'valorActEconomica',
      'valorOrigenRecursos', 'valorDestino', 'criesgo', 'riesgo', 'sumatoria', 'ponderaciones',
      'sumaAntecedentes', 'antecedentes', 'pld', 'pepmx', 'pepex', 'anacionalidad', 'antiguedad', 'personalidad',
      'entidad', 'sumaActEconomica', 'profesion', 'actividad', 'sumaOrigenRecursos', 'origen', 'imonetario', 'divisa', 'destino'
    ));
  }

  public function getmorales(Request $request)
  {

    $result = Moral::with('personasMorales');


    return datatables()->of($result)
      ->addColumn('socios', function ($query) {
        return count($query->personasMorales);
      })
      ->addColumn('blacklist', function ($query) {
        $result = '<button title="Listas Negras" onclick="noblacklist()" style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-check-circle success"></i></button>';

        return $result;
      })
      ->addColumn('status', function ($query) {

        $perfil = DB::TABLE('perfil_transacional_moral')->where('id_moral', $query->id)->first();
        $credito = DB::TABLE('credito_morales')->where('moral_id', $query->id)->where('status', '<>', 'liquidado')->first();
        $text = " - ";

        if (isset($perfil)) {
          if (empty($perfil->monto) || empty($perfil->tcredito) || empty($perfil->frecuencia) || empty($perfil->instrumento_monetario) || empty($perfil->origen_recursos) || empty($perfil->destino_recursos) || empty($perfil->divisas)) {
            $text = 'Pendiente <br> <a href="/morales/perfil/' . $query->id . '" class="warning">Perfil Transacional</a>';
          } else if (empty($perfil->profesion) || empty($perfil->actividad_giro) || empty($perfil->efr)) {
            $text = 'Pendiente <br> <a href="/morales/morales/ebr/' . $query->id . '" class="warning">Criterios de Riesgo</a>';
          } else if (isset($credito)) {
            $text = 'Aprobado <br> <a href="/morales/info/' . $query->id . '" class="warning">Información</a>';
          } else {
            $text = 'Pendiente <br> <a href="/morales/continuar/' . $query->id . '" class="warning">Credito</a>';
          }
        } else {
          $text = 'Pendiente <br> <a href="/morales/perfil/' . $query->id . '" class="warning">Perfil Transacional</a>';
        }

        return $text;

      })
      ->addColumn('actions', function ($query) {
        $user = Auth::user();
        return '
              <a href="/morales/info/' . $query->id . '" title="Información"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-eye"></i></button></a>
              <a href="/morales/continuar/' . $query->id . '" title="Crédito"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-dollar-sign warning"></i></button></a>
              <a href="/morales/perfil/' . $query->id . '" title="Perfil Transacional"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-file success"></i></button></a>
              <a href="morales/ebr/' . $query->id . '" title="Criterios de Riesgos"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-info info"></i></button></a>
              <a href="/morales/riesgo/' . $query->id . '" title="Grado de Riesgo"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-bar-chart-2 warning"></i></button></a>
              <button title="Descarga Archivos" onclick="files(' . $query->id . ');" style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-archive primary"></i></button>
              <a href="/morales/editarmoral/' . $query->id . '" title="Editar"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-edit primary"></i></button></a>
              <button title="Archivar" onclick="del(' . $query->id . ');" style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-trash danger"></i></button>';
      })
      ->rawColumns(['actions', 'socios', 'status', 'blacklist'])
      ->toJson();

    // if ($request->filtro == 'Archivados') {
    //   $result = DB::table('clientes')->where('status', 'Archivado');
    // } elseif ($request->filtro == 'H') {
    //   $result = DB::table('clientes')->where('gender', 'H');
    // } elseif ($request->filtro == 'M') {
    //   $result = DB::table('clientes')->where('gender', 'M');
    // } else {
    //   $result = DB::table('clientes')->where('status', '<>', 'Archivado');;
    // }

    // return datatables()->of($result)
    //   ->addColumn('names', function ($query) {
    //     return strtoupper($query->name . ' ' . $query->lastname . ' ' . $query->o_lastname);
    //   })

    //   ->addColumn('actions', function ($query) {
    //     $user = Auth::user();
    //     return '
    //           <a href="/clientes/fisicas/info/' . $query->id . '" title="Información"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-eye"></i></button></a>
    //           <a href="/clientes/fisicas/perfil/' . $query->id . '" title="Perfil Transacional"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-file success"></i></button></a>
    //           <a href="/clientes/fisicas/riesgo/' . $query->id . '" title="Grado de Riesgo"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-bar-chart-2 warning"></i></button></a>
    //           <button title="Descarga Archivos" onclick="files(' . $query->id . ');" style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-archive primary"></i></button>
    //           <a href="/clientes/fisicas/editar/' . $query->id . '" title="Editar"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-edit primary"></i></button></a>
    //           <button title="Archivar" onclick="del(' . $query->id . ');" style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-trash danger"></i></button>';
    //   })
    //   ->rawColumns(['actions', 'names'])
    //   ->toJson();
  }


  public function eperfil(Request $request)
  {
    $cid = $request->id;

    $user = Auth::user();
    $args = array(
      'divisas' => $request->divisa,
      'monto' => $request->monto,
      'tcredito' => $request->tcredito,
      'frecuencia' => $request->frecuencia,
      'origen_recursos' => $request->orecursos,
      'destino_recursos' => $request->drecursos,
      'instrumento_monetario' => $request->imonetario,
      'actividad' => false,
      'propietario' => false,
      'proovedor' => false,
      'dactividad' => false,
      'dpasivos' => false,
      'dotro' => false,
      'total' => false,
      'aceptable' => false,
      'dificil' => false,
      'ingreso' => $request->ingreso ? $request->ingreso : null,
      'conducta' => $request->conducta,
      'comentario' => $request->comentario,
    );

    switch ($request->actividad) {
      case 'actividad':
        $args['actividad'] = true;
        break;
      case 'propietario':
        $args['propietario'] = true;
        break;
      case 'proovedor':
        $args['proovedor'] = true;
        break;
    }

    switch ($request->dactividad) {
      case 'dactividad':
        $args['dactividad'] = true;
        break;
      case 'dpasivos':
        $args['dpasivos'] = true;
        break;
      case 'dotro':
        $args['dotro'] = $request->dotro;
        break;
    }

    switch ($request->disponibilidad) {
      case 'total':
        $args['total'] = true;
        break;
      case 'aceptable':
        $args['aceptable'] = true;
        break;
      case 'dificil':
        $args['dificil'] = true;
        break;
    }


    $fields = array(
      'id_moral' => $cid
    );

    $update = PerfilMoral::updateOrCreate($fields, $args);
    $riesgos = new Riesgos();
    $a = $riesgos->gradoMorales($cid);
    return redirect('/morales/morales')->with('message', 'OK');
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
      $extension = "jpg";
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
      $extension = "jpg";
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
      $extension = "jpg";
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
      $extension = "jpg";
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
      $extension = "jpg";
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
      $extension = "jpg";
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
      $extension = "jpg";
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
      $extension = "jpg";
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
    $datos = db::table('clientes')->where('id', $id)->first();

    return view('/morales/fisicas-editar', [
      'pageConfigs' => $pageConfigs,
      'nacionalidades' => $nacionalidades,
      'paises' => $paises,
      'entidad' => $entidad,
      'datos' => $datos
    ]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function NuevaEmpresa()
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageHeader' => true,
      'pageName' => 'Nueva Empresa'
    ];

    $nacionalidades = db::table('nacionalidades')->get();
    $paises = db::table('paises')->get();
    $entidad = db::table('entidad_federativa')->get();
    $nacionalidadesantecedente = db::table('nacionalidad_antecedentes')->get();

    return view('/morales/nueva-empresa', [
      'pageConfigs' => $pageConfigs,
      'nacionalidades' => $nacionalidades,
      'paises' => $paises,
      'entidad' => $entidad,
      'nacionantecedentes' => $nacionalidadesantecedente
    ]);
  }

  public function editarmoral($id)
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageHeader' => true,
      'pageName' => 'Editar Moral'
    ];

    $nacionalidades = db::table('nacionalidades')->get();
    $nacionantecedentes = db::table('nacionalidad_antecedentes')->get();
    $paises = db::table('paises')->get();
    $entidad = db::table('entidad_federativa')->get();

    $datos2 = DB::TABLE('morales')->where('id', $id)->first();

    $datos = Moral::where('id', '=', $id)->with('personasmorales')->with('perfil')->first();
    $origen = ActividadGiro::get();
    $destino = DestinoRecursos::get();
    $instrumento = InstrumentoMonetario::get();
    $divisa = Divisa::get();
    $profesiones = Profesion::get();
    $actividad = ActividadGiro::get();
    //TODO corroborar consultas
    $profesion = Moral::where('id', $id)->first()->personasMorales[0]->job;
    $actividad = ActividadGiro::get();
    $efresidencia = EFResidencia::get();
    $gresidencia = Moral::where('id', $id)->first()->ef;
    $residencia = EntidadFederativa::where('code', $gresidencia)->first()->entity;

    if (isset($datos)) {

      return view('/morales/morales-editar', compact(
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
        'actividad',
        'nacionantecedentes'
      ));
    }
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

  public function agregarArchivo($archivo, $cid, $uid, $tipo)
  {
    if (isset($archivo)) {
      $path = 'personas-morales/' . Str::slug($tipo);
      $extension = strtolower($archivo->getClientOriginalExtension());
      if (strtolower($extension) == 'pdf') {
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($archivo));
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = $tipo;
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $uid;
        $uploads->tipo = 1;
        $uploads->save();
      }
    }
    return '';
  }

  public function agregarImagen($archivo, $cid, $uid, $tipo)
  {
    if (isset($archivo)) {
      $image = Image::make(File::get($archivo));
      $path = 'personas-morales/imagenes';
      $id = rand();
      $extension = ("jpg");
      //$extension = strtolower($archivo->getClientOriginalExtension());
      $filename = $id . '-destino.' . $extension;
      $image->resize(1280, null, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
      });

      Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode($extension, 30));
      $uploads = new Files();
      $uploads->client_id = $cid;
      $uploads->type = $tipo;
      $uploads->path = $path;
      $uploads->extension = $extension;
      $uploads->name = $filename;
      $uploads->full = $path . '/' . $filename;
      $uploads->user_id = $uid;
      $uploads->tipo = 1;
      $uploads->save();
    }
    return;
  }

  public function create(Request $request)
  {
    /*$request->validate([
        'email' => 'required|string|email|unique:users'
      ]);*/
    $moral = new Moral($request->all());
    $moral->id_nacionalidad_antecedente = $request->nacionalidad_ante;

    DB::beginTransaction();
    try {
      $moral->save();
      if (isset($request->personasMorales)) {
        $moral->personasMorales()->createMany($request->personasMorales);
      }
    } catch (\Exception $ex) {
      DB::rollback();
      return response()->json(['error' => $ex->getMessage()], 500);
    }
    DB::commit();


    $cid = $moral->id;
    $user = $request->user();
    $userid = $user->id;
    $this->agregarArchivo($request->file('entrevista'), $cid, $userid, 'Entrevista');
    $this->agregarArchivo($request->file('reporte'), $cid, $userid, 'Reporte');
    $this->agregarArchivo($request->file('autorizacion_reporte_circulo_credito'), $cid, $userid, 'Reporte Circulo');
    $this->agregarArchivo($request->file('ultima_declaracion_anual'), $cid, $userid, 'Declaracion anual');
    $this->agregarArchivo($request->file('estados_financieros_anuales'), $cid, $userid, 'Estados Anuales');
    $this->agregarArchivo($request->file('estados_financieros_recientes'), $cid, $userid, 'Estados Recientes');
    $this->agregarImagen($request->file('fotografia1'), $cid, $userid, 'Imagen1');
    $this->agregarImagen($request->file('fotografia2'), $cid, $userid, 'Imagen2');

    $random = Str::random(50);
    $category = new User;
    $category->name = $request->nombre;
    $category->lastname = "-";
    $category->o_lastname = "-";
    $category->email = $request->email;
    $category->role = 3;
    $category->status = 'Activo';
    $category->password = bcrypt('123456');
    $category->activate = $random;
    $category->save();
    //TODO cuidar email
    //Mail::to(array($request->email))->send(new EmailVerification($random));


    $moral->refresh();
    $personasMoralesCurp = array_column($request->personasMorales, 'curp');
    foreach ($moral->personasMorales as $personaMoral) {
      $key = array_search($personaMoral->curp, $personasMoralesCurp);

      $files = $request->file('personasMorales');
      $fileine = $files[$key]['inefront'] ? $files[$key]['inefront'] : 1;
      $ineback = $files[$key]['ineback'] ? $files[$key]['ineback'] : 1;

      if ($fileine != 1) {
        $path = 'personas-morales/ine';
        // $extension = strtolower($fileine->getClientOriginalExtension());
        $extension = ("jpg");
        if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
          $filename = $personaMoral->id . '-frontal.' . $extension;
          $uploads = new Files();
          $uploads->client_id = $personaMoral->id;
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
          $uploads->client_id = $personaMoral->id;
          $uploads->type = 'INE';
          $uploads->path = $path;
          $uploads->extension = $extension;
          $uploads->name = $personaMoral->id . '-frontal.' . $extension;
          $uploads->full = $path . '/' . $personaMoral->id . '.' . $extension;
          $uploads->user_id = $user->id;
          $uploads->tipo = 1;
          $uploads->save();
          Storage::disk('public')->put($path . '/' . $personaMoral->id . '.' . $extension, File::get($fileine));
        }
      }

      if ($ineback != 1) {
        $path = 'personas-morales/ine';
        $extension = ("jpg");
        if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
          $filename = $personaMoral->id . '-trasera.' . $extension;
          $uploads = new Files();
          $uploads->client_id = $personaMoral->id;
          $uploads->type = 'INE';
          $uploads->path = $path;
          $uploads->extension = $extension;
          $uploads->name = $filename;
          $uploads->full = $path . '/' . $filename;
          $uploads->user_id = $user->id;
          $uploads->tipo = 1;
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
          $uploads->client_id = $personaMoral->id;
          $uploads->type = 'INE';
          $uploads->path = $path;
          $uploads->extension = $extension;
          $uploads->name = $personaMoral->id . '-trasera.' . $extension;
          $uploads->full = $path . '/' . $personaMoral->id . '.' . $extension;
          $uploads->user_id = $user->id;
          $uploads->tipo = 1;
          $uploads->save();
          Storage::disk('public')->put($path . '/' . $personaMoral->id . '.' . $extension, File::get($ineback));
        }
      }
    }


    $filecurp = $request->file('filecurp') ? $request->file('filecurp') : 1;
    $filedom = $request->file('filedom') ? $request->file('filedom') : 1;
    $filerfc = $request->file('filerfc') ? $request->file('filerfc') : 1;


    if ($filecurp != 1) {
      $path = 'morales/acta';
      $extension = "jpg";
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'ACTA';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->tipo = 1;
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
        $uploads->type = 'ACTA';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->tipo = 1;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($filecurp));
      }
    }

    if ($filedom != 1) {
      $path = 'morales/dom';
      $extension = "jpg";
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
        $uploads->tipo = 1;
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
        $uploads->tipo = 1;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($filedom));
      }
    }


    if ($filerfc != 1) {
      $path = 'morales/rfc';
      $extension = "jpg";
      //$extension = strtolower($filerfc->getClientOriginalExtension());
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
        $uploads->tipo = 1;
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
        $uploads->tipo = 1;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($filerfc));
      }
    }


    return redirect('/morales/morales')->with('message', 'OK');
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

    return redirect('/morales/morales')->with('message', 'ED');
  }


  public function activar(Request $request)
  {
    $id = $request->id;
    $user = Moral::find($id);
    $user->status = 'Activo';
    $user->save();

    return response('OK', 200);
  }

  public function archivar(Request $request)
  {
    $id = $request->id;
    $user = Moral::find($id);
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

    $images = DB::TABLE('files')->where('client_id', $id)->where('tipo', 1)->get();

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

    $images = DB::TABLE('files')->where('client_id', $id)->where('tipo', 1)->get();
    $data = '';
    foreach ($images as $img) {
      $data .= '<tr><td>' . $img->type . '</td><td>' . $img->extension . '</td><td>' . $img->created_at . '</td><td><a href="/uploads/' . $img->full . '" target="_blank"><button  style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-eye primary"></i></button></a></td><td><a href="/storage/' . $img->full . '" target="_blank"><button  style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-download primary"></i></button></a></td></tr>';
    }

    return $data;
  }

  public function continuar($id, $limite = false)
  {
    $detinoC = new DestinoCredito();
    $Moral = Moral::where('id', $id)->first();
    $destino = DestinoRecursos::get();
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageHeader' => true,
      'pageName' => 'Continuar Registro'
    ];
    if ($limite) {
      return view('/morales/continuar', ['pageConfigs' => $pageConfigs,
        'moral' => $Moral,
        'limite' => $limite,
        'id' => $id, 'destino' => $destino]);
    }
    return view('/morales/continuar', ['pageConfigs' => $pageConfigs,
      'moral' => $Moral,
      'id' => $id, 'destino' => $destino]);
  }

  public function ebr($id)
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageName' => 'Criterios de Riesgos'
    ];
    $datos = PerfilMoral::where('id_moral', '=', $id)->first();
    $origen = OrigenRecursos::get();
    $destino = DestinoRecursos::get();
    $instrumento = InstrumentoMonetario::get();
    $divisa = Divisa::get();
    $profesiones = Profesion::get();
    $actividad = ActividadGiro::get();
    $efresidencia = EFResidencia::get();
    $gresidencia = Moral::where('id', $id)->first()->ef;
    $residencia = EntidadFederativa::where('code', $gresidencia)->first()->entity;
    $profesions = DB::TABLE('morales')->where('id', $id)->first()->giro;

    if (isset($datos)) {
      return view('/morales/ebr', compact(
        'pageConfigs',
        'id',
        'datos',
        'origen',
        'instrumento',
        'divisa',
        'destino',
        'profesiones',
        'profesions',
        'efresidencia',
        'residencia',
        'actividad'
      ));
    } else {
      return redirect()->route('perfil_web_ebr', ['id' => $id, 'redireccion' => true]);
    }
  }

  public function eebr(Request $request)
  {
    $cid = $request->id;

    $user = Auth::user();

    $args = array(
      'profesion' => $request->profesion ? $request->profesion : null,
      'actividad_giro' => $request->actividad ? $request->actividad : null,
      'efr' => $request->efr ? $request->efr : null,
      'limite_credito' => $request->limite_credito ? $request->limite_credito : null
    );

    $fields = array(
      'id_moral' => $cid
    );

    $update = PerfilMoral::updateOrCreate($fields, $args);
    $riesgos = new Riesgos();
    $riesgos->gradoMorales($cid);
    return redirect('/morales/morales')->with('ebr', 'OK');
  }

  public function listaNegraPDF($id)
  {
    $cliente = Client::where('id', $id)->with('listasNegras')->first();
    $documentos = Files::where('client_id', '=', $id)->get();

    return PDF::loadView('/clients/listaNegraPDF', compact('cliente', 'documentos'))->stream();
    //return view('/clients/listaNegraPDF', compact('cliente', 'documentos'));
  }

  public function getPerfil($id, $redireccion = 0)
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageName' => 'Personas Morales'
    ];
    $datos = PerfilMoral::where('id_moral', '=', $id)->first();
    $origen = OrigenRecursos::get();
    $destino = DestinoRecursos::get();
    $instrumento = InstrumentoMonetario::get();
    $divisa = Divisa::get();
    $profesiones = Profesion::get();
    $actividad = ActividadGiro::get();
    $profesion = DB::TABLE('morales')->where('id', $id)->first();


    if (isset($datos)) {
      return view('/morales/perfil', compact(
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
      return view('/morales/perfil', compact(
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

  public function pago(Request $request)
  {
    $cid = Credito_Moral::where('moral_id', $request->id)->where('status', 'Aprobado')->first()->id;
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
    $npago = new Pago_Moral();
    $npago->moral_id = $request->id;
    $npago->credito_id = $cid;
    $npago->pago = $request->monto;
    $npago->fpago = $request->fecha;
    $npago->forma = $forma;
    $npago->moneda = $moneda;
    $npago->origen = $request->origen;
    $npago->save();
    $alertas = new AlertasMorales();
    $alertas->verificarMoral($request, $cid);
    $alertas->validarRiesgoMorales($request->id, $cid, "Pago");
    $moral = Moral::where('id', $request->id)->first();
    $moral->credito_disponible = $moral->credito_disponible + $request->monto;
    $moral->save();
    $amortizaciones = Amortizacion_Morales::where('moral_id', $request->id)->where('credito_id', $cid)->where('liquidado', 0)->where('flujo', '>', 0)->orderBy('periodo', 'asc')->orderBy('id', 'asc')->get();
    $pago = $request->monto;
    $gcobranza = $request->gcobranza ? $request->gcobranza : 0;
    if ($gcobranza > 0) {
      $gc = Amortizacion_Morales::where('moral_id', $request->id)->where('credito_id', $cid)->where('liquidado', 0)->where('flujo', '>', 0)->orderBy('periodo', 'asc')->orderBy('id', 'asc')->first();
      $flujoc = $gc->flujo + $gcobranza;
      $lcob = $gc->gcobranza ? $gc->gcobranza : 0;
      $ncobranza = $gcobranza + $lcob;
      Amortizacion_Morales::where('id', $gc->id)->update(['gcobranza' => $gcobranza, 'flujo' => $flujoc]);
      $amortizaciones = Amortizacion_Morales::where('moral_id', $request->id)->where('credito_id', $cid)->where('liquidado', 0)->where('flujo', '>', 0)->orderBy('periodo', 'asc')->orderBy('id', 'asc')->get();
    }
    $cc = 0;
    $pagoid = $npago->id;
    $rfecha = $request->fecha;
    $rmonto = $request->monto;
    $rperiodo = 0;
    foreach ($amortizaciones as $amoritzacion) {

      if ($pago > 0) {
        $fecha1 = date_create(date('d-m-Y', strtotime($amoritzacion->fin)));
        $fecha2 = date_create(date('d-m-Y', strtotime($request->fecha)));

        $dias = str_replace('+', '', date_diff($fecha1, $fecha2)->format('%R%a'));

        $fecha11 = date_create(date('d-m-Y', strtotime($request->fecha)));
        $fecha22 = date_create(date('d-m-Y', strtotime($amoritzacion->inicio)));

        $dias2 = str_replace('+', '', date_diff($fecha11, $fecha22)->format('%R%a'));

        if ($dias <= 0) {
          if ($dias2 > 0) {
            $amortizaciones2 = Amortizacion_Morales::where('moral_id', $request->id)->where('credito_id', $cid)->where('liquidado', 0)->orderBy('periodo', 'desc')->orderBy('id', 'asc')->get();
            foreach ($amortizaciones2 as $amoritzacion2) {
              if ($pago > 0) {
                if ($amoritzacion2->pagos != 0) {
                  $flujo = $amoritzacion2->flujo - $amoritzacion2->pagos;

                  if ($flujo > $pago) {
                    $lam = Amortizacion_Morales::where('id', $amoritzacion2->id)->first();
                    $lpagos = $lam->pagos ? $lam->pagos : 0;
                    $restante = ($lam->flujo - $lpagos) - $pago;

                    $rpagos = new RelacionPago_Morales();
                    $rpagos->amortizacion_moral_id = $amoritzacion2->id;
                    $rpagos->pago_moral_id = $pagoid;
                    $rpagos->fecha_pago = $rfecha;
                    $rpagos->monto = $pago;
                    $rpagos->monto_total = $rmonto;
                    $rpagos->restante = $restante;
                    $rpagos->pago_restante = 0;
                    $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                    $rpagos->save();
                    $rmonto = 0;
                    Amortizacion_Morales::where('id', $amoritzacion2->id)->update(['pagos' => $pago]);
                    $pago = 0;
                  } else {
                    if ($flujo == $pago) {
                      $lam = Amortizacion_Morales::where('id', $amoritzacion2->id)->first();
                      $lpagos = $lam->pagos ? $lam->pagos : 0;
                      $restante = ($lam->flujo - $lpagos) - $pago;

                      $rpagos = new RelacionPago_Morales();
                      $rpagos->amortizacion_moral_id = $amoritzacion2->id;
                      $rpagos->pago_moral_id = $pagoid;
                      $rpagos->fecha_pago = $rfecha;
                      $rpagos->monto = $pago;
                      $rpagos->monto_total = $rmonto;
                      $rpagos->restante = $restante;
                      $rpagos->pago_restante = 0;
                      $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                      $rpagos->save();
                      $rmonto = 0;

                      Amortizacion_Morales::where('id', $amoritzacion2->id)->update(['pagos' => $pago, 'liquidado' => 1]);
                      $pago = 0;
                    } else {
                      $lam = Amortizacion_Morales::where('id', $amoritzacion2->id)->first();

                      $pagos = $lam->pagos ? $lam->pagos : 0;

                      $apago = $lam->flujo - $pagos;

                      $prest = $pago - $apago;

                      $rpagos = new RelacionPago_Morales();
                      $rpagos->amortizacion_moral_id = $amoritzacion2->id;
                      $rpagos->pago_moral_id = $pagoid;
                      $rpagos->fecha_pago = $rfecha;
                      $rpagos->monto = $apago;
                      $rpagos->monto_total = $rmonto;
                      $rpagos->restante = 0;
                      $rpagos->pago_restante = $prest;
                      $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                      $rpagos->save();
                      $rmonto = $prest;

                      Amortizacion_Morales::where('id', $amoritzacion2->id)->update(['pagos' => $lam->flujo, 'liquidado' => 1]);

                      $pago = $pago - $flujo;
                    }
                  }
                } else {
                  if ($amoritzacion2->flujo > $pago) {
                    $lam = Amortizacion_Morales::where('id', $amoritzacion2->id)->first();
                    $lpagos = $lam->pagos ? $lam->pagos : 0;
                    $restante = ($lam->flujo - $lpagos) - $pago;


                    $rpagos = new RelacionPago_Morales();
                    $rpagos->amortizacion_moral_id = $amoritzacion2->id;
                    $rpagos->pago_moral_id = $pagoid;
                    $rpagos->fecha_pago = $rfecha;
                    $rpagos->monto = $pago;
                    $rpagos->monto_total = $rmonto;
                    $rpagos->restante = $restante;
                    $rpagos->pago_restante = 0;
                    $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                    $rpagos->save();
                    $rmonto = 0;

                    Amortizacion_Morales::where('id', $amoritzacion2->id)->update(['pagos' => $pago]);
                    $pago = 0;
                  } else {
                    if ($amoritzacion2->flujo == $pago) {

                      $lam = Amortizacion_Morales::where('id', $amoritzacion2->id)->first();
                      $lpagos = $lam->pagos ? $lam->pagos : 0;
                      $restante = ($lam->flujo - $lpagos) - $pago;


                      $rpagos = new RelacionPago_Morales();
                      $rpagos->amortizacion_moral_id = $amoritzacion2->id;
                      $rpagos->pago_moral_id = $pagoid;
                      $rpagos->fecha_pago = $rfecha;
                      $rpagos->monto = $pago;
                      $rpagos->monto_total = $rmonto;
                      $rpagos->restante = $restante;
                      $rpagos->pago_restante = 0;
                      $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                      $rpagos->save();
                      $rmonto = 0;

                      Amortizacion_Morales::where('id', $amoritzacion2->id)->update(['pagos' => $pago, 'liquidado' => 1]);
                      $pago = 0;
                    } else {
                      $lam = Amortizacion_Morales::where('id', $amoritzacion2->id)->first();

                      $pagos = $lam->pagos ? $lam->pagos : 0;

                      $apago = $lam->flujo - $pagos;

                      $prest = $pago - $apago;

                      $rpagos = new RelacionPago_Morales();
                      $rpagos->amortizacion_moral_id = $amoritzacion2->id;
                      $rpagos->pago_moral_id = $pagoid;
                      $rpagos->fecha_pago = $rfecha;
                      $rpagos->monto = $apago;
                      $rpagos->monto_total = $rmonto;
                      $rpagos->restante = 0;
                      $rpagos->pago_restante = $prest;
                      $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                      $rpagos->save();
                      $rmonto = $prest;

                      Amortizacion_Morales::where('id', $amoritzacion2->id)->update(['pagos' => $lam->flujo, 'liquidado' => 1]);
                      $pago = $pago - $lam->flujo;
                    }
                  }
                }
              }
              $rperiodo = $amoritzacion2->periodo;
            }
          } else {
            if ($amoritzacion->pagos != 0) {
              $flujo = $amoritzacion->flujo - $amoritzacion->pagos;
              $pagos = $amoritzacion->pagos;
              if ($flujo > $pago) {

                $lam = Amortizacion_Morales::where('id', $amoritzacion->id)->first();
                $lpagos = $lam->pagos ? $lam->pagos : 0;
                $restante = ($lam->flujo - $lpagos) - $pago;


                $rpagos = new RelacionPago_Morales();
                $rpagos->amortizacion_moral_id = $amoritzacion->id;
                $rpagos->pago_moral_id = $pagoid;
                $rpagos->fecha_pago = $rfecha;
                $rpagos->monto = $pago;
                $rpagos->monto_total = $rmonto;
                $rpagos->restante = $restante;
                $rpagos->pago_restante = 0;
                $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                $rpagos->save();
                $rmonto = 0;

                Amortizacion_Morales::where('id', $amoritzacion->id)->update(['pagos' => ($pagos + $pago)]);
                $pago = 0;
              } else {
                if ($flujo == $pago) {

                  $lam = Amortizacion_Morales::where('id', $amoritzacion->id)->first();
                  $lpagos = $lam->pagos ? $lam->pagos : 0;
                  $restante = ($lam->flujo - $lpagos) - $pago;


                  $rpagos = new RelacionPago_Morales();
                  $rpagos->amortizacion_moral_id = $amoritzacion->id;
                  $rpagos->pago_moral_id = $pagoid;
                  $rpagos->fecha_pago = $rfecha;
                  $rpagos->monto = $pago;
                  $rpagos->monto_total = $rmonto;
                  $rpagos->restante = $restante;
                  $rpagos->pago_restante = 0;
                  $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                  $rpagos->save();
                  $rmonto = 0;

                  Amortizacion_Morales::where('id', $amoritzacion->id)->update(['pagos' => ($pagos + $pago), 'liquidado' => 1]);
                  $pago = 0;
                } else {
                  $lam = Amortizacion_Morales::where('id', $amoritzacion->id)->first();

                  $pagos = $lam->pagos ? $lam->pagos : 0;

                  $apago = $lam->flujo - $pagos;

                  $prest = $pago - $apago;

                  $rpagos = new RelacionPago_Morales();
                  $rpagos->amortizacion_moral_id = $amoritzacion->id;
                  $rpagos->pago_moral_id = $pagoid;
                  $rpagos->fecha_pago = $rfecha;
                  $rpagos->monto = $apago;
                  $rpagos->monto_total = $rmonto;
                  $rpagos->restante = 0;
                  $rpagos->pago_restante = $prest;
                  $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                  $rpagos->save();
                  $rmonto = $prest;

                  Amortizacion_Morales::where('id', $amoritzacion->id)->update(['pagos' => $lam->flujo, 'liquidado' => 1]);
                  $pago = $pago - $flujo;
                }
              }
            } else {
              if ($amoritzacion->flujo > $pago) {
                $lam = Amortizacion_Morales::where('id', $amoritzacion->id)->first();
                $lpagos = $lam->pagos ? $lam->pagos : 0;
                $restante = ($lam->flujo - $lpagos) - $pago;


                $rpagos = new RelacionPago_Morales();
                $rpagos->amortizacion_moral_id = $amoritzacion->id;
                $rpagos->pago_moral_id = $pagoid;
                $rpagos->fecha_pago = $rfecha;
                $rpagos->monto = $pago;
                $rpagos->monto_total = $rmonto;
                $rpagos->restante = $restante;
                $rpagos->pago_restante = 0;
                $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                $rpagos->save();
                $rmonto = 0;

                Amortizacion_Morales::where('id', $amoritzacion->id)->update(['pagos' => $pago]);
                $pago = 0;
              } else {
                if ($amoritzacion->flujo == $pago) {
                  $lam = Amortizacion_Morales::where('id', $amoritzacion->id)->first();
                  $lpagos = $lam->pagos ? $lam->pagos : 0;
                  $restante = ($lam->flujo - $lpagos) - $pago;

                  $rpagos = new RelacionPago_Morales();
                  $rpagos->amortizacion_moral_id = $amoritzacion->id;
                  $rpagos->pago_moral_id = $pagoid;
                  $rpagos->fecha_pago = $rfecha;
                  $rpagos->monto = $pago;
                  $rpagos->monto_total = $rmonto;
                  $rpagos->restante = $restante;
                  $rpagos->pago_restante = 0;
                  $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                  $rpagos->save();
                  $rmonto = 0;


                  Amortizacion_Morales::where('id', $amoritzacion->id)->update(['pagos' => $pago, 'liquidado' => 1]);
                  $pago = 0;
                } else {
                  $lam = Amortizacion_Morales::where('id', $amoritzacion->id)->first();

                  $pagos = $lam->pagos ? $lam->pagos : 0;

                  $apago = $lam->flujo - $pagos;

                  $prest = $pago - $apago;

                  $rpagos = new RelacionPago_Morales();
                  $rpagos->amortizacion_moral_id = $amoritzacion->id;
                  $rpagos->pago_moral_id = $pagoid;
                  $rpagos->fecha_pago = $rfecha;
                  $rpagos->monto = $apago;
                  $rpagos->monto_total = $rmonto;
                  $rpagos->restante = 0;
                  $rpagos->pago_restante = $prest;
                  $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                  $rpagos->save();
                  $rmonto = $prest;

                  Amortizacion_Morales::where('id', $amoritzacion->id)->update(['pagos' => $lam->flujo, 'liquidado' => 1]);
                  $pago = $pago - $lam->flujo;
                }
              }
            }
          }


        } else {
          if ($amoritzacion->pagos != 0) {
            $flujo = $amoritzacion->flujo - $amoritzacion->pagos;
            $pagos = $amoritzacion->pagos;
            if ($flujo > $pago) {
              $lam = Amortizacion_Morales::where('id', $amoritzacion->id)->first();
              $lpagos = $lam->pagos ? $lam->pagos : 0;
              $restante = ($lam->flujo - $lpagos) - $pago;


              $rpagos = new RelacionPago_Morales();
              $rpagos->amortizacion_moral_id = $amoritzacion->id;
              $rpagos->pago_moral_id = $pagoid;
              $rpagos->fecha_pago = $rfecha;
              $rpagos->monto = $pago;
              $rpagos->monto_total = $rmonto;
              $rpagos->restante = $restante;
              $rpagos->pago_restante = 0;
              $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
              $rpagos->save();
              $rmonto = 0;

              Amortizacion_Morales::where('id', $amoritzacion->id)->update(['pagos' => ($pagos + $pago)]);
              $pago = 0;
            } else {
              if ($flujo == $pago) {
                $lam = Amortizacion_Morales::where('id', $amoritzacion->id)->first();
                $lpagos = $lam->pagos ? $lam->pagos : 0;
                $restante = ($lam->flujo - $lpagos) - $pago;


                $rpagos = new RelacionPago_Morales();
                $rpagos->amortizacion_moral_id = $amoritzacion->id;
                $rpagos->pago_moral_id = $pagoid;
                $rpagos->fecha_pago = $rfecha;
                $rpagos->monto = $pago;
                $rpagos->monto_total = $rmonto;
                $rpagos->restante = $restante;
                $rpagos->pago_restante = 0;
                $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                $rpagos->save();
                $rmonto = 0;

                Amortizacion_Morales::where('id', $amoritzacion->id)->update(['pagos' => ($pagos + $pago), 'liquidado' => 1]);
                $pago = 0;
              } else {


                $lam = Amortizacion_Morales::where('id', $amoritzacion->id)->first();

                $pagos = $lam->pagos ? $lam->pagos : 0;

                $apago = $lam->flujo - $pagos;

                $prest = $pago - $apago;

                $rpagos = new RelacionPago_Morales();
                $rpagos->amortizacion_moral_id = $amoritzacion->id;
                $rpagos->pago_moral_id = $pagoid;
                $rpagos->fecha_pago = $rfecha;
                $rpagos->monto = $apago;
                $rpagos->restante = 0;
                $rpagos->monto_total = $rmonto;
                $rpagos->pago_restante = $prest;
                $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                $rpagos->save();
                $rmonto = $prest;

                Amortizacion_Morales::where('id', $amoritzacion->id)->update(['pagos' => $lam->flujo, 'liquidado' => 1]);
                $pago = $pago - $flujo;
              }
            }
          } else {
            if ($amoritzacion->flujo > $pago) {
              $lam = Amortizacion_Morales::where('id', $amoritzacion->id)->first();
              $lpagos = $lam->pagos ? $lam->pagos : 0;
              $restante = ($lam->flujo - $lpagos) - $pago;


              $rpagos = new RelacionPago_Morales();
              $rpagos->amortizacion_moral_id = $amoritzacion->id;
              $rpagos->pago_moral_id = $pagoid;
              $rpagos->fecha_pago = $rfecha;
              $rpagos->monto = $pago;
              $rpagos->monto_total = $rmonto;
              $rpagos->restante = $restante;
              $rpagos->pago_restante = 0;
              $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
              $rpagos->save();
              $rmonto = 0;

              Amortizacion_Morales::where('id', $amoritzacion->id)->update(['pagos' => $pago]);
              $pago = 0;
            } else {
              if ($amoritzacion->flujo == $pago) {
                $lam = Amortizacion_Morales::where('id', $amoritzacion->id)->first();
                $lpagos = $lam->pagos ? $lam->pagos : 0;
                $restante = ($lam->flujo - $lpagos) - $pago;


                $rpagos = new RelacionPago_Morales();
                $rpagos->amortizacion_moral_id = $amoritzacion->id;
                $rpagos->pago_moral_id = $pagoid;
                $rpagos->fecha_pago = $rfecha;
                $rpagos->monto = $pago;
                $rpagos->monto_total = $rmonto;
                $rpagos->restante = $restante;
                $rpagos->pago_restante = 0;
                $rpagos->descripcion = 'Saldo restante de pago del periodo ' . $rperiodo;
                $rpagos->save();
                $rmonto = 0;

                Amortizacion_Morales::where('id', $amoritzacion->id)->update(['pagos' => $pago, 'liquidado' => 1]);
                $pago = 0;
              } else {
                $lam = Amortizacion_Morales::where('id', $amoritzacion->id)->first();

                $pagos = $lam->pagos ? $lam->pagos : 0;

                $apago = $lam->flujo - $pagos;

                $prest = $pago - $apago;

                $rpagos = new RelacionPago_Morales();
                $rpagos->amortizacion_moral_id = $amoritzacion->id;
                $rpagos->pago_moral_id = $pagoid;
                $rpagos->fecha_pago = $rfecha;
                $rpagos->monto = $apago;
                $rpagos->restante = 0;
                $rpagos->monto_total = $rmonto;
                $rpagos->pago_restante = $prest;
                $rpagos->descripcion = 'Monto pagado por el cliente';
                $rpagos->save();
                $rmonto = $prest;

                Amortizacion_Morales::where('id', $amoritzacion->id)->update(['pagos' => $lam->flujo, 'liquidado' => 1]);
                $pago = $pago - $lam->flujo;
              }
            }
          }

        }
      }
      $rperiodo = $amoritzacion->periodo;
    }
    return redirect('/morales/info/' . $request->id)->with('pago', 'OK');
  }

  public function infocredito($id)
  {
    $result = Credito_Moral::where('moral_id', $id)->get();
    return datatables()->of($result)
      ->toJson();
  }

  public function infotasas($id)
  {
    $result = Credito_Moral::where('moral_id', $id)->get();
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
    if (isset($request->limite)) {
      Moral::where('id', $id)->update(['limite_credito' => $request->limite, 'credito_disponible' => $request->limite]);
    }
    $moral = Moral::where('id', $id)->first();
    if ($moral->limite_credito < $request->sliderInput) {
      return redirect()->action([Morales::class, 'continuar'], ['id' => $id, 'limite' => true]);
    } else {
      $moral->credito_disponible = $moral->credito_disponible - $request->sliderInput;
      $moral->save();
    }
    $ncredito = new Credito_Moral();
    $ncredito->moral_id = $id;
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

    $moral = Moral::where('id', $id)->update(['status' => 'credito']);
    $detinoC = new DestinoCreditoMorales();
    $destino = $detinoC::all();
    $detinoC->id_credito_moral = $ncredito->id;
    $detinoC->id_destino_recursos = $request->recurso;
    $detinoC->titular = $request->titular;
    $detinoC->numero_cuenta_clabe = $request->numero_cuenta_clabe;
    $detinoC->tipo_cuenta = $request->tipo_cuenta;
    $detinoC->save();
    $alerta = new AlertasMorales();
    $alerta->validarDestinoMoral($request, $id, $ncredito->id);
    $alerta->validarRiesgoMorales($id, $ncredito->id, "Nuevo credito");
    return redirect('/morales/morales')->with('credito', 'OK');
  }

  public function infoamortizacion($id)
  {
    $totalCreditos = [];
    $data = Credito_Moral::where('moral_id', $id)->get();
    $hoy = date('Y-m-d');
    if (!empty($data)) {
      foreach ($data as $credito) {
        $result = array();
        $amortizacion = Amortizacion_Morales::where('moral_id', $id)->where('credito_id', $credito->id)->orderBy('id', 'asc')->get();
        if (!empty(count($amortizacion))) {
          foreach ($amortizacion as $gdata) {
            if ($gdata->liquidado == 0) {
              $fecha1 = date_create(date('Y-m-d'));
              $fecha2 = date_create($gdata->fin);
              $dias = str_replace('+', '', date_diff($fecha1, $fecha2)->format('%R%a'));
              $tasa = (Credito_Moral::where('id', $gdata->credito_id)->first()->tasa) / 100;

              if ($dias < 0 && $gdata->dia_mora != $hoy) {
                $dias = abs($dias);
                $intmora = ((($gdata->amortizacion * $tasa) * 2) / 360) * $dias;
                $ivamora = $intmora * 0.16;
                $moratorios = doubleval(number_format($intmora, 2)) + doubleval(number_format($ivamora, 2));
                $lgcobranza = $gdata->gcobranza ? $gdata->gcobranza : 0;
                $gcobranza = 200;
                $ivacobranza = doubleval(number_format($gcobranza * 0.16, 2));
                if (empty($lgcobranza)) {
                  $nflujo = $gdata->amortizacion + $gdata->intereses + $gdata->iva + $moratorios + $gcobranza + $ivacobranza;

                  $mflujo = $gdata->amortizacion + $gdata->intereses + $gdata->iva + $gcobranza + $ivacobranza;

                  $nhistorialflujo = new HistorialFlujos_Morales();
                  $nhistorialflujo->amortizacion_id = $gdata->id;
                  $nhistorialflujo->monto = $mflujo;
                  $nhistorialflujo->cambio = $gcobranza + $ivacobranza;
                  $nhistorialflujo->descripcion = 'Gastos De Cobranza';
                  $nhistorialflujo->save();

                } else {
                  $nflujo = $gdata->flujo + $moratorios;
                }

                Amortizacion_Morales::where('id', $gdata->id)->update(['flujo' => $nflujo, 'dias_mora' => $dias, 'int_mora' => $intmora, 'iva_mora' => $ivamora, 'gcobranza' => $gcobranza, 'dia_mora' => $hoy, 'iva_cobranza' => $ivacobranza]);

                HistorialFlujos_Morales::where('amortizacion_id', $gdata->id)->where('descripcion', 'Gastos Moratorios')->delete();
                $nhistorialflujo = new HistorialFlujos_Morales();
                $nhistorialflujo->amortizacion_id = $gdata->id;
                $nhistorialflujo->monto = $nflujo;
                $nhistorialflujo->cambio = $moratorios;
                $nhistorialflujo->descripcion = 'Gastos Moratorios (Interes mora: $' . number_format($intmora, 2) . ' + Iva mora: $' . number_format($ivamora, 2) . ')';
                $nhistorialflujo->save();
              }
            }
          }

          $amortizacion = Amortizacion_Morales::selectRaw("
              id,
              moral_id,
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
          )->where('moral_id', $id)->where('credito_id', $credito->id)->orderBy('id', 'asc')->get();
          $result = $amortizacion;
        } else {

          $plazo = $credito->plazo;
          $tinteres = $credito->tasa;
          $monto = $credito->monto;
          $frecuencia = $credito->frecuencia;
          $amortizaciones = $credito->amortizacion;
          $forma = $credito->fpago;
          $disposicion = $credito->disposicion;
          $nuevafecha = '';
          $dias = '';
          $mdis = number_format($monto * -1, 2);
          $saldo = $monto;
          $comision = doubleval(number_format($monto * 0.01, 2));
          $civa = $credito->iva;
          $intereses = 0;
          $amortizacion = 0;
          $iva = 0;
          $flujo = 0;
          $addt = '';
          $add = 1;
          $sumintereses = 0;
          $sumiva = 0;
          $sumflujo = 0;
          $cid = $credito->id;

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
            $amm = new Amortizacion_Morales();
            $amm->moral_id = $id;
            $amm->credito_id = $credito->id;
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

            $nhistorialflujo = new HistorialFlujos_Morales();
            $nhistorialflujo->amortizacion_id = $amm->id;
            $nhistorialflujo->monto = $key->flujo ? str_replace(',', '', $key->flujo) : null;
            $nhistorialflujo->cambio = $key->flujo ? str_replace(',', '', $key->flujo) : null;
            $nhistorialflujo->descripcion = 'Flujo Original De Amortización';
            $nhistorialflujo->save();

          }


          $amortizacion = Amortizacion_Morales::where('moral_id', $id)->where('credito_id', $credito->id)->orderBy('id', 'asc')->get();
          foreach ($amortizacion as $gdata) {
            if ($gdata->liquidado == 0) {
              $fecha1 = date_create(date('Y-m-d'));
              $fecha2 = date_create($gdata->fin);
              $dias = str_replace('+', '', date_diff($fecha1, $fecha2)->format('%R%a'));
              $tasa = (Credito_Moral::where('id', $gdata->credito_id)->first()->tasa) / 100;

              if ($dias < 0 && $gdata->dia_mora != $hoy) {
                $dias = abs($dias);
                $intmora = ((($gdata->amortizacion * $tasa) * 2) / 360) * $dias;
                $ivamora = $intmora * 0.16;
                $moratorios = doubleval(number_format($intmora, 2)) + doubleval(number_format($ivamora, 2));
                $lgcobranza = $gdata->gcobranza ? $gdata->gcobranza : 0;
                $gcobranza = 200;
                $ivacobranza = doubleval(number_format($gcobranza * 0.16, 2));
                if (empty($lgcobranza)) {
                  $nflujo = $gdata->amortizacion + $gdata->intereses + $gdata->iva + $moratorios + $gcobranza + $ivacobranza;

                  $mflujo = $gdata->amortizacion + $gdata->intereses + $gdata->iva + $gcobranza + $ivacobranza;

                  $nhistorialflujo = new HistorialFlujos;
                  $nhistorialflujo->amortizacion_id = $gdata->id;
                  $nhistorialflujo->monto = $mflujo;
                  $nhistorialflujo->cambio = $gcobranza + $ivacobranza;
                  $nhistorialflujo->descripcion = 'Gastos De Cobranza';
                  $nhistorialflujo->save();


                } else {
                  $nflujo = $gdata->flujo + $moratorios;
                }
                Amortizacion_Morales::where('id', $gdata->id)->update(['flujo' => $nflujo, 'dias_mora' => $dias, 'int_mora' => $intmora, 'iva_mora' => $ivamora, 'gcobranza' => $gcobranza, 'dia_mora' => $hoy, 'iva_cobranza' => $ivacobranza]);

                HistorialFlujos_Morales::where('amortizacion_id', $gdata->id)->where('descripcion', 'Gastos Moratorios')->delete();
                $nhistorialflujo = new HistorialFlujos_Morales();
                $nhistorialflujo->amortizacion_id = $gdata->id;
                $nhistorialflujo->monto = $nflujo;
                $nhistorialflujo->cambio = $moratorios;
                $nhistorialflujo->descripcion = 'Gastos Moratorios (Interes mora: $' . number_format($intmora, 2) . ' + Iva mora: $' . number_format($ivamora, 2) . ')';
                $nhistorialflujo->save();

              }
            }
          }
          $amortizacion = Amortizacion_Morales::selectRaw("
              id,
              moral_id,
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
          )->where('moral_id', $id)->where('credito_id', $credito->id)->orderBy('id', 'asc')->get();
          $result = $amortizacion;
        }
        $tabla = datatables()->of($result)
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
        array_push($totalCreditos, $tabla);
      }
    }
    return $totalCreditos;
  }

  public function infohistorialflujo(Request $request)
  {
    $id = $request->id;

    $result = HistorialFlujos_Morales::where('amortizacion_id', $id)->get();

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

    $result = Amortizacion_Morales::where('id', $id)->get();

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

  public function infopagosaplicados(Request $request)
  {
    $id = $request->id;

    $result = RelacionPago_Morales::where('amortizacion_moral_id', $id)->get();

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

  public function infopagos($id)
  {
    $data = Credito_Moral::where('moral_id', $id)->first();
    $creditoid = 0;
    if (isset($data)) {
      $creditoid = $data->id;
    }

    $result = DB::SELECT("SELECT periodo,fpago,0 as mora,0 as imora,0 as condonacion, 0 as iva,pago, `full`
                          FROM `pago_morales`
                          LEFT JOIN comprobante_pagos_morales on pago_moral_id=pago_morales.id
                          WHERE credito_id=$creditoid");

    return datatables()->of($result)
      ->addColumn('comprobante', function ($query) {
        return '<a href="/uploads/' . $query->full . '" target="popup" onclick="window.open(\'/uploads/' . $query->full . '\',\'popup\',\'width=600,height=600\'); return false;"><button  style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-eye primary"></i></button></a>  <a href="/storage/' . $query->full . '" target="_blank"><button  style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-download primary"></i></button></a>';
      })
      ->rawColumns(['comprobante'])
      ->toJson();
  }
}
