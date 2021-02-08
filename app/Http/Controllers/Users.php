<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerification;

class Users extends Controller
{

  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('checkStatus');
  }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $pageConfigs = [
        'mainLayoutType' => 'vertical',
          'pageName' => 'Lista de Usuarios'
      ];

      return view('/users/users', [
          'pageConfigs' => $pageConfigs
      ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function new()
    {
      $pageConfigs = [
        'mainLayoutType' => 'vertical',
          'pageHeader' => true,
          'pageName' => 'Agregar un Usuario'
      ];
      return view('/users/new-user', [
        'pageConfigs' => $pageConfigs
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
      $request->validate([
        'email' => 'required|string|email|unique:users',
        'status' => 'required|string|',
        'rol' => 'required|string|'
    ]);

      $random = Str::random(50);

       $category = new User;
       $category->name = $request->name;
       $category->lastname = $request->lastname;
       $category->o_lastname = $request->olastname;
       $category->email = $request->email;
       $category->role = $request->rol;
       $category->status = $request->status;
       $category->password = bcrypt('123456');
       $category->activate = $random;
       $category->save();

        Mail::to(array($request->email))->send(new EmailVerification($random));

       return redirect('/usuarios/lista')->with('message', 'OK');
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

     public function GetUsers(Request $request)
     {
        if($request->filtro == '1000'){
          $result = DB::table('users')->where('activate','<>', '')->where('role','<>', 3);
        }elseif($request->filtro == ''){
          $result = DB::table('users')->where('status','Activo')->where('activate', '')->where('role','<>', 3);
        }elseif($request->filtro == 3){
          $result = DB::table('users')->where('status','Desactivado')->where('activate', '')->where('role','<>', 3);
        }elseif($request->filtro == 5){
          $result = DB::table('users')->where('role',3);
        }else{
          $result = DB::table('users')->where('role',$request->filtro)->where('status','Activo')->where('activate', '');
        }
          return datatables()->of($result)
          ->addColumn('rol', function($query) {
          if($query->role == 1){
            return 'Administrador';
          }
          if($query->role == 2){
            return 'Analista';
          }
          if($query->role == 3){
            return 'Cliente';
          }
         })
         ->addColumn('actions', function($query) {
                $user = Auth::user();

                  if($user->id == $query->id){
                    return '<button style="z-index:999" type="button" class="btn btn-default"></button>';
                  }else{
                    if($query->activate != ''){
                            if($query->status=='Activo'){
                              return '
                              <button style="z-index:999" type="button"onclick="resend('.$query->id.')"  class="btn btn-default"><i class="feather icon-mail"></i></button>
                              <a href="/usuarios/editar/'.$query->id.'"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-edit"></i></button></a>
                              <button style="z-index:999" type="button"onclick="del('.$query->id.')"  class="btn btn-default"><i class="feather icon-user-x"></i></button>';
                            }else{
                              return '
                              <button style="z-index:999" type="button"onclick="resend('.$query->id.')"  class="btn btn-default"><i class="feather icon-mail"></i></button>
                              <a href="/usuarios/editar/'.$query->id.'"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-edit"></i></button></a>
                              <button style="z-index:999" type="button"onclick="act('.$query->id.')"  class="btn btn-default"><i class="feather icon-user-check"></i></button>';
                            }
                        }else{
                            if($query->status=='Activo'){
                              return '<a href="/usuarios/editar/'.$query->id.'"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-edit"></i></button></a>
                              <button style="z-index:999" type="button"onclick="del('.$query->id.')"  class="btn btn-default"><i class="feather icon-user-x"></i></button>';
                            }else{
                              return '<a href="/usuarios/editar/'.$query->id.'"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-edit"></i></button></a>
                              <button style="z-index:999" type="button"onclick="act('.$query->id.')"  class="btn btn-default"><i class="feather icon-user-check"></i></button>';
                            }
                        }

                  }
         })
         ->addColumn('estado', function($query) {
          if($query->activate != ''){
            return 'Confirmación Pendiente';
          }else{
            return $query->status;

          }
         })
         ->rawColumns([ 'rol', 'actions','estado'])
     ->toJson();

     }



     public function activar(Request $request)
     {
      $id = $request->id;
      $user = User::find($id);
      $user->status = 'Activo';
      $user->save();

        return response('OK', 200);
     }

     public function reactivar(Request $request)
     {
      $id = $request->id;

      $guser = DB::TABLE('users')->where('id', $id)->first();

      Mail::to(array($guser->email))->send(new EmailVerification($guser->activate));

      return response('OK', 200);
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
