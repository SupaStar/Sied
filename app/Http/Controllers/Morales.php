<?php

namespace App\Http\Controllers;

use App\ActividadGiro;
use App\DestinoRecursos;
use App\Divisa;
use App\EFResidencia;
use App\EntidadFederativa;
use App\InstrumentoMonetario;
use App\OrigenRecursos;
use App\PerfilMoral;
use App\Profesion;
use App\Riesgos;
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
    return view('/morales/info', [
      'pageConfigs' => $pageConfigs,
      'nacionalidades' => $nacionalidades,
      'paises' => $paises,
      'entidad' => $entidad,
      'datos' => $datos,
      'miid' => $id

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

    return view('/morales/riesgo', [
      'pageConfigs' => $pageConfigs
    ]);
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
              <a href="/morales/info/' . $query->id . '" title="Información"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-eye"></i></button></a>
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
    $a=$riesgos->gradoMorales($cid);
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
      $extension = strtolower($fileine->getClientOriginalExtension());
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
      $extension = strtolower($ineback->getClientOriginalExtension());
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
      $extension = strtolower($filecurp->getClientOriginalExtension());
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
      $extension = strtolower($filedom->getClientOriginalExtension());
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
      $extension = strtolower($filecom1->getClientOriginalExtension());
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
      $extension = strtolower($filecom2->getClientOriginalExtension());
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
      $extension = strtolower($filecom3->getClientOriginalExtension());
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
      $extension = strtolower($filerfc->getClientOriginalExtension());
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

    return view('/morales/nueva-empresa', [
      'pageConfigs' => $pageConfigs,
      'nacionalidades' => $nacionalidades,
      'paises' => $paises,
      'entidad' => $entidad,
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
    $paises = db::table('paises')->get();
    $entidad = db::table('entidad_federativa')->get();

    $datos2 = DB::TABLE('morales')->where('id', $id)->first();

    $datos = Moral::where('id', '=', $id)->with('personasmorales')->first();
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

      return view('/morales/fisicas-editar', compact(
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
      $extension = strtolower($archivo->getClientOriginalExtension());
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
        $extension = strtolower($fileine->getClientOriginalExtension());
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
        $extension = strtolower($ineback->getClientOriginalExtension());
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
      $extension = strtolower($filecurp->getClientOriginalExtension());
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
      $extension = strtolower($filedom->getClientOriginalExtension());
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
      $extension = strtolower($filerfc->getClientOriginalExtension());
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

    $images = DB::TABLE('files')->where('client_id', $id)->where('tipo',1)->get();

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

    $images = DB::TABLE('files')->where('client_id', $id)->where('tipo',1)->get();
    $data = '';
    foreach ($images as $img) {
      $data .= '<tr><td>' . $img->type . '</td><td>' . $img->extension . '</td><td>' . $img->created_at . '</td><td><a href="/uploads/' . $img->full . '" target="_blank"><button  style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-eye primary"></i></button></a></td><td><a href="/storage/' . $img->full . '" target="_blank"><button  style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-download primary"></i></button></a></td></tr>';
    }

    return $data;
  }

  public function continuar()
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageHeader' => true,
      'pageName' => 'Continuar Registro'
    ];

    return view('/clients/continuar', ['pageConfigs' => $pageConfigs]);
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
      'efr' => $request->efr ? $request->efr : null
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

}
