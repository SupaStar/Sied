<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Files;
use Image;
use App\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class Cliente extends Controller
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

    public function info()
    {
      $pageConfigs = [
        'mainLayoutType' => 'vertical',
          'pageName' => 'Personas Fisicas'
      ];

      return view('/clients/info', [
          'pageConfigs' => $pageConfigs
      ]);
    }

    public function fperfil()
    {
      $pageConfigs = [
        'mainLayoutType' => 'vertical',
          'pageName' => 'Personas Fisicas'
      ];

      return view('/clients/perfil', [
          'pageConfigs' => $pageConfigs
      ]);
    }

    public function getfisicas(Request $request)
    {

      if($request->filtro == 'Archivados'){
        $result = DB::table('clientes')->where('status', 'Archivado');
        }elseif($request->filtro == 'H'){
         $result = DB::table('clientes')->where('gender', 'H');
       }elseif($request->filtro == 'M'){
         $result = DB::table('clientes')->where('gender', 'M');
       }else{
         $result = DB::table('clientes');
       }

         return datatables()->of($result)
        ->addColumn('names', function($query) {
          return strtoupper($query->name.' '.$query->lastname.' '.$query->o_lastname);
        })

        ->addColumn('actions', function($query) {
          $user = Auth::user();
              return '
              <a href="/clientes/fisicas/info" title="Información"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-eye"></i></button></a>
              <a href="/clientes/fisicas/perfil" title="Perfil Transacional"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-file success"></i></button></a>
              <button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-bar-chart-2 warning"></i></button>
              <button onclick="files(2);" style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-archive primary"></i></button>
              <a href="/clientes/editar/fisica/'.$query->id.'" title="Editar"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-edit primary"></i></button></a>
              <button onclick="del(2);" style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-trash danger"></i></button>';
        })
        ->rawColumns(['actions','names'])
        ->toJson();

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function misdatos()
    {
      $pageConfigs = [
        'mainLayoutType' => 'vertical',
          'pageHeader' => true,
          'pageName' => 'Mis Datos'
      ];

      $user = Auth::user();

      $nacionalidades = db::table('nacionalidades')->get();
      $paises = db::table('paises')->get();
      $entidad = db::table('entidad_federativa')->get();
      $datos = db::table('clientes')->where('email', $user->email)->first();


      return view('/cliente/mis-datos', [
        'pageConfigs' => $pageConfigs,
        'nacionalidades' => $nacionalidades,
        'paises' => $paises,
        'entidad' => $entidad,
        'datos' => $datos,
        'usuario' => $user
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


    public function editar(Request $request)
    {

      $bith = substr($request->fnacimiento, 6, 4).'-'.substr($request->fnacimiento, 0, 2).'-'.substr($request->fnacimiento, 3, 2);
      $dd = date('Y-m-d', strtotime($bith));

      if($request->id =! null){
        $cliente = Client::find($request->id);
      }else{
        $cliente = new Client;
      }

      $user = Auth::user();

      $cliente->name = $request->nombre;
      $cliente->lastname = $request->apellidop;
      $cliente->o_lastname = $request->apellidom;
      $cliente->gender = $request->genero;
      $cliente->date_birth = $dd;
      $cliente->country_birth = $request->pais_nacimiento;
      $cliente->nationality = $request->lnacimiento;
      $cliente->place_birth = $request->nacionalidad;
      $cliente->job = $request->ocupacion;
      $cliente->street = $request->calle;
      $cliente->exterior = $request->exterior;
      $cliente->inside = $request->interior;
      $cliente->pc = $request->cp;
      $cliente->colony = $request->colonia;
      $cliente->town = $request->municipio;
      $cliente->city = $request->ciudad;
      $cliente->ef = $request->entidad;
      $cliente->country = $request->pais;
      $cliente->phone1 = $request->telefono1;
      $cliente->phone2 = $request->telefono2;
      $cliente->email = $request->memail;
      $cliente->curp = $request->curp;
      $cliente->rfc = $request->rfc;
      $cliente->c_name = $request->cnombre;
      $cliente->c_lastname = $request->capellidop;
      $cliente->c_o_lastname = $request->capellidom;
      $cliente->c_phone = $request->ctelefono;
      $cliente->c_email = $request->cemail;
      $cliente->save();

       return redirect('/')->with('message', 'OK');
      }


    public function create(Request $request)
    {
      $request->validate([
        'email' => 'required|string|email|unique:users'
      ]);

      $bith = substr($request->fnacimiento, 6, 4).'-'.substr($request->fnacimiento, 0, 2).'-'.substr($request->fnacimiento, 3, 2);
      $dd = date('Y-m-d', strtotime($bith));
      $cliente = new Client;
      $cliente->name = $request->nombre;
      $cliente->lastname = $request->apellidop;
      $cliente->o_lastname = $request->apellidom;
      $cliente->gender = $request->genero;
      $cliente->date_birth = $dd;
      $cliente->country_birth = $request->pais_nacimiento;
      $cliente->nationality = $request->lnacimiento;
      $cliente->place_birth = $request->nacionalidad;
      $cliente->job = $request->ocupacion;
      $cliente->street = $request->calle;
      $cliente->exterior = $request->exterior;
      $cliente->inside = $request->interior;
      $cliente->pc = $request->cp;
      $cliente->colony = $request->colonia;
      $cliente->town = $request->municipio;
      $cliente->city = $request->ciudad;
      $cliente->ef = $request->entidad;
      $cliente->country = $request->pais;
      $cliente->phone1 = $request->telefono1;
      $cliente->phone2 = $request->telefono2;
      $cliente->email = $request->memail;
      $cliente->curp = $request->curp;
      $cliente->rfc = $request->rfc;
      $cliente->c_name = $request->cnombre;
      $cliente->c_lastname = $request->capellidop;
      $cliente->c_o_lastname = $request->capellidom;
      $cliente->c_phone = $request->ctelefono;
      $cliente->c_email = $request->cemail;
      $cliente->save();

      $user = $request->user();
      $userid = $user->id;

      $random = Str::random(50);
      $category = new User;
      $category->name = $request->cnombre;
      $category->lastname = $request->capellidop;
      $category->o_lastname = $request->capellidom;
      $category->email = $request->email;
      $category->role = 3;
      $category->status = 'Activo';
      $category->password = bcrypt('123456');
      $category->activate = $random;
      $category->save();
      Mail::to(array($request->email))->send(new EmailVerification($random));


      $fileine = $request->file('fileine') ? $request->file('fileine'): 1;
      $filecurp = $request->file('filecurp') ? $request->file('filecurp'): 1;
      $filedom = $request->file('filedom') ? $request->file('filedom'): 1;
      $filecom1 = $request->file('filecom1') ? $request->file('filecom1'): 1;
      $filecom2 = $request->file('filecom2') ? $request->file('filecom2'): 1;
      $filecom3 = $request->file('filecom3') ? $request->file('filecom3'): 1;
      $filerfc = $request->file('filerfc') ? $request->file('filerfc'): 1;

      if($fileine != 1){
            $path = 'fisicas/ine';
            $extension = strtolower($fileine->getClientOriginalExtension());
            if(strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif')
            {
                $filename = $cliente->id.'.'.$extension;
                $uploads = new Files();
                $uploads->client_id = $cliente->id;
                $uploads->type = 'INE';
                $uploads->path = $path;
                $uploads->extension = $extension;
                $uploads->name = $filename;
                $uploads->full = $path.'/'.$filename;
                $uploads->user_id = $userid;
                $uploads->save();

                $image = Image::make(File::get($fileine));
                $image->resize(1280, null, function ($constraint) {
                  $constraint->aspectRatio();
                  $constraint->upsize();
                });
                Storage::disk('public')->put($path.'/'.$filename, (string) $image->encode($extension, 30));
              }else{
                $uploads = new Files();
                $uploads->client_id = $cliente->id;
                $uploads->type = 'INE';
                $uploads->path = $path;
                $uploads->extension = $extension;
                $uploads->name = $cliente->id.'.'.$extension;
                $uploads->full = $path.'/'.$cliente->id.'.'.$extension;
                $uploads->user_id = $userid;
                $uploads->save();
                Storage::disk('public')->put($path.'/'.$cliente->id.'.'.$extension, File::get($fileine));
            }
      }else{
        return redirect('/tamal');
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

     public function data(Request $request)
     {
      $id = $request->id;
      $guser = DB::TABLE('clientes')->where('id', $id)->first();
      $data = array(
        'nombre' => strtoupper($guser->name.' '.$guser->lastname.' '.$guser->o_lastname),
        'genero' => $guser->gender,
        'fnac' => $guser->date_birth,
        'pnac' => $guser->place_birth,
        'ocupacion' => $guser->job,
        'direccion' => $guser->street.' '.$guser->exterior.' '.$guser->inside.' '.$guser->town.' '.$guser->city.' '.$guser->pc,
        'telefonos' => $guser->phone1.' '.$guser->phone2,
        'email' => strtoupper($guser->email),
        'curp' => strtoupper($guser->curp),
        'rfc' => strtoupper($guser->rfc),
        'cnombre' => strtoupper($guser->c_name.' '.$guser->c_lastname.' '.$guser->c_o_lastname),
        'ctelefono' => $guser->c_phone,
        'cemail' => strtoupper($guser->c_email),
        'images' => array()
      );

      $images = DB::TABLE('files')->where('client_id', $id)->get();

      foreach($images as $img){
        array_push($data['images'], array( 'extension' => $img->extension, 'name' => $img->name,  'path' => $img->full ));
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

}
